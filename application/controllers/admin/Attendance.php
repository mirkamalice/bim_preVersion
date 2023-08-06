<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of attendance
 *
 * @author NaYeM
 */
class Attendance extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('attendance_model');
    }

    public function time_history()
    {
        $data['title'] = lang('time_history');
        $search = $this->input->post('search', TRUE);
        if (!empty($search)) {
            $data['edit'] = true;
        }
        $user_id = $this->input->post('user_id', TRUE);
        if (!empty($user_id)) {
            $data['user_id'] = $user_id;
        } else {
            $data['user_id'] = $this->session->userdata('user_id');
        }
        $data['active'] = date('Y');

        $attendance_info = get_order_by('tbl_attendance', array('user_id' => $data['user_id']), 'date_in', true);
        $data['mytime_info'] = $this->get_mytime_info($attendance_info);

        // retrive all data from department table
        $data['all_employee'] = $this->attendance_model->get_all_employee();

        $data['subview'] = $this->load->view('admin/attendance/time_history', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function time_history_pdf($user_id)
    {
        $data['title'] = lang('time_logs'); //Page title
        $this->attendance_model->_table_name = "tbl_attendance"; // table name
        $this->attendance_model->_order_by = "user_id"; // $id
        $attendance_info = $this->attendance_model->get_by(array('user_id' => $user_id), FALSE);
        $data['user_info'] = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
        $data['mytime_info'] = $this->get_mytime_info($attendance_info);
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/attendance/all_timehistory_pdf', $data, TRUE);
        pdf_create($viewfile, slug_it(lang('time_logs') . '-' . $data['user_info']->fullname));
    }

    public function get_mytime_info($attendance_info)
    {
        if (!empty($attendance_info)) {
            foreach ($attendance_info as $v_info) {
                if ($v_info->date_in == $v_info->date_out) {
                    $date = strftime(config_item('date_format'), strtotime($v_info->date_in));
                } else {
                    $date = lang('date_in') . ' : ' . strftime(config_item('date_format'), strtotime($v_info->date_in)) . ', ' . lang('day_out') . ': ' . strftime(config_item('date_format'), strtotime($v_info->date_out));
                }
                $clock_info[date('Y', strtotime($v_info->date_in))][date('W', strtotime($v_info->date_in))][$date] = get_result('tbl_attendance', array('attendance_id' => $v_info->attendance_id));
                //                    $this->attendance_model->get_mytime_info($v_info->attendance_id);
            }
            return $clock_info;
        }
    }

    public function edit_mytime($clock_id)
    {

        $data['title'] = lang('edit_time');
        $attendance_id = NULL;
        $data['clock_info'] = $this->attendance_model->get_mytime_info($attendance_id, $clock_id);
        $data['modal_subview'] = $this->load->view('admin/attendance/edit_mytime', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function delete_mytime($clock_id)
    {
        $clock_info = get_row('tbl_clock', array('clock_id' => $clock_id));
        if (!empty($clock_info)) {
            $attendance_info = count(get_result('tbl_clock', array('attendance_id' => $clock_info->attendance_id)));
            if ($attendance_info == 1) {
                // *********** Delete into tbl_clock *******************
                $this->attendance_model->_table_name = "tbl_attendance"; // table name
                $this->attendance_model->_primary_key = "attendance_id"; // $id
                $this->attendance_model->delete($clock_id);
            }
            // *********** Delete into tbl_clock *******************
            $this->attendance_model->_table_name = "tbl_clock"; // table name
            $this->attendance_model->_primary_key = "clock_id"; // $id
            $this->attendance_model->delete($clock_id);

            // *********** Delete into tbl_clock_history *******************
            $this->attendance_model->_table_name = "tbl_clock_history"; // table name
            $this->attendance_model->delete_multiple(array('clock_id' => $clock_id));

            echo json_encode(array("status" => 'success', 'message' => lang('deleted')));
            exit();
        } else {
            redirect('admin/attendance/time_history');
        }
    }

    public function cheanged_mytime($clock_id)
    {

        $cdata = $this->attendance_model->array_from_post(array('reason', 'clockin_edit', 'clockout_edit'));

        $data['clock_id'] = $clock_id;
        $data['user_id'] = $this->session->userdata('user_id');
        $data['clockin_edit'] = date('H:i:s', strtotime($cdata['clockin_edit']));
        $data['clockout_edit'] = date('H:i:s', strtotime($cdata['clockout_edit']));
        $data['reason'] = $cdata['reason'];

        //save data in database
        $this->attendance_model->_table_name = "tbl_clock_history"; // table name
        $this->attendance_model->_order_by = "clock_history_id"; // $id
        $history_id = $this->attendance_model->save($data);

        $user_type = $this->session->userdata('user_type');
        if ($user_type == 1) {
            $msg = lang('time_change_request_admin');
            $cldata['clockin_time'] = $data['clockin_edit'];
            $cldata['clockout_time'] = $data['clockout_edit'];

            $this->attendance_model->_table_name = 'tbl_clock';
            $this->attendance_model->_primary_key = 'clock_id';
            $this->attendance_model->save($cldata, $clock_id);

            $adata['status'] = '2';

            $this->attendance_model->_table_name = 'tbl_clock_history';
            $this->attendance_model->_primary_key = 'clock_history_id';
            $this->attendance_model->save($adata, $history_id);
        } else {
            $msg = lang('time_change_request_staff');
            $all_admin = $this->db->where('role_id', 1)->get('tbl_users')->result();
            $notifyUser = array();
            if (!empty($all_admin)) {
                foreach ($all_admin as $v_admin) {
                    if (!empty($v_admin)) {
                        if ($v_admin->user_id != $this->session->userdata('user_id')) {
                            array_push($notifyUser, $v_admin->user_id);
                            add_notification(array(
                                'to_user_id' => $v_admin->user_id,
                                'description' => 'not_timechange_request',
                                'icon' => 'file-text',
                                'link' => 'admin/attendance/view_changerequest/' . $history_id,
                                'value' => lang('edited') . ' ' . lang('by') . ' ' . $this->session->userdata('name'),
                            ));
                        }
                    }
                }
            }
            if (!empty($notifyUser)) {
                show_notification($notifyUser);
            }
        }

        $user_info = $this->db->where('user_id', $this->session->userdata('user_id'))->get('tbl_account_details')->row();
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'attendance',
            'module_field_id' => $clock_id,
            'activity' => 'activity_time_change_request',
            'icon' => 'fa-clock-o',
            'link' => 'admin/attendance/view_changerequest/' . $history_id,
            'value1' => $user_info->fullname,
            'value2' => $data['reason'],
        );

        // Update into tbl_project
        $this->attendance_model->_table_name = "tbl_activities"; //table name
        $this->attendance_model->_primary_key = "activities_id";
        $this->attendance_model->save($activities);

        // messages for user
        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/attendance/time_history');
    }

    public function add_time_manually()
    {

        $data['title'] = lang('view_timerequest');
        // retrive all data from department table
        $data['all_employee'] = $this->attendance_model->get_all_employee();

        $data['subview'] = $this->load->view('admin/attendance/add_time_manually', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function saved_manual_time()
    {

        $adata = $this->attendance_model->array_from_post(array('user_id', 'date_in', 'date_out'));
        $date_in = date('Y', strtotime($adata['date_in']));
        if (empty($adata['date_in'])) {
            $adata['date_in'] = date('Y-m-d');
        } elseif ($date_in >= 1969 && $date_in <= 1999) {
            $adata['date_in'] = date('Y-m-d');
        }
        $date_in = date('Y', strtotime($adata['date_out']));
        if (empty($adata['date_out'])) {
            $adata['date_out'] = date('Y-m-d');
        } elseif ($date_in >= 1969 && $date_in <= 1999) {
            $adata['date_out'] = date('Y-m-d');
        }
        $check_date = $this->attendance_model->check_by(array('user_id' => $adata['user_id'], 'date_in' => $adata['date_in']), 'tbl_attendance');
        $this->attendance_model->_table_name = "tbl_attendance"; // table name
        $this->attendance_model->_primary_key = "attendance_id"; // $id

        if (!empty($check_date)) { // if exis do not save date and return id
            if ($check_date->attendance_status != '1') {
                $adata['attendance_status'] = 1;
                $this->attendance_model->save($adata, $check_date->attendance_id);
            }
            $data['attendance_id'] = $check_date->attendance_id;
        } else { // else save data into tbl attendance
            $adata['attendance_status'] = 1;
            //save data into attendance table
            $data['attendance_id'] = $this->attendance_model->save($adata);
        }

        $data['clockin_time'] = date('H:i:s', strtotime($this->input->post('clockin_time', TRUE)));
        $data['clockout_time'] = date('H:i:s', strtotime($this->input->post('clockout_time', TRUE)));
        $data['clocking_status'] = 0;
        //save data in database
        $this->attendance_model->_table_name = "tbl_clock"; // table name
        $this->attendance_model->_primary_key = "clock_id"; // $id
        $id = $this->attendance_model->save($data);
        $user_info = $this->db->where('user_id', $adata['user_id'])->get('tbl_account_details')->row();
        $hdata['user_id'] = $adata['user_id'];
        $hdata['clock_id'] = $id;
        $hdata['clockin_edit'] = $data['clockin_time'];
        $hdata['clockout_edit'] = $data['clockout_time'];
        $hdata['reason'] = lang('manually_added') . ' ' . lang('by') . ' ' . $user_info->fullname;
        $user_type = $this->session->userdata('user_type');
        if ($user_type == 1) {
            $hdata['status'] = '2';
        }
        $this->attendance_model->_table_name = "tbl_clock_history"; // table name
        $this->attendance_model->_primary_key = "clock_history_id"; // $id
        $clock_history_id = $this->attendance_model->save($hdata);


        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'attendance',
            'module_field_id' => $data['attendance_id'],
            'activity' => 'activity_time_manually',
            'icon' => 'fa-clock-o',
            'link' => 'admin/attendance/view_changerequest/' . $clock_history_id,
            'value1' => $user_info->fullname,
            'value2' => $adata['date_in'] . ' To ' . $adata['date_out'],
        );

        // Update into tbl_project
        $this->attendance_model->_table_name = "tbl_activities"; //table name
        $this->attendance_model->_primary_key = "activities_id";
        $this->attendance_model->save($activities);

        if ($user_type != 1) {
            $all_admin = $this->db->where('role_id', 1)->get('tbl_users')->result();
            $notifyUser = array();
            if (!empty($all_admin)) {
                foreach ($all_admin as $v_admin) {
                    if (!empty($v_admin)) {
                        if ($v_admin->user_id != $this->session->userdata('user_id')) {
                            array_push($notifyUser, $v_admin->user_id);
                            add_notification(array(
                                'to_user_id' => $v_admin->user_id,
                                'description' => 'not_timechange_request',
                                'icon' => 'file-text',
                                'link' => 'admin/attendance/view_changerequest/' . $clock_history_id,
                                'value' => lang('manually_added') . ' ' . lang('by') . ' ' . $user_info->fullname,
                            ));
                        }
                    }
                }
            }
            if (!empty($notifyUser)) {
                show_notification($notifyUser);
            }
        }

        $type = "success";
        $message = lang('time_manually_added');
        set_message($type, $message);
        redirect('admin/attendance/timechange_request'); //redirect page

    }

    public function timechange_request()
    {
        $data['title'] = lang('timechange_request');
        $data['all_clock_history'] = $this->attendance_model->get_all_clock_history();
        $data['subview'] = $this->load->view('admin/attendance/list_all_request', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function timechange_requestList($type = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_clock_history';
            $this->datatables->column_order = array('clockin_edit', 'clockout_edit', 'user_id');
            $this->datatables->column_search = array('clockin_edit', 'clockout_edit', 'user_id');
            $this->datatables->order = array('clock_history_id' => 'desc');
            // get all invoice
            $fetch_data = $this->datatables->get_all_clock_history($type);;

            $data = array();
            foreach ($fetch_data as $_key => $v_clock_history) {

                $action = null;
                $sub_array = array();
                $emp_id = null;
                $emp_id .= '<a class="text-info" href="' . base_url() . 'admin/user/user_details/' . $v_clock_history->user_id . '">' . $v_clock_history->employment_id . '</a>';
                $sub_array[] = $emp_id;

                $name = null;
                $name .= '<a class="text-info" href="' . base_url() . 'admin/user/user_details/' . $v_clock_history->user_id . '">' . $v_clock_history->fullname . '</a>';
                $sub_array[] = $name;

                if ($v_clock_history->clockin_edit != "00:00:00") {
                    $clockin_edit = display_time($v_clock_history->clockin_edit);
                } else {
                    $clockin_edit = '-';
                }
                $sub_array[] = $clockin_edit;
                if ($v_clock_history->clockout_edit != "00:00:00") {
                    $clockout_edit = display_time($v_clock_history->clockout_edit);
                } else {
                    $clockout_edit = '-';
                }
                $sub_array[] = $clockout_edit;
                $label = null;
                $text = '-';
                if ($v_clock_history->status == 1) {
                    $label = 'warning';
                    $text = lang('pending');
                } elseif ($v_clock_history->status == 2) {
                    $label = 'success';
                    $text = lang('accepted');
                } elseif ($v_clock_history->status == 3) {
                    $label = 'danger';
                    $text = lang('rejected');
                }
                $sub_array[] = '<span class="label label-' . $label . '">' . $text . '</span>';
                $action .= '<a href="' . base_url() . 'admin/attendance/view_timerequest/' . $v_clock_history->clock_history_id . '"
                       class="btn btn-primary btn-xs"
                       title="' . lang('view') . '" data-toggle="modal" data-placement="top" data-target="#myModal"><span
                            class="fa fa-list-alt"></span></a>' . ' ';
                if ($this->session->userdata('user_type') == 1) {
                    $action .= ajax_anchor(base_url("admin/attendance/delete_request/" . $v_clock_history->clock_history_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }

            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function view_changerequest($clock_history_id)
    {
        $data['title'] = lang('view_timerequest');
        $data['clock_history'] = $this->attendance_model->get_all_clock_history($clock_history_id);
        $data['subview'] = $this->load->view('admin/attendance/request_details', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function view_timerequest($clock_history_id)
    {
        $data['title'] = lang('view_timerequest');
        $data['clock_history'] = $this->attendance_model->get_all_clock_history($clock_history_id);
        $data['subview'] = $this->load->view('admin/attendance/request_details', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data);
    }

    public function delete_request($id)
    {
        $clock_history = $this->attendance_model->check_by(array('clock_history_id' => $id), 'tbl_clock_history');
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'attendance',
            'module_field_id' => $id,
            'activity' => 'activity_delete_timechage_request',
            'icon' => 'fa-clock-o',
            'value1' => lang('clock_in') . ' ' . display_time($clock_history->clockin_edit),
            'value2' => lang('clock_out') . ' ' . display_time($clock_history->clockout_edit),
        );
        // Update into tbl_project
        $this->attendance_model->_table_name = "tbl_activities"; //table name
        $this->attendance_model->_primary_key = "activities_id";
        $this->attendance_model->save($activities);

        //save data into table.
        $this->attendance_model->_table_name = "tbl_clock_history"; // table name
        $this->attendance_model->_primary_key = "clock_history_id"; // $id
        $this->attendance_model->delete($id);

        $type = "success";
        $message = lang('delete_timechage_request');

        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }

    public function set_time_status($history_id)
    {
        // get input status
        // if status == 1 its pending
        // if status == 2 its accept  and set timein/timeout into tbl_clock
        // and 3 == reject
        $status = $this->input->post('status', TRUE);
        $clock_history_info = $this->invoice_model->check_by(array('clock_history_id' => $history_id), 'tbl_clock_history');

        if ($status == 2) {
            $clock_id = $this->input->post('clock_id', TRUE);
            $clockin_time = $this->input->post('clockin_time', TRUE);
            if (!empty($clockin_time)) {
                $data['clockin_time'] = $clockin_time;
            }
            $clockout_time = $this->input->post('clockout_time', TRUE);
            if (!empty($clockout_time)) {
                $data['clockout_time'] = $clockout_time;
            }
            $this->attendance_model->_table_name = 'tbl_clock';
            $this->attendance_model->_primary_key = 'clock_id';
            $this->attendance_model->save($data, $clock_id);
            $adata['status'] = $status;

            $this->attendance_model->_table_name = 'tbl_clock_history';
            $this->attendance_model->_primary_key = 'clock_history_id';
            $this->attendance_model->save($adata, $history_id);

            $type = "success";
            $message = lang('time_change_request') . ' ' . lang('accepted');
            $description = 'time_change_request_accepted';
        } else {
            $data['status'] = $status;
            $this->attendance_model->_table_name = 'tbl_clock_history';
            $this->attendance_model->_primary_key = 'clock_history_id';
            $this->attendance_model->save($data, $history_id);

            $type = "error";
            $message = lang('time_change_request') . ' ' . lang('rejected');
            $description = 'time_change_request_rejected';
        }
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'attendance',
            'module_field_id' => $history_id,
            'activity' => $description,
            'icon' => 'fa-clock-o',
            'link' => 'admin/attendance/view_changerequest/' . $history_id,
            'value1' => lang('by') . ' ' . $this->session->userdata('name'),
        );
        // Update into tbl_project
        $this->attendance_model->_table_name = "tbl_activities"; //table name
        $this->attendance_model->_primary_key = "activities_id";
        $this->attendance_model->save($activities);
        $notifyUser = array($clock_history_info->user_id);
        if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
                add_notification(array(
                    'to_user_id' => $v_user,
                    'description' => $description,
                    'icon' => 'clock-o',
                    'link' => 'admin/attendance/view_changerequest/' . $history_id,
                    'value' => lang('by') . ' ' . $this->session->userdata('name'),
                ));
            }
        }
        if (!empty($notifyUser)) {
            show_notification($notifyUser);
        }

        set_message($type, $message);
        redirect('admin/attendance/timechange_request'); //redirect page
    }

    public function attendance_report($departments_id = null, $type = null, $date = null, $pdf = null, $end_date = null)
    {

        $data['title'] = lang('attendance_report');
        $data['all_department'] = $this->attendance_model->select_data('tbl_departments', 'departments_id', 'deptname');
        if (empty($departments_id)) {
            $departments_id = $this->input->post('departments_id', TRUE);
        }
        if (empty($type)) {
            $type = $this->input->post('search_type', TRUE);
        }

        if (!empty($type)) {
            if ($type == 'period') {
                $start_date = $date;
                $date = array();
                if (!empty($end_date)) {
                    $date['start_date'] = $start_date;
                    $date['end_date'] = $pdf;
                    $pdf = true;
                } else {
                    $date['start_date'] = $this->input->post('start_date', TRUE);
                    $date['end_date'] = $this->input->post('end_date', TRUE);
                }
            } else if (empty($date)) {
                $date = $this->input->post('date', TRUE);
            }


            if (config_item('attendance_report') == 1) {
                $this->get_report($departments_id, $type, $date, $pdf, $end_date);
            } elseif (config_item('attendance_report') == 2) {
                $this->get_report_2($departments_id, $type, $date, $pdf, $end_date);
            } elseif (config_item('attendance_report') == 3) {
                $this->get_report_3($departments_id, $type, $date, $pdf, $end_date);
            } elseif (config_item('attendance_report') == 4) {
                $this->get_report_4($departments_id, $type, $date, $pdf, $end_date);
            } elseif (config_item('attendance_report') == 5) {
                $this->get_report_5($departments_id, $type, $date, $pdf, $end_date);
            } elseif (config_item('attendance_report') == 6) {
                $this->get_report_6($departments_id, $type, $date, $pdf, $end_date);
            } else {
                $this->get_report_7($departments_id, $type, $date, $pdf, $end_date);
            }
        } else {
            $data['subview'] = $this->load->view('admin/attendance/attendance_report', $data, true);
            $this->load->view('admin/_layout_main', $data);
        }
    }

    public function get_report()
    {
        $departments_id = $this->input->post('departments_id', TRUE);
        $date = $this->input->post('date', TRUE);

        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if (!empty($departments_id)) {
            $data['employee_info'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
        } else {
            $data['employee_info'] = get_staff_details();
        }

        $holidays = $this->common_model->get_holidays(); //tbl working Days Holiday

        if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
        } else {
            $yymm = $year . '-' . $month;
        }

        $public_holiday = $this->common_model->get_public_holidays($yymm);

        //tbl a_calendar Days Holiday
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
        }

        foreach ($data['employee_info'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {

                if ($i >= 1 && $i <= 9) {
                    $sdate = $yymm . '-' . '0' . $i;
                } else {
                    $sdate = $yymm . '-' . $i;
                }
                $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));

                $data['week_info'][date('W', strtotime($sdate))][$sdate] = $sdate;

                // get leave info
                if (!empty($holidays)) {
                    foreach ($holidays as $v_holiday) {
                        if ($v_holiday->day == $day_name) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($p_hday)) {
                    foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($flag)) {
                    $data['attendace_info'][date('W', strtotime($sdate))][$sdate][$v_employee->user_id] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                } else {
                    $data['attendace_info'][date('W', strtotime($sdate))][$sdate][$v_employee->user_id] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                }
                $key++;
                $flag = '';
            }
        }
        $data['title'] = lang('attendance_report');
        $data['all_department'] = $this->attendance_model->select_data('tbl_departments', 'departments_id', 'deptname');
        $data['departments_id'] = $this->input->post('departments_id', TRUE);
        $data['date'] = $this->input->post('date', TRUE);
        $where = array('departments_id' => $departments_id);
        $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
        $data['month'] = date('F-Y', strtotime($yymm));


        $data['subview'] = $this->load->view('admin/attendance/attendance_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function get_report_7($departments_id, $type, $date, $pdf = null)
    {
        if (is_array($date)) {
            $start_date = $date['start_date'];
            $end_date = $date['end_date'];
            // get month and year from start date and end date and check if they are equal or not
            $start_month = date('m', strtotime($start_date));
            $start_year = date('Y', strtotime($start_date));
            $end_month = date('m', strtotime($end_date));
            $end_year = date('Y', strtotime($end_date));
            if ($start_month == $end_month && $start_year == $end_year) {
                $yymm = $start_year . '-' . $start_month;
            } else {
                $yymm = array();
                $yymm['start_month'] = $start_year . '-' . $start_month;
                $yymm['end_month'] = $end_year . '-' . $end_month;
            }
            $all_dates = $this->attendance_model->GetDays($start_date, $end_date);
            $data['dates'] = $all_dates;
            $data['dateSl'] = $all_dates;
            $data['total_days'] = count(array($all_dates));
        } else {
            $month = date('n', strtotime($date));
            $year = date('Y', strtotime($date));
            $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            if ($month >= 1 && $month <= 9) {
                $yymm = $year . '-' . '0' . $month;
            } else {
                $yymm = $year . '-' . $month;
            }
            $all_dates = array();
            for ($i = 1; $i <= $num; $i++) {
                $data['dateSl'][] = $i;
                $data['dates'][] = $yymm . '-' . $i;
                $all_dates[] = $yymm . '-' . $i;
            }
            $data['total_days'] = $num;
        }
        if (!empty($departments_id)) {
            $data['employee'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
        } else {
            $data['employee'] = get_staff_details();
        }
        $total_holiday = 0;
        $holidays = $this->common_model->get_holidays(); //tbl working Days Holiday
        // total keys of $holidays array using array_keys
        if (!empty($holidays)) {
            $total_holiday += count(array($holidays));
        }
        if (is_array($yymm)) {
            $public_holiday_start_month = $this->common_model->get_public_holidays($yymm['start_month']); //tbl a_calendar Days Holiday
            $public_holiday_end_month = $this->common_model->get_public_holidays($yymm['end_month']); //tbl a_calendar Days Holiday
            // merge two array
            $public_holiday = array_merge($public_holiday_start_month, $public_holiday_end_month);
        } else {
            $public_holiday = $this->common_model->get_public_holidays($yymm);
        }


        //tbl a_calendar Days Holiday
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
        }

        if (!empty($p_hday)) {
            $total_holiday += count(array($p_hday));
        }


        foreach ($data['employee'] as $sl => $v_employee) {
            foreach ($all_dates as $key => $sdate) {
                $day_name = date('l', strtotime($sdate));
                if (!empty($holidays)) {
                    foreach ($holidays as $v_holiday) {
                        if ($v_holiday->day == $day_name) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($p_hday)) {
                    foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($flag)) {
                    $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate, $flag);
                } else {
                    $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate);
                }
                $flag = '';
            }
        }

        $data['title'] = lang('attendance_report');
        $data['all_department'] = $this->attendance_model->select_data('tbl_departments', 'departments_id', 'deptname');
        $data['departments_id'] = $this->input->post('departments_id', TRUE);
        $data['date'] = $this->input->post('date', TRUE);
        $where = array('departments_id' => $departments_id);
        $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
        // total holiday
        $data['total_holiday'] = $total_holiday;

        if (!empty($yymm['start_month'])) {
            $data['month'] = date('F-Y', strtotime($yymm['start_month']));
        } else {
            $data['month'] = date('F-Y', strtotime($yymm));
        }
        $data['search_type'] = $type;
        $data['start_date'] = (!empty($start_date) ? $start_date : '');
        $data['end_date'] = (!empty($end_date) ? $end_date : '');
        if (!empty($pdf)) {
            $viewfile = $this->load->view('admin/attendance/attendance_report_7_pdf', $data, TRUE);
            $this->load->helper('dompdf');
            pdf_create($viewfile, lang('attendance_report') . ' ' . $data['month']);
        } else {
            $data['subview'] = $this->load->view('admin/attendance/attendance_report_7', $data, TRUE);
            $this->load->view('admin/_layout_main', $data);
        }


    }

    public function get_report_2()
    {
        $departments_id = $this->input->post('departments_id', TRUE);
        $date = $this->input->post('date', TRUE);
        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if (!empty($departments_id)) {
            $data['employee'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
        } else {
            $data['employee'] = get_staff_details();
        }
        $day = date('d', strtotime($date));
        $holidays = $this->common_model->get_holidays(); //tbl working Days Holiday

        if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
        } else {
            $yymm = $year . '-' . $month;
        }
        for ($i = 1; $i <= $num; $i++) {
            $data['dateSl'][] = $i;
        }

        $public_holiday = $this->common_model->get_public_holidays($yymm);


        //tbl a_calendar Days Holiday
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
        }
        foreach ($data['employee'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {

                if ($i >= 1 && $i <= 9) {

                    $sdate = $yymm . '-' . '0' . $i;
                } else {
                    $sdate = $yymm . '-' . $i;
                }
                $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));


                if (!empty($holidays)) {
                    foreach ($holidays as $v_holiday) {

                        if ($v_holiday->day == $day_name) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($p_hday)) {
                    foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($flag)) {
                    $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate, $flag);
                } else {
                    $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate);
                }

                $key++;
                $flag = '';
            }
        }

        $data['title'] = lang('attendance_report');
        $data['all_department'] = $this->attendance_model->select_data('tbl_departments', 'departments_id', 'deptname');
        $data['departments_id'] = $this->input->post('departments_id', TRUE);
        $data['date'] = $this->input->post('date', TRUE);
        $where = array('departments_id' => $departments_id);
        $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');

        $data['month'] = date('F-Y', strtotime($yymm));
        $data['subview'] = $this->load->view('admin/attendance/attendance_report_2', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function get_report_3()
    {
        $departments_id = $this->input->post('departments_id', TRUE);
        $date = $this->input->post('date', TRUE);

        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);


        if (!empty($departments_id)) {
            $data['employee'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
        } else {
            $data['employee'] = get_staff_details();
        }

        $holidays = $this->common_model->get_holidays(); //tbl working Days Holiday

        if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
        } else {
            $yymm = $year . '-' . $month;
        }

        $public_holiday = $this->common_model->get_public_holidays($yymm);

        //tbl a_calendar Days Holiday
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
        }

        foreach ($data['employee'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {

                if ($i >= 1 && $i <= 9) {
                    $sdate = $yymm . '-' . '0' . $i;
                } else {
                    $sdate = $yymm . '-' . $i;
                }
                $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));

                $data['week_info'][date('W', strtotime($sdate))][$sdate] = $sdate;
                // get leave info

                if (!empty($holidays)) {
                    foreach ($holidays as $v_holiday) {
                        if ($v_holiday->day == $day_name) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($p_hday)) {
                    foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($flag)) {
                    $data['attendace_info'][$sl][date('W', strtotime($sdate))][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                } else {
                    $data['attendace_info'][$sl][date('W', strtotime($sdate))][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                }
                $key++;
                $flag = '';
            }
        }

        $data['title'] = lang('attendance_report');
        $data['all_department'] = $this->attendance_model->select_data('tbl_departments', 'departments_id', 'deptname');
        $data['departments_id'] = $this->input->post('departments_id', TRUE);
        $data['date'] = $this->input->post('date', TRUE);
        $where = array('departments_id' => $departments_id);
        $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');

        $data['month'] = date('F Y', strtotime($yymm));
        $data['subview'] = $this->load->view('admin/attendance/attendance_report_3', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function attendance_pdf($type, $departments_id = null, $date)
    {
        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if (!empty($departments_id)) {
            $data['employee'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
        } else {
            $data['employee'] = get_staff_details();
        }
        $holidays = $this->common_model->get_holidays(); //tbl working Days Holiday

        if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
        } else {
            $yymm = $year . '-' . $month;
        }

        $public_holiday = $this->common_model->get_public_holidays($yymm);

        //tbl a_calendar Days Holiday
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
        }
        if ($type == 1) {
            foreach ($data['employee'] as $sl => $v_employee) {
                $key = 1;
                $x = 0;
                for ($i = 1; $i <= $num; $i++) {

                    if ($i >= 1 && $i <= 9) {
                        $sdate = $yymm . '-' . '0' . $i;
                    } else {
                        $sdate = $yymm . '-' . $i;
                    }
                    $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));

                    $data['week_info'][date('W', strtotime($sdate))][$sdate] = $sdate;

                    // get leave info
                    if (!empty($holidays)) {
                        foreach ($holidays as $v_holiday) {
                            if ($v_holiday->day == $day_name) {
                                $flag = 'H';
                            }
                        }
                    }
                    if (!empty($p_hday)) {
                        foreach ($p_hday as $v_hday) {
                            if ($v_hday == $sdate) {
                                $flag = 'H';
                            }
                        }
                    }
                    if (!empty($flag)) {
                        $data['attendace_info'][date('W', strtotime($sdate))][$sdate][$v_employee->user_id] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                    } else {
                        $data['attendace_info'][date('W', strtotime($sdate))][$sdate][$v_employee->user_id] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                    }
                    $key++;
                    $flag = '';
                }
            }
            $data['title'] = lang('attendance_report');
            $where = array('departments_id' => $departments_id);
            $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');

            $data['month'] = date('F-Y', strtotime($yymm));
            $subview = 'attendance_report_pdf';
        } elseif ($type == 2) {

            foreach ($data['employee'] as $sl => $v_employee) {
                $key = 1;
                $x = 0;
                for ($i = 1; $i <= $num; $i++) {

                    if ($i >= 1 && $i <= 9) {

                        $sdate = $yymm . '-' . '0' . $i;
                    } else {
                        $sdate = $yymm . '-' . $i;
                    }
                    $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));


                    if (!empty($holidays)) {
                        foreach ($holidays as $v_holiday) {

                            if ($v_holiday->day == $day_name) {
                                $flag = 'H';
                            }
                        }
                    }
                    if (!empty($p_hday)) {
                        foreach ($p_hday as $v_hday) {
                            if ($v_hday == $sdate) {
                                $flag = 'H';
                            }
                        }
                    }
                    if (!empty($flag)) {
                        $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate, $flag);
                    } else {
                        $data['attendance'][$sl][] = $this->attendance_model->attendance_report_2_by_empid($v_employee->user_id, $sdate);
                    }

                    $key++;
                    $flag = '';
                }
            }

            $data['title'] = lang('attendance_report');
            $where = array('departments_id' => $departments_id);
            $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
            $data['month'] = date('F-Y', strtotime($yymm));
            $subview = 'attendance_report_2_pdf';
        } elseif ($type == 6) {
            foreach ($data['employee'] as $sl => $v_employee) {
                $key = 1;
                $x = 0;
                for ($i = 1; $i <= $num; $i++) {
                    if ($i >= 1 && $i <= 9) {
                        $sdate = $yymm . '-' . '0' . $i;
                    } else {
                        $sdate = $yymm . '-' . $i;
                    }
                    $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));


                    // get leave info

                    if (!empty($holidays)) {
                        foreach ($holidays as $v_holiday) {
                            if ($v_holiday->day == $day_name) {
                                $flag = 'H';
                            }
                        }
                    }
                    if (!empty($p_hday)) {
                        foreach ($p_hday as $v_hday) {
                            if ($v_hday == $sdate) {
                                $flag = 'H';
                            }
                        }
                    }
                    $data['dateName'][$sdate] = $sdate;
                    if (!empty($flag)) {
                        $data['attendace_info'][$sdate][$v_employee->fullname . '(' . $v_employee->employment_id . ')'] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                    } else {
                        $data['attendace_info'][$sdate][$v_employee->fullname . '(' . $v_employee->employment_id . ')'] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                    }
                    $key++;
                    $flag = '';
                }
            }
            $data['title'] = lang('attendance_report');
            $where = array('departments_id' => $departments_id);
            $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');

            $data['month'] = date('F Y', strtotime($yymm));
            $subview = 'attendance_report_6_pdf';
        } else {
            foreach ($data['employee'] as $sl => $v_employee) {
                $key = 1;
                $x = 0;
                for ($i = 1; $i <= $num; $i++) {

                    if ($i >= 1 && $i <= 9) {
                        $sdate = $yymm . '-' . '0' . $i;
                    } else {
                        $sdate = $yymm . '-' . $i;
                    }
                    $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));

                    $data['week_info'][date('W', strtotime($sdate))][$sdate] = $sdate;
                    // get leave info

                    if (!empty($holidays)) {
                        foreach ($holidays as $v_holiday) {
                            if ($v_holiday->day == $day_name) {
                                $flag = 'H';
                            }
                        }
                    }
                    if (!empty($p_hday)) {
                        foreach ($p_hday as $v_hday) {
                            if ($v_hday == $sdate) {
                                $flag = 'H';
                            }
                        }
                    }
                    if (!empty($flag)) {
                        $data['attendace_info'][$sl][date('W', strtotime($sdate))][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                    } else {
                        $data['attendace_info'][$sl][date('W', strtotime($sdate))][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                    }
                    $key++;
                    $flag = '';
                }
            }

            $data['title'] = lang('attendance_report');
            $where = array('departments_id' => $departments_id);
            $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');

            $data['month'] = date('F Y', strtotime($yymm));
            $subview = 'attendance_report_3_pdf';
        }
        $data['title'] = lang('attendance_report'); //Page title
        $this->load->helper('dompdf');

        $viewfile = $this->load->view('admin/attendance/' . $subview, $data, TRUE);
        pdf_create($viewfile, slug_it($data['month'] . '-' . $data['dept_name']->deptname));
    }

    public function get_report_4()
    {
        $departments_id = $this->input->post('departments_id', TRUE);

        $date = $this->input->post('date', TRUE);


        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);


        if (!empty($departments_id)) {
            $data['users'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
        } else {
            $data['users'] = get_staff_details();
        }
        $holidays = $this->common_model->get_holidays(); //tbl working Days Holiday

        if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
        } else {
            $yymm = $year . '-' . $month;
        }

        $public_holiday = $this->common_model->get_public_holidays($yymm);

        //tbl a_calendar Days Holiday
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                $p_hday = $this->attendance_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
        }

        foreach ($data['users'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {

                if ($i >= 1 && $i <= 9) {
                    $sdate = $yymm . '-' . '0' . $i;
                } else {
                    $sdate = $yymm . '-' . $i;
                }
                $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));

                // get leave info

                if (!empty($holidays)) {
                    foreach ($holidays as $v_holiday) {
                        if ($v_holiday->day == $day_name) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($p_hday)) {
                    foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                            $flag = 'H';
                        }
                    }
                }
                $data['dateName'][$sdate] = $sdate;
                if (!empty($flag)) {
                    $data['attendace_info'][$sl][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                } else {
                    $data['attendace_info'][$sl][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                }
                $key++;
                $flag = '';
            }
        }


        $data['title'] = lang('attendance_report');
        $data['all_department'] = $this->attendance_model->select_data('tbl_departments', 'departments_id', 'deptname');
        $data['departments_id'] = $this->input->post('departments_id', TRUE);
        $data['date'] = $this->input->post('date', TRUE);
        $where = array('departments_id' => $departments_id);
        $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
        $data['month'] = date('F Y', strtotime($yymm));
        $data['subview'] = $this->load->view('admin/attendance/attendance_report_4', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function get_report_5()
    {
        $departments_id = $this->input->post('departments_id', TRUE);
        $date = $this->input->post('date', TRUE);

        $data['date'] = $date;
        $payment_month = $date;

        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));
        $day = date('d', strtotime($date));
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);


        if (!empty($departments_id)) {
            $data['users'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
        } else {
            $data['users'] = get_staff_details();
        }
        $holidays = $this->common_model->get_holidays(); //tbl working Days Holiday

        if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
        } else {
            $yymm = $year . '-' . $month;
        }

        $public_holiday = $this->common_model->get_public_holidays($yymm);

        //tbl a_calendar Days Holiday
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                $p_hday = $this->common_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
        }
        foreach ($data['users'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {
                if ($i >= 1 && $i <= 9) {
                    $sdate = $yymm . '-' . '0' . $i;
                } else {
                    $sdate = $yymm . '-' . $i;
                }
                $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));


                // get leave info

                if (!empty($holidays)) {
                    foreach ($holidays as $v_holiday) {
                        if ($v_holiday->day == $day_name) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($p_hday)) {
                    foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                            $flag = 'H';
                        }
                    }
                }
                $data['dateName'][$sdate] = $sdate;
                if (!empty($flag)) {
                    $data['attendace_info'][$sl][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                } else {
                    $data['attendace_info'][$sl][$sdate] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                }
                $key++;
                $flag = '';
                // $data['overtime_info'][$sl][$sdate] = $this->attendance_model->get_overtime_info_by_id($v_employee->user_id, $sdate);
                // $data['overtime_info'][$sl][$sdate] = $this->attendance_model->get_overtime_info_by_id($v_employee->user_id, $payment_month);
            }
        }

        $data['title'] = lang('attendance_report');
        $data['all_department'] = $this->attendance_model->select_data('tbl_departments', 'departments_id', 'deptname');
        $data['departments_id'] = $this->input->post('departments_id', TRUE);
        $data['date'] = $this->input->post('date', TRUE);
        $where = array('departments_id' => $departments_id);
        $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
        $data['month'] = date('F Y', strtotime($yymm));
        $data['subview'] = $this->load->view('admin/attendance/attendance_report_5', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function get_report_6()
    {
        $departments_id = $this->input->post('departments_id', TRUE);
        $date = $this->input->post('date', TRUE);

        $data['date'] = $date;
        $payment_month = $date;

        $month = date('n', strtotime($date));
        $year = date('Y', strtotime($date));
        $day = date('d', strtotime($date));
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (!empty($departments_id)) {
            $data['users'] = $this->attendance_model->get_employee_id_by_dept_id($departments_id);
        } else {
            $data['users'] = get_staff_details();
        }


        $holidays = $this->common_model->get_holidays(); //tbl working Days Holiday

        if ($month >= 1 && $month <= 9) {
            $yymm = $year . '-' . '0' . $month;
        } else {
            $yymm = $year . '-' . $month;
        }

        $public_holiday = $this->common_model->get_public_holidays($yymm);

        //tbl a_calendar Days Holiday
        if (!empty($public_holiday)) {
            foreach ($public_holiday as $p_holiday) {
                $p_hday = $this->common_model->GetDays($p_holiday->start_date, $p_holiday->end_date);
            }
        }
        foreach ($data['users'] as $sl => $v_employee) {
            $key = 1;
            $x = 0;
            for ($i = 1; $i <= $num; $i++) {
                if ($i >= 1 && $i <= 9) {
                    $sdate = $yymm . '-' . '0' . $i;
                } else {
                    $sdate = $yymm . '-' . $i;
                }
                $day_name = date('l', strtotime("+$x days", strtotime($year . '-' . $month . '-' . $key)));


                // get leave info

                if (!empty($holidays)) {
                    foreach ($holidays as $v_holiday) {
                        if ($v_holiday->day == $day_name) {
                            $flag = 'H';
                        }
                    }
                }
                if (!empty($p_hday)) {
                    foreach ($p_hday as $v_hday) {
                        if ($v_hday == $sdate) {
                            $flag = 'H';
                        }
                    }
                }
                $data['dateName'][$sdate] = $sdate;
                if (!empty($flag)) {
                    $data['attendace_info'][$sdate][$v_employee->fullname . '(' . $v_employee->employment_id . ')'] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate, $flag);
                } else {
                    $data['attendace_info'][$sdate][$v_employee->fullname . '(' . $v_employee->employment_id . ')'] = $this->attendance_model->attendance_report_by_empid($v_employee->user_id, $sdate);
                }
                $key++;
                $flag = '';
            }
        }

        $data['title'] = lang('attendance_report');
        $data['all_department'] = $this->attendance_model->select_data('tbl_departments', 'departments_id', 'deptname');
        $data['departments_id'] = $this->input->post('departments_id', TRUE);
        $data['date'] = $this->input->post('date', TRUE);
        $where = array('departments_id' => $departments_id);
        $data['dept_name'] = $this->attendance_model->check_by($where, 'tbl_departments');
        $data['month'] = date('F Y', strtotime($yymm));
        $data['subview'] = $this->load->view('admin/attendance/attendance_report_6', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function workplace()
    {
        $data['title'] = lang('workplace');
        $data['active'] = 1;
        $data['subview'] = $this->load->view('admin/attendance/workplace', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function new_workplace($id = null)
    {

        $data['title'] = lang('new_workplace');
        if ($id) {
            $data['active'] = 2;
            $can_edit = $this->attendance_model->can_action('tbl_workplace', 'edit', array('workplace_id' => $id));
            $edited = can_action('36', 'edited');
            if (!empty($can_edit) && !empty($edited)) {
                $data['workplace_info'] = $this->attendance_model->check_by(array('workplace_id' => $id), 'tbl_workplace');
            }
        }
        $data['active'] = 2;
        $data['subview'] = $this->load->view('admin/attendance/new_workplace', $data, true);
        $this->load->view('admin/_layout_main', $data);
    }

    public function save_workplace($id = null)
    {
        $created = can_action('36', 'created');
        $edited = can_action('36', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $this->attendance_model->_table_name = 'tbl_workplace';
            $this->attendance_model->_primary_key = 'workplace_id';
            $data = $this->attendance_model->array_from_post(array('location_name', 'workplace_details', 'workplace_location', 'latitude', 'longitude', 'allowed_radius'));
            // update root category
            $where = array('location_name' => $data['location_name']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $workplace_id = array('workplace_id !=' => $id);
            } else { // if id is not exist then set id as null
                $workplace_id = null;
            }

            $user_id = $this->input->post('user_id', true);
            if (!empty($user_id)) {
                foreach ($user_id as $value) {
                    $check_workplace = get_row('tbl_workplace', array('user_id' => $value));
                    $sdata = $data;
                    $sdata['user_id'] = $value;
                    if (!empty($check_workplace)) {
                        $return_id = $this->attendance_model->save($sdata, $check_workplace->workplace_id);
                    } else {
                        $return_id = $this->attendance_model->save($sdata);
                    }
                }
            }

            if (!empty($id)) {
                $id = $id;
                $action = 'activity_update_workplace';
                $msg = lang('update_workplace');
            } else {
                $id = $return_id;
                $action = 'activity_save_workplace';
                $msg = lang('save_workplace');
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'attendance',
                'module_field_id' => $id,
                'activity' => $action,
                'icon' => 'fa-circle-o',
                'value1' => $data['location_name']
            );
            $this->attendance_model->_table_name = 'tbl_activities';
            $this->attendance_model->_primary_key = 'activities_id';
            $this->attendance_model->save($activity);
            // messages for user
            $type = "success";
            $message = $msg;
            set_message($type, $message);
        }
        redirect('admin/attendance/workplace');
    }

    public function attendancetList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_workplace';
            $this->datatables->join_table = array('tbl_account_details');
            $this->datatables->join_where = array('tbl_workplace.user_id=tbl_account_details.user_id');
            $this->datatables->column_order = array('location_name', 'workplace_details', 'workplace_location', 'latitude', 'longitude', 'allowed_radius');
            $this->datatables->column_search = array('location_name', 'workplace_details', 'workplace_location', 'latitude', 'longitude', 'allowed_radius');
            $this->datatables->order = array('workplace_id' => 'desc');

            $fetch_data = make_datatables();

            $data = array();
            $edited = can_action_by_label('workplace', 'edited');
            $deleted = can_action_by_label('workplace', 'deleted');
            foreach ($fetch_data as $key => $row) {
                $action = null;
                $sub_array = array();
                $sub_array[] = $row->fullname;
                $sub_array[] = $row->location_name;
                $sub_array[] = $row->workplace_details;
                $sub_array[] = $row->workplace_location;
                $sub_array[] = $row->allowed_radius;

                if (!empty($edited)) {
                    $action .= btn_edit('admin/attendance/new_workplace/' . $row->workplace_id) . ' ';
                }
                if (!empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/attendance/delete_workplace/$row->workplace_id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $key));
                }

                //            echo $action;
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_workplace($id)
    {
        $deleted = $deleted = can_action('36', 'deleted');
        if (!empty($deleted)) {
            $workplace_info = $this->attendance_model->check_by(array('workplace_id' => $id), 'tbl_workplace');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'attendance',
                'module_field_id' => $id,
                'activity' => ('activity_deleted_attendance'),
                'value1' => $workplace_info->location_name
            );
            $this->attendance_model->_table_name = 'tbl_activities';
            $this->attendance_model->_primary_key = 'activities_id';
            $this->attendance_model->save($activity);

            $this->attendance_model->_table_name = 'tbl_workplace';
            $this->attendance_model->_primary_key = 'workplace_id';
            $this->attendance_model->delete($id);

            $type = "success";
            $msg = lang('delete_successfully');
            echo json_encode(array("status" => $type, 'message' => $msg));
            exit();
        }
    }
}
