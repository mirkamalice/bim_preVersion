<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Resignation extends Admin_Controller
{
    // promotion
    public function __construct()
    {
        parent::__construct();
        $this->load->model('resignation_model');
    }
    public function index($id = NULL)
    {
        $data['title'] = lang('resignation');
        if ($id) {
            $data['resignations'] = $this->db->where('id', $id)->get('tbl_resignations')->row();
            if (empty($data['resignations'])) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/resignation');
            }
        }
        $data['subview'] = $this->load->view('admin/resignation/resignation', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function resignationList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_resignations';
            $this->datatables->join_table = array('tbl_account_details');
            $this->datatables->join_where = array('tbl_resignations.employee_id=tbl_account_details.user_id');
            $action_array = array('tbl_resignations.id');
            $main_column = array('notice_date', 'resignation_date', 'description', 'tbl_account_details.fullname');
            $result = array_merge($main_column, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('id' => 'desc');
            $fetch_data = make_datatables();
            $edited = can_action('100', 'edited');
            $deleted = can_action('100', 'deleted');
            $data = array();
            foreach ($fetch_data as $_key => $v_resignations) {
                $action = null;
                $sub_array = array();
                $sub_array[] = $v_resignations->fullname;
                $sub_array[] = display_date($v_resignations->notice_date);
                $sub_array[] = display_date($v_resignations->resignation_date);
                $action .= btn_view_modal('admin/resignation/resignation_details/' . $v_resignations->id) . ' ';
                if (!empty($edited)) {
                    $action .= '<a href="' . base_url() . 'admin/resignation/new_resignation/' . $v_resignations->id . '"
                               class="btn btn-primary btn-xs" title="' . lang('edit') . '" data-toggle="modal"
                               data-target="#myModal_lg"><span class="fa fa-pencil-square-o"></span></a>  ';
                }
                if (!empty($deleted)) {
                    $action .= ajax_anchor(base_url('admin/resignation/delete_resignation/' . $v_resignations->id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function resignation_details($id = null)
    {
        $data['title'] = lang('resignation_details'); //Page title
        $data['resignation_details'] = join_data('tbl_resignations', '*', array('id' => $id), array('tbl_account_details' => 'tbl_account_details.user_id = tbl_resignations.employee_id'));
        if (empty($data['resignation_details'])) {
            $type = "error";
            $message = lang("no_record_found");
            set_message($type, $message);
            redirect('admin/resignation');
        }
        $data['subview'] = $this->load->view('admin/resignation/resignation_details', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data);
    }

    public function new_resignation($id = null)
    {
        $data['title'] = lang('resignation'); //Page title
        if (!empty($id)) {
            $edited = can_action('100', 'edited');
            if (!empty($edited)) {
                $data['resignations'] = $this->db->where('id', $id)->get('tbl_resignations')->row();
            }
            if (empty($data['resignations'])) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/resignation/new_resignation');
            }
        }
        $this->load->model('attendance_model');
        $data['all_employee'] = $this->attendance_model->get_all_employee();
        $data['subview'] = $this->load->view('admin/resignation/new_resignation', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }
    public function save_resignation($id = NULL)
    {
        $created = can_action('100', 'created');
        $edited = can_action('100', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $data = $this->resignation_model->array_from_post(array(
                'employee_id',
                'notice_date',
                'resignation_date',
                'description',

            ));

            $where = array('employee_id' => $data['employee_id']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $user_id = array('id !=' => $id);
            } else { // if id is not exist then set id as null
                $user_id = null;
            }
            // check whether this input data already exist or not
            $check_users = $this->resignation_model->check_update('tbl_resignations', $where, $user_id);
            if (!empty($check_users)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = lang('resignation_already_exist');
            } else {
                $this->resignation_model->_table_name = "tbl_resignations"; // table name
                $this->resignation_model->_primary_key = "id"; // $id
                $return_id = $this->resignation_model->save($data, $id);
                $get_user = get_row('tbl_users', array('user_id' => $data['employee_id']));
                $this->resignation_model->_table_name = "tbl_users"; //table name
                $this->resignation_model->_primary_key = 'user_id';
                $uDate['activated'] = 0;
                $this->resignation_model->save($uDate, $get_user->user_id);
                if (!empty($id)) {
                    $activity = 'activity_update_resignations';
                    $msg = lang('resignations_information_update');
                } else {
                    $activity = 'activity_added_resignations';
                    $msg = lang('resignations_information_saved');
                }
                // save into activities
                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'resignation',
                    'module_field_id' => $id,
                    'activity' => $activity,
                    'icon' => 'fa-ticket',
                    'value1' => $data['title'],
                );

                // Update into tbl_project
                $this->resignation_model->_table_name = "tbl_activities"; //table name
                $this->resignation_model->_primary_key = "activities_id";
                $this->resignation_model->save($activities);

                // messages for user
                $type = "success";
            }
            $message = $msg;
            set_message($type, $message);
        }
        redirect('admin/resignation');
    }
    public function delete_resignation($id = NULL)
    {
        $deleted = can_action('100', 'deleted');
        if (!empty($deleted)) {
            $resignations_info = $this->resignation_model->check_by(array('id' => $id), 'tbl_resignations');
            if (empty($resignations_info)) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/resignation');
            }

            $get_user = get_row('tbl_users', array('user_id' => $resignations_info->employee_id));
            $resignationInfo = get_row('tbl_terminations', array('employee_id' => $resignations_info->employee_id));
            if (!empty($get_user) && empty($resignationInfo)) {
                $this->resignation_model->_table_name = "tbl_users"; //table name
                $this->resignation_model->_primary_key = 'user_id';
                $uDate['activated'] = 1;
                $this->resignation_model->save($uDate, $get_user->user_id);
            }

            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'resignation',
                'module_field_id' => $id,
                'activity' => 'activity_delete_resignation',
                'icon' => 'fa-ticket',
                'value1' => $resignations_info->title,
            );
            // Update into tbl_project
            $this->resignation_model->_table_name = "tbl_activities"; //table name
            $this->resignation_model->_primary_key = "activities_id";
            $this->resignation_model->save($activities);
            $this->resignation_model->_table_name = "tbl_resignations";
            $this->resignation_model->_primary_key = "id";
            $this->resignation_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('resignation_information_delete');
        }
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
}
