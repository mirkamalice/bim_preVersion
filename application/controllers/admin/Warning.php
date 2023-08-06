<?php

use Paddle\Alert;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Warning extends Admin_Controller
{
    // promotion
    public function __construct()
    {
        parent::__construct();
        $this->load->model('warning_model');
    }
    public function index($id = NULL)
    {
        $data['title'] =  lang('warning');
        if ($id) {
            $data['warnings'] = $this->db->where('id', $id)->get('tbl_warnings')->row();
            if (empty($data['warnings'])) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/warning');
            }
        }
        $data['subview'] = $this->load->view('admin/warning/warning', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function warningList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_warnings';
            $this->datatables->join_table = array('tbl_account_details', 'tbl_customer_group');
            $this->datatables->join_where = array('tbl_warnings.warning_by=tbl_account_details.user_id', 'tbl_customer_group.customer_group_id=tbl_warnings.warning_type');
            $this->datatables->select = 'tbl_warnings.*,tbl_account_details.fullname,tbl_customer_group.customer_group';
            $action_array = array('tbl_warnings.id');
            $main_column = array('subject', 'warning_date', 'description', 'tbl_account_details.fullname');
            $result = array_merge($main_column, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('id' => 'desc');
            $fetch_data = make_datatables();
            $edited = can_action('100', 'edited');
            $deleted = can_action('100', 'deleted');
            $data = array();
            foreach ($fetch_data as $_key => $v_warnings) {
                $action = null;
                $sub_array = array();
                $sub_array[] = fullname($v_warnings->warning_by);
                $sub_array[] = fullname($v_warnings->warning_to);
                $sub_array[] = $v_warnings->customer_group;
                $sub_array[] = $v_warnings->subject;
                $sub_array[] = display_date($v_warnings->warning_date);
                $action .= btn_view_modal('admin/warning/warning_details/' . $v_warnings->id) . ' ';
                if (!empty($edited)) {
                    $action .= '<a href="' . base_url() . 'admin/warning/new_warning/' . $v_warnings->id . '"
                               class="btn btn-primary btn-xs" title="' . lang('edit') . '" data-toggle="modal"
                               data-target="#myModal_lg"><span class="fa fa-pencil-square-o"></span></a>  ';
                }
                if (!empty($deleted)) {
                    $action .= ajax_anchor(base_url('admin/warning/delete_warning/' . $v_warnings->id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }

            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function warning_details($id = null)
    {
        $data['title'] = lang('warning_details'); //Page title
        $data['warning_details'] = join_data('tbl_warnings', 'tbl_warnings.*,tbl_account_details.fullname,tbl_customer_group.customer_group', array('id' => $id), array('tbl_account_details' => 'tbl_account_details.user_id = tbl_warnings.warning_by', 'tbl_customer_group' => 'tbl_customer_group.customer_group_id = tbl_warnings.warning_type'));
        if (empty($data['warning_details'])) {
            $type = "error";
            $message = lang("no_record_found");
            set_message($type, $message);
            redirect('admin/warning');
        }
        $data['subview'] = $this->load->view('admin/warning/warning_details', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data);
    }

    public function new_warning($id = null)
    {
        $data['title'] = lang('warning'); //Page title
        if (!empty($id)) {
            $edited = can_action('100', 'edited');
            if (!empty($edited)) {
                $data['warnings'] = $this->db->where('id', $id)->get('tbl_warnings')->row();
            }
            if (empty($data['warnings'])) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/warning/new_warning');
            }
        }
        $this->load->model('attendance_model');
        $data['all_employee'] = $this->attendance_model->get_all_employee();
        $data['subview'] = $this->load->view('admin/warning/new_warning', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function warning_type($id = NULL, $opt = null)
    {
        $data['title'] = lang('warning_type');
        if (!empty($id)) {
            if ($id == 'warning') {
                $data['active'] = 3;
                $data['warning'] = $this->warning_model->check_by(array('customer_group_id' => $opt), 'tbl_customer_group');
            }
        }
        $data['subview'] = $this->load->view('admin/warning/warning_type', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    // warning_type
    public function saved_warning_type($id = null)
    {
        $this->warning_model->_table_name = 'tbl_customer_group';
        $this->warning_model->_primary_key = 'customer_group_id';
        $cate_data['customer_group'] = $this->input->post('customer_group', TRUE);
        $cate_data['description'] = $this->input->post('description', TRUE);
        $cate_data['type'] = 'warning';

        $where = array('customer_group' => $cate_data['customer_group']);
        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $user_id = array('customer_group_id !=' => $id);
        } else { // if id is not exist then set id as null
            $user_id = null;
        }
        // check whether this input data already exist or not
        $check_users = $this->warning_model->check_update('tbl_customer_group', $where, $user_id);

        if (!empty($check_users)) { // if input data already exist show error alert
            // massage for user
            $type = 'error';
            $msg = lang('warnig_employee_already_exist');
        } else {
        $id = $this->warning_model->save($cate_data, $id);
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('warning_type_added'),
            'value1' => $cate_data['customer_group']
        );
        $this->warning_model->_table_name = 'tbl_activities';
        $this->warning_model->_primary_key = 'activities_id';
        $this->warning_model->save($activity);
        // messages for user
        $type = "success";
        if (!empty($id)) {
            $msg = lang('warning_type_update');
        }
        $msg = lang('warning_type_added');
        
    }
        $message = $msg;
        set_message($type, $message);
        redirect('admin/warning/warning_type');
    }

    public function save_warning($id = NULL)
    {
        $created = can_action('100', 'created');
        $edited = can_action('100', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $data = $this->warning_model->array_from_post(array(
                'warning_by',
                'warning_to',
                'warning_type',
                'subject',
                'warning_date',
                'description',
            ));
            if ($data['warning_by'] == $data['warning_to']) {
                $msg = lang("dont_same_as_warning_by_and_warning_to_name");
                $type = "error";
                $message = $msg;
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
                $this->warning_model->_table_name = "tbl_warnings"; // table name
                $this->warning_model->_primary_key = "id"; // $id
                $return_id = $this->warning_model->save($data, $id);
                $this->send_promotions_email($data);
                $get_user = get_row('tbl_account_details', array('user_id' => $data['warning_to']));
                $notifyUser = array($get_user->user_id);
                if (!empty($notifyUser)) {
                    foreach ($notifyUser as $v_user) {
                        if (!empty($v_user)) {
                            if ($v_user != $this->session->userdata('user_id')) {
                                add_notification(array(
                                    'to_user_id' => $v_user,
                                    // 'description' => 'not_leave_request',
                                    'description' => 'warning',
                                    'icon' => 'clock-o',
                                    'link' => 'admin/warning/warning_details/' . $id,
                                    'value' => lang('by') . ' ' . $get_user->fullname,
                                ));
                            }
                        }
                    }
                }
                if (!empty($notifyUser)) {
                    show_notification($notifyUser);
                }
                // save into activities
                if (!empty($id)) {
                    $activity = 'activity_update_warning';
                }
                $activity = 'activity_added_warning';
                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'warning',
                    'module_field_id' => $id,
                    'activity' => $activity,
                    'icon' => 'fa-ticket',
                    'value1' => $data['subject'],
                );
                // Update into tbl_project
                $this->warning_model->_table_name = "tbl_activities"; //table name
                $this->warning_model->_primary_key = "activities_id";
                $this->warning_model->save($activities);
                // messages for user
                $type = "success";
                $msg = lang('warning_information_saved');
                if (!empty($id)) {
                    $msg = lang('warning_information_update');
                }
                $message = $msg;
            }

            set_message($type, $message);
        }
        redirect('admin/warning');
    }

    public function download_files($id, $key)
    {
        $this->load->helper('download');
        $file_info = $this->warning_model->check_by(array('id' => $id), 'tbl_warnings');
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
            redirect('admin/warning');
        }
    }
    public function delete_warning_type($id)
    {
        $customer_group = $this->warning_model->check_by(array('customer_group_id' => $id), 'tbl_customer_group');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('activity_delete_warning'),
            'value1' => $customer_group->customer_group,
        );
        $this->warning_model->_table_name = 'tbl_activities';
        $this->warning_model->_primary_key = 'activities_id';
        $this->warning_model->save($activity);

        $this->warning_model->_table_name = 'tbl_customer_group';
        $this->warning_model->_primary_key = 'customer_group_id';
        $this->warning_model->delete($id);
        // messages for user
        $type = "success";
        $message = lang('warning_deleted');
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
    public function send_promotions_email($data)
    {
        $all_users = get_row('tbl_account_details', array('user_id' => $data['warning_by']));
        $users_email = get_row('tbl_users', array('user_id' => $data['warning_by']));
        $warnings = get_row('tbl_warnings', array('id' => $data['warning_to']));
        $warning_email = config_item('warning_email');
        if (!empty($warning_email) && $warning_email == 1) {
            $email_template = email_templates(array('email_group' => 'warning_email'));
            $message = $email_template->template_body;
            $subject = $email_template->subject;
            $title = str_replace("{NAME}", $all_users->fullname, $message);
            $designation = str_replace("{DESCRIPTION}", $warnings->description, $title);
            $message = str_replace("{SITE_NAME}", config_item('company_name'), $designation);
            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);
            $params['subject'] = $subject;
            $params['message'] = $message;
            $params['resourceed_file'] = '';
            $params['recipient'] = $users_email->email;
            $this->warning_model->send_email($params);
        }
        return true;
    }
    public function delete_warning($id = NULL)
    {
        $deleted = can_action('100', 'deleted');
        if (!empty($deleted)) {
            $warnings_info = $this->warning_model->check_by(array('id' => $id), 'tbl_warnings');
            if (empty($warnings_info)) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/warning');
            }
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'warning',
                'module_field_id' => $id,
                'activity' => 'activity_delete_warning',
                'icon' => 'fa-ticket',
                'value1' => $warnings_info->title,
            );
            // Update into tbl_project
            $this->warning_model->_table_name = "tbl_activities"; //table name
            $this->warning_model->_primary_key = "activities_id";
            $this->warning_model->save($activities);
            $this->warning_model->_table_name = "tbl_warnings";
            $this->warning_model->_primary_key = "id";
            $this->warning_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('warnings_information_delete');
        }
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
}
