<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('admin_model');
    }

    public function fetch_address_info_gmaps()
    {
        include_once(APPPATH . 'third_party/JD_Geocoder_Request.php');
        $data = $this->input->post();
        $address = '';
        $address .= $data['address'];
        if (!empty($data['city'])) {
            $address .= ', ' . $data['city'];
        }
        if (!empty($data['country'])) {
            $address .= ', ' . $data['country'];
        }
        $georequest = new JD_Geocoder_Request();
        $georequest->forwardSearch($address);
        echo json_encode($georequest);
        exit();
    }

    public function get_project_by_client_id($client_id)
    {
        $HTML = null;
        $client_project_info = $this->db->where(array('client_id' => $client_id))->get('tbl_project')->result();
        if (!empty($client_project_info)) {
            $HTML .= "<option value='" . 0 . "'>" . lang('none') . "</option>";
            foreach ($client_project_info as $v_client_project) {
                $HTML .= "<option value='" . $v_client_project->project_id . "'>" . $v_client_project->project_name . "</option>";
            }
        }
        echo $HTML;
        exit();
    }

    public function get_milestone_by_project_id($project_id)
    {
        $milestone_info = $this->db->where(array('project_id' => $project_id))->get('tbl_milestones')->result();
        $HTML = null;
        if (!empty($milestone_info)) {
            $HTML .= "<option value='" . 0 . "'>" . lang('none') . "</option>";
            foreach ($milestone_info as $v_milestone) {
                $HTML .= "<option value='" . $v_milestone->milestones_id . "'>" . $v_milestone->milestone_name . "</option>";
            }
        }
        echo $HTML;
        exit();
    }

    public function get_related_moduleName_by_value($val, $proposal = null)
    {
        if ($val == 'project') {
            $all_project_info = $this->admin_model->get_permission('tbl_project');
            $HTML = null;
            if ($all_project_info) {
                $HTML .= '<div class="col-sm-5"><select onchange="get_milestone_by_id(this.value)" name="' . $val . '_id" id="related_to"  class="form-control selectpicker m0 " data-live-search="true" >';
                foreach ($all_project_info as $v_project) {
                    $HTML .= "<option value='" . $v_project->project_id . "'>" . $v_project->project_name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'opportunities') {
            $HTML = null;
            $all_opp_info = $this->admin_model->get_permission('tbl_opportunities');
            if ($all_opp_info) {

                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_id" id="related_to"  class="form-control selectpicker m0 " data-live-search="true">';
                foreach ($all_opp_info as $v_opp) {
                    $HTML .= "<option value='" . $v_opp->opportunities_id . "'>" . $v_opp->opportunity_name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'leads') {
            $all_leads_info = $this->admin_model->get_permission('tbl_leads');
            $HTML = null;
            if ($all_leads_info) {

                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_id" id="related_to"  class="form-control selectpicker m0 " data-live-search="true">';
                foreach ($all_leads_info as $v_leads) {
                    $HTML .= "<option value='" . $v_leads->leads_id . "'>" . $v_leads->lead_name . "</option>";
                }
                $HTML .= '</select></div>';
                if (!empty($proposal)) {
                    $HTML .= '<div class="form-group ml0 mr0 pt-lg" style="margin-top: 35px"><label class="col-lg-3 control-label">' . lang("currency") . '</label><div class="col-lg-7"><select name="currency" class="form-control selectpicker m0 " data-live-search="true">';
                    $all_currency = $this->db->get('tbl_currencies')->result();
                    foreach ($all_currency as $v_currency) {
                        $HTML .= "<option " . (config_item('default_currency') == $v_currency->code ? ' selected="selected"' : '') . " value='" . $v_currency->code . "'>" . $v_currency->name . "</option>";
                    }
                    $HTML .= '</select></div></div>';
                }
            }
            echo $HTML;
            exit();
        } elseif ($val == 'client') {
            $all_client_info = $this->db->get('tbl_client')->result();
            $HTML = null;
            if ($all_client_info) {
                $HTML .= '<div class="col-sm-7"><select name="' . $val . '_id" id="related_to"  class="form-control selectpicker m0 " data-live-search="true" required>';
                $HTML .= "<option value=''>" . lang('none') . "</option>";
                foreach ($all_client_info as $v_client) {
                    $HTML .= "<option value='" . $v_client->client_id . "'>" . $v_client->name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'supplier') {
            $all_supplier = $this->db->get('tbl_suppliers')->result();
            $HTML = null;
            if ($all_supplier) {
                $HTML .= '<div class="col-sm-7"><select  name="' . $val . '_id" id="related_to"  data-live-search="true" class="form-control selectpicker m0 ">';
                $HTML .= "<option value=''>" . lang('none') . "</option>";
                foreach ($all_supplier as $v_supplier) {
                    $HTML .= "<option value='" . $v_supplier->supplier_id . "'>" . $v_supplier->name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'bug') {
            $all_bugs_info = $this->admin_model->get_permission('tbl_bug');
            $HTML = null;
            if ($all_bugs_info) {

                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_id" id="related_to"  class="form-control selectpicker m0 " data-live-search="true">';
                foreach ($all_bugs_info as $v_bugs) {
                    $HTML .= "<option value='" . $v_bugs->bug_id . "'>" . $v_bugs->bug_title . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'goal') {
            $all_goal_info = $this->admin_model->get_permission('tbl_goal_tracking');
            $HTML = null;
            if ($all_goal_info) {

                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_tracking_id" id="related_to"  class="form-control selectpicker m0 " data-live-search="true">';
                foreach ($all_goal_info as $v_goal) {
                    $HTML .= "<option value='" . $v_goal->goal_tracking_id . "'>" . $v_goal->subject . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'sub_task') {
            $all_task_info = $this->admin_model->get_permission('tbl_task');
            $HTML = null;
            if ($all_task_info) {

                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_id" id="related_to"  class="form-control selectpicker m0 " data-live-search="true">';
                foreach ($all_task_info as $v_task) {
                    $HTML .= "<option value='" . $v_task->task_id . "'>" . $v_task->task_name . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        } elseif ($val == 'expenses') {
            $all_expenses = $this->admin_model->get_permission('tbl_transactions');
            $HTML = null;
            if ($all_expenses) {
                $val = 'transactions_id';
                $HTML .= '<div class="col-sm-5"><select name="' . $val . '_id" id="related_to"  class="form-control selectpicker m0 " data-live-search="true">';
                foreach ($all_expenses as $expenses) {
                    $HTML .= "<option value='" . $expenses->transactions_id . "'>" . $expenses->name . (!empty($expenses->reference) ? '#' . $expenses->reference : '') . "</option>";
                }
                $HTML .= '</select></div>';
            }
            echo $HTML;
            exit();
        }
    }

    public function check_current_password()
    {
        $old_password = $this->input->post('name', true);
        if (!empty($old_password)) {
            if (!empty($old_password)) {
                $password = $this->hash($old_password);
            }
            $check_dupliaction_id = $this->admin_model->check_by(array('user_id' => my_id(), 'password' => $password), 'tbl_users');
            if (empty($check_dupliaction_id)) {
                $result['error'] = lang("password_does_not_match");
            } else {
                $result['success'] = 1;
                $encrypt_password = $this->input->post('encrypt_password', true);
                if (!empty($encrypt_password)) {
                    $result['password'] = decrypt($encrypt_password);
                }
            }
            echo json_encode($result);
            exit();
        }
    }

    public function check_existing_user_name($user_id = null)
    {
        $username = $this->input->post('name', true);
        if (!empty($username)) {
            $check_user_name = $this->admin_model->check_user_name($username, $user_id);
            if (!empty($check_user_name)) {
                $result['error'] = lang("name_already_exist");
            } else {
                $result['success'] = 1;
            }
            echo json_encode($result);
            exit();
        }
    }


    public function check_duplicate_emp_id($user_id = null)
    {
        $employment_id = $this->input->post('name', true);
        if (!empty($employment_id)) {
            $where = array('employment_id' => $employment_id);
            if (!empty($user_id)) {
                $where['user_id !='] = $user_id;
            }
            $check_dupliaction_id = $this->admin_model->check_by($where, 'tbl_account_details');
            if (!empty($check_dupliaction_id)) {
                $result['error'] = lang("employee_id_exist");
            } else {
                $result['success'] = 1;
            }
            echo json_encode($result);
            exit();
        }
    }

    public function check_email_addrees($user_id = null)
    {
        $email_address = $this->input->post('name', true);
        if (!empty($email_address)) {
            $where = array('email' => $email_address);
            if (!empty($user_id)) {
                $where['user_id !='] = $user_id;
            }
            $check_email_address = $this->admin_model->check_by($where, 'tbl_users');
            if (!empty($check_email_address)) {
                $result['error'] = lang("this_email_already_exist");
            } else {
                $result['success'] = 1;
            }
            echo json_encode($result);
            exit();
        }
    }


    public function get_item_name_by_id($stock_sub_category_id)
    {
        $HTML = NULL;
        $this->admin_model->_table_name = 'tbl_stock';
        $this->admin_model->_order_by = 'stock_sub_category_id';
        $stock_info = $this->admin_model->get_by(array('stock_sub_category_id' => $stock_sub_category_id, 'total_stock >=' => '1'), FALSE);
        if (!empty($stock_info)) {
            foreach ($stock_info as $v_stock_info) {
                $HTML .= "<option value='" . $v_stock_info->stock_id . "'>" . $v_stock_info->item_name . "</option>";
            }
        }
        echo $HTML;
        exit();
    }


    public function check_available_leave($user_id, $start_date = NULL, $end_date = NULL, $leave_category_id = NULL, $leave_application_id = null)
    {

        $office_hours = config_item('office_hours');
        $result = null;
        if (!empty($leave_category_id) && !empty($start_date)) {
            $total_leave = $this->admin_model->check_by(array('leave_category_id' => $leave_category_id), 'tbl_leave_category');
            $leave_total = $total_leave->leave_quota;

            $all_leave = $this->db->where(array('leave_application_id !=' => $leave_application_id, 'user_id' => $user_id))->get('tbl_leave_application')->result();
            //            $leave_info = $this->db->where(array('leave_application_id' => $leave_application_id))->get('tbl_leave_application')->row();
            if (!empty($all_leave)) {
                foreach ($all_leave as $v_all_leave) {
                    if (empty($v_all_leave->leave_end_date)) {
                        $v_all_leave->leave_end_date = $v_all_leave->leave_start_date;
                    }
                    $get_dates = $this->admin_model->GetDays($v_all_leave->leave_start_date, $v_all_leave->leave_end_date);
                    //                    $get_datesaa[] = $this->admin_model->GetDays($v_all_leave->leave_start_date, $v_all_leave->leave_end_date);
                    $result_start = in_array($start_date, $get_dates);
                    if (!empty($end_date)) {
                        $result_end = in_array($end_date, $get_dates);
                        if (!empty($result_end)) {
                            $result = lang('leave_date_conflict') . ' Date is:' . $end_date;
                        }
                    }
                    if (!empty($result_start)) {
                        $result = lang('leave_date_conflict') . ' Date is:' . $start_date;
                    }
                }
            }

            $token_leave = $this->db->where(array('user_id' => $user_id, 'leave_category_id' => $leave_category_id, 'YEAR(`leave_start_date`)' => jdate('Y'), 'application_status' => '2'))->get('tbl_leave_application')->result();

            $total_taken = 0;
            $total_hourly = 0;
            if (!empty($token_leave)) {
                $res = calculate_taken_leave($token_leave);
                $total_taken = $res['total_taken'];
                $total_hourly = $res['total_hourly'];
            }
            if (empty($total_taken)) {
                $total_taken = 0;
            }
            if (empty($total_hourly)) {
                $total_hourly = 0;
            }
            $total_taken = $total_hourly + $total_taken;

            $input_ge_days = 0;
            $input_m_days = 0;
            if (!empty($end_date) && $end_date != 'null') {
                $input_month = cal_days_in_month(CAL_GREGORIAN, jdate('m', strtotime($start_date)), jdate('Y', strtotime($end_date)));

                $input_datetime1 = new DateTime($start_date);
                $input_datetime2 = new DateTime($end_date);
                $input_difference = $input_datetime1->diff($input_datetime2);
                if ($input_difference->m != 0) {
                    $input_m_days += $input_month;
                } else {
                    $input_m_days = 0;
                }
                $input_ge_days += $input_difference->d + 1;
                $input_total_taken = $input_m_days + $input_ge_days;
            } else {
                $input_total_taken = 1;
            }
            $taken_with_input = $total_taken + $input_total_taken;
            $left_leave = $leave_total - $total_taken;
            $left_leave_hours = $left_leave * $office_hours;
            $left_leave_days = (int)($left_leave_hours / $office_hours);

            $left_leave_hours = $left_leave_hours % $office_hours;
            $left_leave_string = $left_leave_days . ' days ' . $left_leave_hours . ' hours ';

            if ($leave_total < $taken_with_input) {
                if ($user_id == $this->session->userdata('user_id')) {
                    $t = 'You ';
                } else {
                    $profile = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
                    $t = $profile->fullname;
                }
                $total_taken = leave_days_hours($total_taken, true);
                $result = "$t already took  $total_taken $total_leave->leave_category You can apply maximum for $left_leave_string more";
            }
        } else {
            $result = lang('all_required_fill');
        }
        echo $result;
        exit();
    }

    public function get_leave_details($user_id)
    {
        if ($user_id == $this->session->userdata('user_id')) {
            $title = lang('my_leave');
        } else {
            $profile = $this->db->where('user_id', $user_id)->get('tbl_account_details')->row();
            $title = $profile->fullname;
        }
        $panel = null;
        $panel .= '<div class="panel panel-custom"><div class="panel-heading"><div class="panel-title"><strong>' . $title . ' ' . lang('details') . '</strong></div></div><table class="table"><tbody>';
        $total_taken = 0;
        $total_quota = 0;
        $leave_report = leave_report($user_id);
        if (!empty($leave_report['leave_category'])) {
            foreach ($leave_report['leave_category'] as $lkey => $v_l_report) {
                $total_quota += $leave_report['leave_quota'][$lkey];
                $total_taken += $leave_report['leave_taken'][$lkey];

                $panel .= '<tr><td><strong>' . $leave_report['leave_category'][$lkey] . '</strong>:</td><td>';
                $panel .= $leave_report['leave_taken'][$lkey] . '/' . $leave_report['leave_quota'][$lkey];
                $panel .= '</td></tr>';
            }
        }
        $panel .= '<tr><td class="l_report"><strong>' . lang('total') . '</strong>:</td><td class="l_report">' . $total_taken . '/' . $total_quota . '</td></tr></tbody></table></div>';
        echo $panel;
        exit();
    }

    public function get_employee_by_designations_id($designation_id)
    {
        $HTML = NULL;
        $this->admin_model->_table_name = 'tbl_account_details';
        $this->admin_model->_order_by = 'designations_id';
        $employee_info = $this->admin_model->get_by(array('designations_id' => $designation_id), FALSE);
        if (!empty($employee_info)) {
            foreach ($employee_info as $v_employee_info) {
                $HTML .= "<option value='" . $v_employee_info->user_id . "'>" . $v_employee_info->fullname . "</option>";
            }
        }
        echo $HTML;
        exit();
    }

    public function check_advance_amount($amount, $user_id = null)
    {
        $result = $this->common_model->get_advance_amount($user_id);
        if (!empty($result)) {
            if ($result < $amount) {
                echo lang('exced_basic_salary');
                exit();
            } else {
                echo null;
                exit();
            }
        } else {
            echo lang('you_can_not_apply');
            exit();
        }
    }

    public function get_taxes_dropdown()
    {
        $name = $this->input->post('name', true);
        $taxname = $this->input->post('taxname', true);
        echo $this->admin_model->get_taxes_dropdown($name, $taxname);
        exit();
    }

    /* Get item by id / ajax */
    public function get_item_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $item = $this->admin_model->get_item_by_id($id);
            echo json_encode($item);
            exit();
        }
    }

    public function update_ei_items_order($type)
    {
        $data = $this->input->post();
        foreach ($data['items_id'] as $order) {
            if ($type == 'estimate') {
                $this->db->where('estimate_items_id', $order[0]);
                $this->db->update('tbl_estimate_items', array(
                    'order' => $order[1]
                ));
            } else if ($type == 'credit_note') {
                $this->db->where('credit_note_items_id', $order[0]);
                $this->db->update('tbl_credit_note_items', array(
                    'order' => $order[1]
                ));
            } else if ($type == 'proposal') {
                $this->db->where('proposals_items_id', $order[0]);
                $this->db->update('tbl_proposals_items', array(
                    'order' => $order[1]
                ));
            } else if ($type == 'todo') {
                $this->db->where('todo_id', $order[0]);
                $this->db->update('tbl_todo', array(
                    'order' => $order[1]
                ));
            } else {
                $this->db->where('items_id', $order[0]);
                $this->db->update('tbl_items', array(
                    'order' => $order[1]
                ));
            }
        }
    }

    /* Set notifications to read */
    public function mark_as_read()
    {
        if ($this->input->is_ajax_request()) {
            $this->db->where('to_user_id', $this->session->userdata('user_id'));
            $this->db->update('tbl_notifications', array(
                'read' => 1
            ));
            if ($this->db->affected_rows() > 0) {
                echo json_encode(array(
                    'success' => true
                ));
            } //$this->db->affected_rows() > 0
            return false;
        }
    }

    public function read_inline($id)
    {
        $this->db->where('to_user_id', $this->session->userdata('user_id'));
        $this->db->where('notifications_id', $id);
        $this->db->update('tbl_notifications', array(
            'read_inline' => 1
        ));
    }

    public function mark_desktop_notification_as_read($id)
    {
        $this->db->where('to_user_id', $this->session->userdata('user_id'));
        $this->db->where('notifications_id', $id);
        $this->db->update('tbl_notifications', array(
            'read' => 1,
            'read_inline' => 1
        ));
    }

    public function mark_all_as_read()
    {
        $this->db->where('to_user_id', $this->session->userdata('user_id'));
        $this->db->update('tbl_notifications', array(
            'read' => 1,
            'read_inline' => 1
        ));
    }

    public function get_notification()
    {
        $notificationsIds = array();

        if (config_item('desktop_notifications') == "1") {
            $notifications = $this->common_model->get_user_notifications(false);

            $notificationsPluck = array_filter($notifications, function ($n) {
                return $n->read == 0;
            });
            $notificationsIds = array_pluck($notificationsPluck, 'notifications_id');
        }
        echo json_encode(array(
            'html' => $this->load->view('admin/components/notifications', array(), true),
            'notificationsIds' => $notificationsIds
        ));
        exit();
    }

    /* upload a post file */

    function upload_file()
    {
        upload_file_to_temp();
    }

    /* check valid file for project */

    function validate_project_file()
    {
        return validate_post_file($this->input->post("file_name", true));
    }

    function set_media_view($type, $module)
    {
        $k_session[$module . '_media_view'] = $type;
        $this->session->set_userdata($k_session);
        return true;
    }

    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public function set_language($lang)
    {
        $this->session->set_userdata('lang', $lang);
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/dashboard');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function download_all_attachment($type, $id)
    {

        $attachment_info = get_result('tbl_attachments', array('module' => $type, 'module_field_id' => $id));
        $FileName = $type . '_attachment';
        $this->load->library('zip');
        if (!empty($attachment_info) && !empty($FileName)) {
            foreach ($attachment_info as $v_attach) {
                $uploaded_files_info = $this->db->where('attachments_id', $v_attach->attachments_id)->get('tbl_attachments_files')->result();
                $filename = slug_it($FileName);
                foreach ($uploaded_files_info as $v_files) {
                    $down_data = ($v_files->files); // Read the file's contents
                    $this->zip->read_file($down_data);
                }
                $this->zip->download($filename . '.zip');
            }
        } else {
            $type = "error";
            $message = lang('operation_failed');
            set_message($type, $message);
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/dashboard');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function get_search_result()
    {
        $value = $this->input->post('value', true);
        $searchType = $this->input->post('searchType', true);
        if (!empty($value)) {
            $result = $this->getSearchResult($searchType, $value);
            echo json_encode($result);
            exit();
        } else {
            set_message('error', lang('nothing_to_display'));
            redirect('admin/dashboard');
        }
    }

    function getSearchResult($searchType, $value)
    {
        $where2 = '';
        if (!empty($searchType)) {
            if ($searchType == 'projects') {
                $tblName = 'tbl_project';
                $where = 'project_name';
                $where2 = 'project_no';
                $id = 'project_id';
            } else if ($searchType == 'tasks') {
                $tblName = 'tbl_task';
                $where = 'task_name';
                $id = 'task_id';
            } else if ($searchType == 'employee') {
                $tblName = 'tbl_account_details';
                $where = 'fullname';
                $where2 = 'employment_id';
                $id = 'user_id';
            } else if ($searchType == 'client') {
                $tblName = 'tbl_client';
                $where = 'name';
                $where2 = 'email';
                $id = 'client_id';
            } else if ($searchType == 'bugs') {
                $tblName = 'tbl_bug';
                $where = 'bug_title';
                $where2 = 'issue_no';
                $id = 'bug_id';
            } else if ($searchType == 'opportunities') {
                $tblName = 'tbl_opportunities';
                $where = 'opportunity_name';
                $where2 = 'stages';
                $id = 'opportunities_id';
            } elseif ($searchType == 'leads') {
                $tblName = 'tbl_leads';
                $where = 'lead_name';
                $id = 'leads_id';
            } elseif ($searchType == 'purchase') {
                $tblName = 'tbl_purchases';
                $where = 'reference_no';
                $id = 'purchase_id';
            } elseif ($searchType == 'invoice') {
                $tblName = 'tbl_invoices';
                $where = 'reference_no';
                $id = 'invoices_id';
            } elseif ($searchType == 'credit_note') {
                $tblName = 'tbl_credit_note';
                $where = 'reference_no';
                $id = 'credit_note_id';
            } elseif ($searchType == 'estimates') {
                $tblName = 'tbl_estimates';
                $where = 'reference_no';
                $id = 'estimates_id';
            } elseif ($searchType == 'tickets') {
                $tblName = 'tbl_tickets';
                $where = 'subject';
                $where2 = 'ticket_code';
                $id = 'tickets_id';
            }
        }
        if (!empty($tblName)) {
            $select = $tblName . '.*,' . $tblName . '.' . $where . ' as title,' . $tblName . '.' . $id . ' as id';
            $this->db->select($select);
            $this->db->from($tblName);
            $this->db->like($where, $value);
            if (!empty($where2)) {
                $this->db->or_like($where2, $value);
            }
            $query_result = $this->db->get();
            $result = $query_result->result();
        }
        $result_array = array();
        if (!empty($result)) {
            foreach ($result as $value) {
                $title = $value->title;
                if (!empty($where2) && !empty($value->$where2)) {
                    $title = $value->title . '(' . $value->$where2 . ')';
                }
                $result_array[] = array("value" => $value->id, "label" => $title);
            }
        }
        return $result_array;
    }

    public function items_suggestions($limit = null)
    {
        if (empty($limit)) {
            $limit = 12;
            $class = 'select_pos_item';
        } else {
            $class = 'select_item';
        }
        $term = $this->input->post('term', true);
        $warehouse_id = $this->input->post('warehouse_id', true);
        if (!empty($warehouse_id)) {
            $rows = $this->admin_model->getItemsInfo($term, $warehouse_id, $limit);
        }

        $html = null;
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $imgaeURL = base_url('assets/img/product.png');
                if (!empty($row->upload_file)) {
                    $uploaded_file = json_decode($row->upload_file);
                    $rand_keys = array_rand($uploaded_file, 1);
                    if ($uploaded_file[$rand_keys]->is_image == 1) {
                        $imgaeURL = base_url($uploaded_file[$rand_keys]->path);
                    } else {
                        $imgaeURL = base_url('assets/img/filepreview.jpg');
                    }
                }
                $html .= '<div class="col-xs-2 p0 m0" style="max-height:108px;overflow: hidden"> <a  class="' . $class . ' btn p0" data-item-id="' . $row->saved_items_id . '">
                                            <img class="round" src="' . $imgaeURL . '" style="height: 70px;width: 70%"> <div class="text-xs-center text"> <small style="white-space: pre-wrap;">' . $row->item_name . ' ' . $row->code . '</small></div></a></div>';
            }
            echo $html;
        }
        exit();
    }

    public function itemAddedManualy()
    {
        $data = (object)$this->input->post();
        $saved_items_id = 0;
        $data->saved_items_id = $saved_items_id;
        $data->code = '';
        $data->new_item_id = $saved_items_id;
        $tax_info = $this->input->post('tax_rates_id', true);
        if (!empty($tax_info)) {
            foreach ($tax_info as $tax_id) {
                $tax = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                if (!empty($tax->tax_rate_name)) {
                    $tax_name[] = $tax->tax_rate_name . '|' . $tax->tax_rate_percent;
                }
            }
            $tax = (object)[
                'taxname' => (!empty($tax_name) ? ($tax_name) : null),
            ];
        }
        unset($data->tax_rates_id);
        if (empty($tax)) {
            $tax = (object)[
                'taxname' => '',
            ];
        }
        $result = (object)array_merge((array)$data, (array)$tax);
        $pr = array('saved_items_id' => $saved_items_id, 'label' => $data->item_name, 'row' => $result);
        echo json_encode($pr);
        die();
    }

    function suggestions($id = NULL)
    {
        $for_purcahse = $this->input->get('for', true);
        $cost = 'unit_cost';
        if ($for_purcahse == 'purchase_item') {
            $cost = 'cost_price';
        }
        if (!empty($id)) {
            // $row = $this->itemInfo($id);
            $row = $this->admin_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
            $row->qty = 1;
            $row->rate = $row->$cost;
            $row->unit = $row->unit_type;
            $row->new_itmes_id = $row->saved_items_id;
            $tax_info = json_decode($row->tax_rates_id);
            if (!empty($tax_info)) {
                foreach ($tax_info as $tax_id) {
                    $tax = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                    if (!empty($tax->tax_rate_name)) {
                        $tax_name[] = $tax->tax_rate_name . '|' . $tax->tax_rate_percent;
                    }
                }
                $tax = (object)[
                    'taxname' => (!empty($tax_name) ? ($tax_name) : null),
                ];
            }
            if (empty($tax)) {
                $tax = (object)[
                    'taxname' => '',
                ];
            }
            $result = (object)array_merge((array)$row, (array)$tax);
            $pr = array('saved_items_id' => str_replace(".", "", microtime(true)), 'saved_items_id' => $row->saved_items_id, 'label' => $row->item_name . " (" . $row->code . ")", 'row' => $result);
            echo json_encode($pr);
            die();
        }
        $term = $this->input->get('term', TRUE);

        $warehouse_id = $this->input->get('warehouse_id', TRUE);
        if (!empty($warehouse_id)) {
            $rows = $this->admin_model->getItemsInfo($term, $warehouse_id);
        }
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $row->qty = 1;
                $row->rate = $row->$cost;
                $row->unit = $row->unit_type;
                $row->new_itmes_id = $row->saved_items_id;
                $tax_info = json_decode($row->tax_rates_id);
                if (!empty($tax_info)) {
                    foreach ($tax_info as $tax_id) {
                        $tax = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                        if (!empty($tax->tax_rate_name)) {
                            $tax_name[] = $tax->tax_rate_name . '|' . $tax->tax_rate_percent;
                        }
                    }
                    $tax = (object)[
                        'taxname' => (!empty($tax_name) ? ($tax_name) : null),
                    ];
                }
                if (empty($tax)) {
                    $tax = (object)[
                        'taxname' => '',
                    ];
                }
                $result = (object)array_merge((array)$row, (array)$tax);
                $pr[] = array('saved_items_id' => $row->saved_items_id, 'label' => $row->item_name . " (" . $row->code . ")", 'row' => $result);
            }
            echo json_encode($pr);
            die();
        } else {
            echo json_encode(array(array('saved_items_id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
            die();
        }
    }

    public function getItemByWarehouse($warehouseId = NULL)
    {
        $where = null;
        $class = 'select_pos_item';
        if (!empty($warehouseId)) {
            $rows = $this->admin_model->getItemsInfo('', $warehouseId);
        }
        $html = null;
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $imgaeURL = base_url('assets/img/product.png');
                if (!empty($row->upload_file)) {
                    $uploaded_file = json_decode($row->upload_file);
                    $rand_keys = array_rand($uploaded_file, 1);
                    if ($uploaded_file[$rand_keys]->is_image == 1) {
                        $imgaeURL = base_url($uploaded_file[$rand_keys]->path);
                    } else {
                        $imgaeURL = base_url('assets/img/filepreview.jpg');
                    }
                }
                $html .= '<div class="col-xs-2 p0 m0" style="max-height:108px;overflow: hidden"> <a class="' . $class . ' btn p0" data-item-id="' . $row->saved_items_id . '">
                                            <img class="round" src="' . $imgaeURL . '" style="height: 70px;width: 70%"> <div class="text-xs-center text"> <small style="white-space: pre-wrap;">' . $row->item_name . ' ' . $row->code . '</small></div></a></div>';
            }
        }
        echo json_encode($html);
        exit();
    }


    function save_comments()
    {
        $data['module'] = $this->input->post('module', TRUE);
        $data['module_field_id'] = $this->input->post('module_field_id', TRUE);
        $data['comment'] = $this->input->post('comment', TRUE);

        $files = $this->input->post("files", true);
        $target_path = getcwd() . "/uploads/";
        //process the fiiles which has been uploaded by dropzone
        if (!empty($files) && is_array($files)) {
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $file_name = $this->input->post('file_name_' . $file, true);
                    $new_file_name = move_temp_file($file_name, $target_path);
                    $file_ext = explode(".", $new_file_name);
                    $is_image = check_image_extension($new_file_name);
                    $size = $this->input->post('file_size_' . $file, true) / 1000;
                    if ($new_file_name) {
                        $up_data[] = array(
                            "fileName" => $new_file_name,
                            "path" => "uploads/" . $new_file_name,
                            "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                            "ext" => '.' . end($file_ext),
                            "size" => round($size, 2),
                            "is_image" => $is_image,
                        );
                        $success = true;
                    } else {
                        $success = false;
                    }
                }
            }
        }
        //process the files which has been submitted manually
        if ($_FILES) {
            $files = $_FILES['manualFiles'];
            if ($files && count(array($files)) > 0) {
                foreach ($files["tmp_name"] as $key => $file) {
                    $temp_file = $file;
                    $file_name = $files["name"][$key];
                    $file_size = $files["size"][$key];
                    $new_file_name = move_temp_file($file_name, $target_path, "", $temp_file);
                    if ($new_file_name) {
                        $file_ext = explode(".", $new_file_name);
                        $is_image = check_image_extension($new_file_name);
                        $up_data[] = array(
                            "fileName" => $new_file_name,
                            "path" => "uploads/" . $new_file_name,
                            "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                            "ext" => '.' . end($file_ext),
                            "size" => round($file_size, 2),
                            "is_image" => $is_image,
                        );
                    }
                }
            }
        }
        if (!empty($up_data)) {
            $data['comments_attachment'] = json_encode($up_data);
        }

        $data['user_id'] = $this->session->userdata('user_id');

        //save data into table.
        $this->common_model->_table_name = "tbl_task_comment"; // table name
        $this->common_model->_primary_key = "task_comment_id"; // $id
        $comment_id = $this->common_model->save($data);
        if ($this->session->userdata('user_type') == 2) {
            $url = 'client/';
        } else {
            $url = 'admin/';
        }
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => $data['module'],
            'module_field_id' => $data['module_field_id'],
            'activity' => 'activity_new_' . $data['module'] . '_comment',
            'icon' => 'fa-rocket',
            'link' => $url . $data['module'] . '/details/' . $data['module_field_id'] . '/2',
            'value1' => $data['comment'],
        );

        // Update into tbl_project
        $this->common_model->_table_name = "tbl_activities"; //table name
        $this->common_model->_primary_key = "activities_id";
        $this->common_model->save($activities);

        if (!empty($comment_id)) {
            $response_data = "";
            $view_data['comment_type'] = $data['module'];
            $view_data['comment_details'] = $this->common_model->get_comment_details($comment_id);
            $response_data = $this->load->view("admin/common/comments_list", $view_data, true);
            echo json_encode(array("status" => 'success', "data" => $response_data, 'message' => lang($data['module'] . '_comment_save')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('error_occurred')));
            exit();
        }
    }

    public function save_comments_reply($comment_id)
    {
        $data['module'] = $this->input->post('module', true);
        $data['module_field_id'] = $this->input->post('module_field_id', true);
        $data['comment'] = $this->input->post('reply_comments', true);
        $data['user_id'] = $this->session->userdata('user_id');
        $data['comments_reply_id'] = $comment_id;
        //save data into table.
        $this->common_model->_table_name = "tbl_task_comment"; // table name
        $this->common_model->_primary_key = "task_comment_id"; // $id
        $comment_id = $this->common_model->save($data);
        if (!empty($comment_id)) {
            $comments_info = $this->common_model->get_comment_details($comment_id)[0];
            if ($this->session->userdata('user_type') == 2) {
                $url = 'client/';
            } else {
                $url = 'admin/';
            }
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => $data['module'],
                'module_field_id' => $data['module_field_id'],
                'activity' => 'activity_new_' . $data['module'] . '_comment_reply',
                'icon' => 'fa-rocket',
                'link' => $url . $data['module'] . '/details/' . $data['module_field_id'] . '/2',
                'value1' => $comments_info->comment,
                'value2' => $data['comment'],
            );
            // Update into tbl_project
            $this->common_model->_table_name = "tbl_activities"; //table name
            $this->common_model->_primary_key = "activities_id";
            $this->common_model->save($activities);


            $notifiedUsers = array($comments_info->user_id);
            if (!empty($notifiedUsers)) {
                foreach ($notifiedUsers as $users) {
                    if ($users != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $users,
                            'from_user_id' => true,
                            'description' => 'not_new_comment',
                            'link' => $url . $data['module'] . '/details/' . $data['module_field_id'] . '/2',
                            'value' => lang('lead') . ' ' . $data['comment'],
                        ));
                    }
                }
                show_notification($notifiedUsers);
            }
            $response_data = "";
            $view_data['comment_reply_details'] = $this->common_model->get_comment_details($comment_id);
            $view_data['comment_type'] = $data['module'];
            $response_data = $this->load->view("admin/common/comments_reply", $view_data, true);
            echo json_encode(array("status" => 'success', "data" => $response_data, 'message' => lang($data['module'] . '_comment_save')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('error_occurred')));
            exit();
        }
    }

    public
    function delete_comments($task_comment_id = null)
    {
        $comments_info = $this->common_model->check_by(array('task_comment_id' => $task_comment_id), 'tbl_task_comment');

        if (!empty($comments_info->comments_attachment)) {
            $attachment = json_decode($comments_info->comments_attachment);
            foreach ($attachment as $v_file) {
                remove_files($v_file->fileName);
            }
        }
        if ($this->session->userdata('user_type') == 2) {
            $url = 'client/';
        } else {
            $url = 'admin/';
        }
        // save into activities
        $activities = array(
            'user' => $this->session->userdata('user_id'),
            'module' => $comments_info->module,
            'module_field_id' => $comments_info->module_field_id,
            'activity' => 'activity_comment_deleted',
            'icon' => 'fa-rocket',
            'link' => $url . $comments_info->module . '/details/' . $comments_info->module_field_id . '/2',
            'value1' => $comments_info->comment,
        );
        // Update into tbl_project
        $this->common_model->_table_name = "tbl_activities"; //table name
        $this->common_model->_primary_key = "activities_id";
        $this->common_model->save($activities);

        //save data into table.
        $this->common_model->_table_name = "tbl_task_comment"; // table name
        $this->common_model->delete_multiple(array('comments_reply_id' => $task_comment_id));
        //save data into table.
        $this->common_model->_table_name = "tbl_task_comment"; // table name
        $this->common_model->_primary_key = "task_comment_id"; // $id
        $this->common_model->delete($task_comment_id);

        echo json_encode(array("status" => 'success', 'message' => lang('task_comment_deleted')));
        exit();
    }

    public function download_files($uploaded_files_id, $comments = null)
    {
        $this->load->helper('download');
        if (!empty($comments)) {
            if ($uploaded_files_id) {
                $down_data = file_get_contents('uploads/' . $uploaded_files_id); // Read the file's contents
                if (!empty($down_data)) {
                    force_download($uploaded_files_id, $down_data);
                } else {
                    $type = "error";
                    $message = lang('operation_failed');
                    set_message($type, $message);
                    if (empty($_SERVER['HTTP_REFERER'])) {
                        redirect('admin/dashboard');
                    } else {
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
            } else {
                $type = "error";
                $message = 'Operation Fieled !';
                set_message($type, $message);
                if (empty($_SERVER['HTTP_REFERER'])) {
                    redirect('admin/dashboard');
                } else {
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        } else {
            $uploaded_files_info = $this->common_model->check_by(array('uploaded_files_id' => $uploaded_files_id), 'tbl_attachments_files');
            if ($uploaded_files_info->uploaded_path) {
                $data = file_get_contents($uploaded_files_info->uploaded_path); // Read the file's contents
                force_download($uploaded_files_info->file_name, $data);
            } else {
                $type = "error";
                $message = lang('operation_failed');
                set_message($type, $message);
                if (empty($_SERVER['HTTP_REFERER'])) {
                    redirect('admin/dashboard');
                } else {
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
    }

    public
    function download_all_files($attachment_id)
    {
        $uploaded_files_info = $this->db->where('attachments_id', $attachment_id)->get('tbl_attachments_files')->result();
        $attachment_info = $this->db->where('attachments_id', $attachment_id)->get('tbl_attachments')->row();
        $this->load->library('zip');
        if (!empty($uploaded_files_info)) {
            $filename = slug_it($attachment_info->title);
            foreach ($uploaded_files_info as $v_files) {
                $down_data = ($v_files->files); // Read the file's contents
                $this->zip->read_file($down_data);
            }
            $this->zip->download($filename . '.zip');
        } else {
            $type = "error";
            $message = lang('operation_failed');
            set_message($type, $message);
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/dashboard');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public
    function new_attachment($module, $id)
    {
        $data['title'] = lang('new_attachment');
        $data['dropzone'] = true;
        $data['module'] = $module;
        $data['module_field_id'] = $id;
        $data['modal_subview'] = $this->load->view('admin/common/new_attachment', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public
    function attachment_details($type, $id, $files_id = null)
    {
        $data['title'] = lang('attachment') . ' ' . lang('details');
        $data['type'] = $type;
        $data['dropzone'] = true;
        $data['module'] = 'attachment-module';
        $data['attachment_info'] = $this->common_model->get_attach_file($id, $type, $files_id);
        $data['modal_subview'] = $this->load->view('admin/common/attachment_details', $data, FALSE);
        $this->load->view('admin/_layout_modal_extra_lg', $data);
    }

    public function save_attachment($attachments_id = null)
    {
        $data = $this->common_model->array_from_post(array('title', 'description', 'module', 'module_field_id'));
        $data['user_id'] = $this->session->userdata('user_id');

        // save and update into tbl_files
        $this->common_model->_table_name = "tbl_attachments"; //table name
        $this->common_model->_primary_key = "attachments_id";
        if (!empty($attachments_id)) {
            $id = $attachments_id;
            $this->common_model->save($data, $id);
            $msg = lang('file_updated');
        } else {
            $id = $this->common_model->save($data);
            $msg = lang('file_added');
        }
        $files = $this->input->post("files", true);

        $target_path = getcwd() . "/uploads/";
        //process the fiiles which has been uploaded by dropzone
        if (!empty($files) && is_array($files)) {
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $file_name = $this->input->post('file_name_' . $file, true);
                    $new_file_name = move_temp_file($file_name, $target_path);
                    $file_ext = explode(".", $new_file_name);
                    $is_image = check_image_extension($new_file_name);

                    if ($new_file_name) {
                        $up_data = array(
                            "files" => "uploads/" . $new_file_name,
                            "uploaded_path" => getcwd() . "/uploads/" . $new_file_name,
                            "file_name" => $new_file_name,
                            "size" => $this->input->post('file_size_' . $file, true),
                            "ext" => end($file_ext),
                            "is_image" => $is_image,
                            "image_width" => 0,
                            "image_height" => 0,
                            "attachments_id" => $id
                        );
                        $this->common_model->_table_name = "tbl_attachments_files"; // table name
                        $this->common_model->_primary_key = "uploaded_files_id"; // $id
                        $uploaded_files_id = $this->common_model->save($up_data);

                        // saved into comments
                        $comment = $this->input->post('comment_' . $file, true);
                        if (!empty($comment)) {
                            $u_cdata = array(
                                "comment" => $comment,
                                "module" => $data['module'],
                                "module_field_id" => $data['module_field_id'],
                                "user_id" => $this->session->userdata('user_id'),
                                "uploaded_files_id" => $uploaded_files_id,
                            );
                            $this->common_model->_table_name = "tbl_task_comment"; // table name
                            $this->common_model->_primary_key = "task_comment_id"; // $id
                            $this->common_model->save($u_cdata);
                        }
                        $success = true;
                    } else {
                        $success = false;
                    }
                }
            }
        }
        //process the files which has been submitted manually
        if ($_FILES) {
            $files = $_FILES['manualFiles'];
            if ($files && count(array($files)) > 0) {
                $comment = $this->input->post('comment', true);
                foreach ($files["tmp_name"] as $key => $file) {
                    $temp_file = $file;
                    $file_name = $files["name"][$key];
                    $file_size = $files["size"][$key];
                    $new_file_name = move_temp_file($file_name, $target_path, "", $temp_file);
                    if ($new_file_name) {
                        $file_ext = explode(".", $new_file_name);
                        $is_image = check_image_extension($new_file_name);
                        $up_data = array(
                            "files" => "uploads/" . $new_file_name,
                            "uploaded_path" => getcwd() . "/uploads/" . $new_file_name,
                            "file_name" => $new_file_name,
                            "size" => $file_size,
                            "ext" => end($file_ext),
                            "is_image" => $is_image,
                            "image_width" => 0,
                            "image_height" => 0,
                            "attachments_id" => $id
                        );
                        $this->common_model->_table_name = "tbl_attachments_files"; // table name
                        $this->common_model->_primary_key = "uploaded_files_id"; // $id
                        $uploaded_files_id = $this->common_model->save($up_data);

                        // saved into comments
                        if (!empty($comment[$key])) {
                            $u_cdata = array(
                                "comment" => $comment[$key],
                                "module" => $data['module'],
                                "module_field_id" => $data['module_field_id'],
                                "user_id" => $this->session->userdata('user_id'),
                                "uploaded_files_id" => $uploaded_files_id,
                            );
                            $this->common_model->_table_name = "tbl_task_comment"; // table name
                            $this->common_model->_primary_key = "task_comment_id"; // $id
                            $this->common_model->save($u_cdata);
                        }
                    }
                }
            }
        }
        $moduleUrl = '/details/';
        if ($data['module'] == 'projects') {
            $moduleUrl = '/project_details/';
        }
        if ($data['module'] == 'leads') {
            $moduleUrl = '/leads_details/';
        }
        $url = 'admin/' . $data['module'] . $moduleUrl . $data['module_field_id'] . '/attachments';
        if ($success) {
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => $data['module'],
                'module_field_id' => $data['module_field_id'],
                'activity' => 'activity_new_' . $data['module'] . '_attachment',
                'icon' => 'fa-rocket',
                'link' => $url,
                'value1' => $data['title'],
            );
            // Update into tbl_project
            $this->common_model->_table_name = "tbl_activities"; //table name
            $this->common_model->_primary_key = "activities_id";
            $this->common_model->save($activities);

            $configEmail = $data['module'] . '_email';
            $tasks_email = config_item($configEmail);
            if (!empty($tasks_email) && $tasks_email == 1) {
                // send notification message
                $model = $data['module'] . '_model';
                // $this->$model->notify_attachment_email($id);
            }

            if (!empty($notifiedUsers)) {
                foreach ($notifiedUsers as $users) {
                    if ($users != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $users,
                            'from_user_id' => true,
                            'description' => 'not_uploaded_attachment',
                            'link' => $url,
                            'value' => lang($data['module']) . ' ' . $data['title'],
                        ));
                    }
                }
                show_notification($notifiedUsers);
            }
            // messages for user
            $type = "success";
            $message = $msg;
        }
        set_message($type, $message);
        redirect($url);
    }

    public function delete_attach_files($attachments_id = null)
    {
        if (empty($attachments_id)) {
            echo json_encode(array("status" => 'error', 'message' => 'Ops there is an issue for delete.please contact administrator'));
        } else {
            $file_info = $this->items_model->check_by(array('attachments_id' => $attachments_id), 'tbl_attachments');
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => $file_info->module,
                'module_field_id' => $file_info->module_field_id,
                'activity' => 'activity_opportunity_attachfile_deleted',
                'icon' => 'fa-filter',
                'link' => 'admin/' . $file_info->module . '/details/' . $file_info->module_field_id . '/5',
                'value1' => $file_info->title,
            );
            // Update into tbl_project
            $this->items_model->_table_name = "tbl_activities"; //table name
            $this->items_model->_primary_key = "activities_id";
            $this->items_model->save($activities);

            //save data into table.
            $this->items_model->_table_name = "tbl_attachments"; // table name
            $this->items_model->delete_multiple(array('attachments_id' => $attachments_id));

            $uploadFileinfo = $this->db->where('attachments_id', $attachments_id)->get('tbl_attachments_files')->result();
            if (!empty($uploadFileinfo)) {
                foreach ($uploadFileinfo as $Fileinfo) {
                    remove_files($Fileinfo->file_name);
                }
            }
            //save data into table.
            $this->items_model->_table_name = "tbl_attachments_files"; // table name
            $this->items_model->delete_multiple(array('attachments_id' => $attachments_id));
            echo json_encode(array("status" => 'success', 'message' => lang('attachfile_successfully_deleted')));
        }
        exit();
    }

    public
    function save_attachment_comments()
    {
        $attachments_id = $this->input->post('attachments_id');
        if (!empty($attachments_id)) {
            $data['attachments_id'] = $attachments_id;
        } else {
            $data['uploaded_files_id'] = $this->input->post('uploaded_files_id');
        }

        $data['comment'] = $this->input->post('description', true);

        $files = $this->input->post("files", true);
        $target_path = getcwd() . "/uploads/";
        //process the fiiles which has been uploaded by dropzone
        if (!empty($files) && is_array($files)) {
            foreach ($files as $key => $file) {
                if (!empty($file)) {
                    $file_name = $this->input->post('file_name_' . $file, true);
                    $new_file_name = move_temp_file($file_name, $target_path);
                    $file_ext = explode(".", $new_file_name);
                    $is_image = check_image_extension($new_file_name);
                    $size = $this->input->post('file_size_' . $file, true) / 1000;
                    if ($new_file_name) {
                        $up_data[] = array(
                            "fileName" => $new_file_name,
                            "path" => "uploads/" . $new_file_name,
                            "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                            "ext" => '.' . end($file_ext),
                            "size" => round($size, 2),
                            "is_image" => $is_image,
                        );
                        $success = true;
                    } else {
                        $success = false;
                    }
                }
            }
        }
        //process the files which has been submitted manually
        if ($_FILES) {
            $files = $_FILES['manualFiles'];
            if ($files && count(array($files)) > 0) {
                $comment = $this->input->post('comment', true);
                foreach ($files["tmp_name"] as $key => $file) {
                    $temp_file = $file;
                    $file_name = $files["name"][$key];
                    $file_size = $files["size"][$key];
                    $new_file_name = move_temp_file($file_name, $target_path, "", $temp_file);
                    if ($new_file_name) {
                        $file_ext = explode(".", $new_file_name);
                        $is_image = check_image_extension($new_file_name);
                        $up_data[] = array(
                            "fileName" => $new_file_name,
                            "path" => "uploads/" . $new_file_name,
                            "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                            "ext" => '.' . end($file_ext),
                            "size" => round($file_size, 2),
                            "is_image" => $is_image,
                        );
                    }
                }
            }
        }
        if (!empty($up_data)) {
            $data['comments_attachment'] = json_encode($up_data);
        }
        $data['user_id'] = $this->session->userdata('user_id');

        //save data into table.
        $this->common_model->_table_name = "tbl_task_comment"; // table name
        $this->common_model->_primary_key = "task_comment_id"; // $id
        $comment_id = $this->common_model->save($data);
        if (!empty($comment_id)) {
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'tasks',
                'module_field_id' => $data['task_id'],
                'activity' => 'activity_new_task_comment',
                'icon' => 'fa-tasks',
                'link' => 'admin/tasks/details/' . $data['task_id'] . '/2',
                'value1' => $data['comment'],
            );
            // Update into tbl_project
            $this->common_model->_table_name = "tbl_activities"; //table name
            $this->common_model->_primary_key = "activities_id";
            $this->common_model->save($activities);

            $tasks_info = $this->common_model->check_by(array('task_id' => $data['task_id']), 'tbl_task');

            $notifiedUsers = array();
            if (!empty($tasks_info->permission) && $tasks_info->permission != 'all') {
                $permissionUsers = json_decode($tasks_info->permission);
                foreach ($permissionUsers as $user => $v_permission) {
                    array_push($notifiedUsers, $user);
                }
            } else {
                $notifiedUsers = $this->common_model->allowed_user_id('54');
            }
            if (!empty($notifiedUsers)) {
                foreach ($notifiedUsers as $users) {
                    if ($users != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $users,
                            'from_user_id' => true,
                            'description' => 'not_new_comment',
                            'link' => 'admin/tasks/details/' . $data['task_id'] . '/2',
                            'value' => lang('task') . ' ' . $tasks_info->task_name,
                        ));
                    }
                }
                show_notification($notifiedUsers);
            }
            $response_data = "";
            $view_data['comment_details'] = $this->db->where(array('task_comment_id' => $comment_id))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();
            $response_data = $this->load->view("admin/tasks/comments_list", $view_data, true);
            echo json_encode(array("status" => 'success', "data" => $response_data, 'message' => lang('task_comment_save')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('error_occurred')));
            exit();
        }
    }


}
