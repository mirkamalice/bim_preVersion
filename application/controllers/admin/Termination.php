<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Termination extends Admin_Controller
{
    // promotion
    public function __construct()
    {
        parent::__construct();
        $this->load->model('termination_model');
    }
    public function index($id = NULL)
    {
        $data['title'] = lang('termination');
        if ($id) {
            $data['terminations'] = $this->db->where('id', $id)->get('tbl_terminations')->row();
            if (empty($data['terminations'])) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/warning');
            }
        }
        $data['subview'] = $this->load->view('admin/termination/termination', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }


    public function terminationList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_terminations';
            $this->datatables->join_table = array('tbl_account_details', 'tbl_customer_group');
            $this->datatables->join_where = array('tbl_terminations.employee_id=tbl_account_details.user_id', 'tbl_customer_group.customer_group_id=tbl_terminations.termination_type');
            $this->datatables->select = 'tbl_terminations.*,tbl_account_details.fullname,tbl_customer_group.customer_group';
            $action_array = array('tbl_terminations.id');
            $main_column = array('termination_type', 'notice_date', 'termination_date',  'tbl_account_details.fullname', 'tbl_customer_group.customer_group');
            $result = array_merge($main_column, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('id' => 'desc');
            $fetch_data = make_datatables();
            $edited = can_action('100', 'edited');
            $deleted = can_action('100', 'deleted');
            $data = array();
            foreach ($fetch_data as $_key => $v_terminations) {
                $action = null;
                $sub_array = array();
                $sub_array[] = $v_terminations->fullname;
                $sub_array[] = $v_terminations->customer_group;
                $sub_array[] = display_date($v_terminations->notice_date);
                $sub_array[] = display_date($v_terminations->termination_date);
                $action .= btn_view_modal('admin/termination/termination_details/' . $v_terminations->id) . ' ';
                if (!empty($edited)) {
                    $action .= '<a href="' . base_url() . 'admin/termination/new_termination/' . $v_terminations->id . '"
                               class="btn btn-primary btn-xs" title="' . lang('edit') . '" data-toggle="modal"
                               data-target="#myModal_lg"><span class="fa fa-pencil-square-o"></span></a>  ';
                }
                if (!empty($deleted)) {
                    $action .= ajax_anchor(base_url('admin/termination/delete_termination/' . $v_terminations->id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function termination_details($id = null)
    {
        $data['title'] = lang('termination_details'); //Page title
        $data['termination_details'] = join_data('tbl_terminations', 'tbl_terminations.*,tbl_account_details.fullname,tbl_customer_group.customer_group', array('id' => $id), array('tbl_account_details' => 'tbl_account_details.user_id = tbl_terminations.employee_id', 'tbl_customer_group' => 'tbl_customer_group.customer_group_id = tbl_terminations.termination_type'));
        if (empty($data['termination_details'])) {
            $type = "error";
            $message = lang("no_record_found");
            set_message($type, $message);
            redirect('admin/termination');
        }
        $data['subview'] = $this->load->view('admin/termination/termination_details', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data);
    }
    public function new_termination($id = NULL)
    {
        $data['title'] = lang('termination'); //Page title
        if (!empty($id)) {
            $edited = can_action('100', 'edited');
            if (!empty($edited)) {
                $data['terminations'] = $this->db->where('id', $id)->get('tbl_terminations')->row();
            }
            if (empty($data['terminations'])) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/termination/new_termination');
            }
        }
        $this->load->model('attendance_model');
        $data['all_employee'] = $this->attendance_model->get_all_employee();
        $data['subview'] = $this->load->view('admin/termination/new_termination', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function termination_type($id = NULL, $opt = null)
    {
        $data['title'] = lang('termination_type');
        if (!empty($id)) {
            if ($id == 'termination') {
                $data['active'] = 3;
                $data['termination'] = $this->termination_model->check_by(array('customer_group_id' => $opt), 'tbl_customer_group');
            }
        }
        $data['subview'] = $this->load->view('admin/termination/termination_type', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    public function saved_termination_type($id = null)
    {
        $this->termination_model->_table_name = 'tbl_customer_group';
        $this->termination_model->_primary_key = 'customer_group_id';
        $cate_data['customer_group'] = $this->input->post('customer_group', TRUE);
        $cate_data['description'] = $this->input->post('description', TRUE);
        $cate_data['type'] = 'termination';

        $where = array('customer_group' => $cate_data['customer_group']);
        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $user_id = array('customer_group_id !=' => $id);
        } else { // if id is not exist then set id as null
            $user_id = null;
        }
        // check whether this input data already exist or not
        $check_users = $this->termination_model->check_update('tbl_customer_group', $where, $user_id);
        if (!empty($check_users)) { // if input data already exist show error alert
            // massage for user
            $type = 'error';
            $msg = lang('termination_type_already_exist');
        } else {
            $id = $this->termination_model->save($cate_data, $id);
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('termination_type_added'),
                'value1' => $cate_data['customer_group']
            );
            $this->termination_model->_table_name = 'tbl_activities';
            $this->termination_model->_primary_key = 'activities_id';
            $this->termination_model->save($activity);
            // messages for user
            $type = "success";
            $msg = lang('termination_type_added');
            
        }
        $message = $msg;
        set_message($type, $message);
        redirect('admin/termination/termination_type');

    }

    public function save_termination($id = NULL)
    {
        $created = can_action('100', 'created');
        $edited = can_action('100', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $data = $this->termination_model->array_from_post(array(
                'employee_id',
                'termination_type',
                'notice_date',
                'termination_date',
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
            $check_users = $this->termination_model->check_update('tbl_terminations', $where, $user_id);
            if (!empty($check_users)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = lang('termination_already_exist');
            } else {
                $upload_file = array();
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
                                $up_data = array(
                                    "fileName" => $new_file_name,
                                    "path" => "uploads/" . $new_file_name,
                                    "fullPath" => getcwd() . "/uploads/" . $new_file_name,
                                    "ext" => '.' . end($file_ext),
                                    "size" => round($size, 2),
                                    "is_image" => $is_image,
                                );
                                array_push($upload_file, $up_data);
                            }
                        }
                    }
                }

                $fileName = $this->input->post('fileName', true);
                $path = $this->input->post('path', true);
                $fullPath = $this->input->post('fullPath', true);
                $size = $this->input->post('size', true);
                $is_image = $this->input->post('is_image', true);

                if (!empty($fileName)) {
                    foreach ($fileName as $key => $name) {
                        $old['fileName'] = $name;
                        $old['path'] = $path[$key];
                        $old['fullPath'] = $fullPath[$key];
                        $old['size'] = $size[$key];
                        $old['is_image'] = $is_image[$key];

                        array_push($upload_file, $old);
                    }
                }
                if (!empty($upload_file)) {
                    $data['attachment'] = json_encode($upload_file);
                } else {
                    $data['attachment'] = null;
                }

                $this->termination_model->_table_name = "tbl_terminations"; // table name
                $this->termination_model->_primary_key = "id"; // $id
                $return_id = $this->termination_model->save($data, $id);
                $get_user = get_row('tbl_users', array('user_id' => $data['employee_id']));
                $this->termination_model->_table_name = "tbl_users"; //table name
                $this->termination_model->_primary_key = 'user_id';
                $uDate['activated'] = 0;
                $this->termination_model->save($uDate, $get_user->user_id);

                if (!empty($id)) {
                    $activity = 'activity_update_termination';
                    $msg = lang('termination_information_update');
                } else {
                    $activity = 'activity_added_termination';
                    $msg = lang('termination_information_saved');
                }
                // save into activities
                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'termination',
                    'module_field_id' => $id,
                    'activity' => $activity,
                    'icon' => 'fa-ticket',
                    'value1' => $data['title'],
                );

                // Update into tbl_project
                $this->termination_model->_table_name = "tbl_activities"; //table name
                $this->termination_model->_primary_key = "activities_id";
                $this->termination_model->save($activities);

                // messages for user
                $type = "success";
            }
            $message = $msg;
            set_message($type, $message);
        }
        redirect('admin/termination');
    }
    public function download_files($id, $key)
    {
        $this->load->helper('download');
        $file_info = $this->termination_model->check_by(array('id' => $id), 'tbl_terminations');
        $attachment = json_decode($file_info->attachment);
        // get array value from $attachment array
        $values = array_values((array)$attachment);
        // get file name from array value
        $file_name = $values[$key];
        $path = $file_name->path;
        // check file is exist or not
        if (file_exists('./' . $path)) {
            $data = file_get_contents('./' . $path); // Read the file's contents
            force_download($file_name->fileName, $data);
        } else {
            $type = 'error';
            $message = lang('operation_failed');
            set_message($type, $message);
            redirect('admin/termination');
        }
    }
    public function delete_termination_type($id)
    {
        $customer_group = $this->termination_model->check_by(array('customer_group_id' => $id), 'tbl_customer_group');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('activity_delete_termination'),
            'value1' => $customer_group->customer_group,
        );
        $this->termination_model->_table_name = 'tbl_activities';
        $this->termination_model->_primary_key = 'activities_id';
        $this->termination_model->save($activity);
        $this->termination_model->_table_name = 'tbl_customer_group';
        $this->termination_model->_primary_key = 'customer_group_id';
        $this->termination_model->delete($id);
        // messages for user
        $type = "success";
        $message = lang('termination_deleted');
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
    public function delete_termination($id = NULL)
    {
        $deleted = can_action('100', 'deleted');
        if (!empty($deleted)) {
            $termination_info = $this->termination_model->check_by(array('id' => $id), 'tbl_terminations');
            if (empty($termination_info)) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/termination');
            }
            $get_user = get_row('tbl_users', array('user_id' => $termination_info->employee_id));
            $resignationInfo = get_row('tbl_resignations', array('employee_id' => $termination_info->employee_id));
            if (!empty($get_user) && empty($resignationInfo)) {

                $this->termination_model->_table_name = "tbl_users"; //table name
                $this->termination_model->_primary_key = 'user_id';
                $uDate['activated'] = 1;
                $this->termination_model->save($uDate, $get_user->user_id);
            }
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'termination',
                'module_field_id' => $id,
                'activity' => 'activity_delete_termination',
                'icon' => 'fa-ticket',
                'value1' => $termination_info->title,
            );
            // Update into tbl_project
            $this->termination_model->_table_name = "tbl_activities"; //table name
            $this->termination_model->_primary_key = "activities_id";
            $this->termination_model->save($activities);
            $this->termination_model->_table_name = "tbl_terminations";
            $this->termination_model->_primary_key = "id";
            $this->termination_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('terminations_information_delete');
        }
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
}
