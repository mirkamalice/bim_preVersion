<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Promotion extends Admin_Controller
{
    // promotion
    public function __construct()
    {
        parent::__construct();
        $this->load->model('promotion_model');
    }
    public function index($id = NULL)
    {
        $data['title'] = lang('promotion');
        if ($id) {
            $data['promotions'] = $this->db->where('id', $id)->get('tbl_promotions')->row();
            if (empty($data['promotions'])) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/promotion');
            }
        }
        $data['subview'] = $this->load->view('admin/promotion/promotion', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function promotionList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_promotions';
            $this->datatables->join_table = array('tbl_account_details', 'tbl_designations');
            $this->datatables->join_where = array('tbl_promotions.user_id=tbl_account_details.user_id', 'tbl_account_details.designations_id=tbl_designations.designations_id ');
            $action_array = array('tbl_promotions.id');
            $main_column = array('promotion_title', 'description', 'promotion_date', 'tbl_designations.designations', 'tbl_account_details.fullname',);
            $result = array_merge($main_column,  $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('id' => 'desc');
            $fetch_data = make_datatables();
            $edited = can_action('100', 'edited');
            $deleted = can_action('100', 'deleted');
            $data = array();
            foreach ($fetch_data as $_key => $v_promotions) {
                $action = null;
                $sub_array = array();
                $sub_array[] = $v_promotions->fullname;
                $sub_array[] = $v_promotions->designations;
                $sub_array[] = $v_promotions->promotion_title;
                $sub_array[] = display_date($v_promotions->promotion_date);
                $action .= btn_view_modal('admin/promotion/promotion_details/' . $v_promotions->id) . ' ';
                if (!empty($edited)) {
                    $action .= '<a href="' . base_url() . 'admin/promotion/new_promotion/' . $v_promotions->id . '"
                               class="btn btn-primary btn-xs" title="' . lang('edit') . '" data-toggle="modal"
                               data-target="#myModal_lg"><span class="fa fa-pencil-square-o"></span></a>  ';
                }
                if (!empty($deleted)) {
                    $action .= ajax_anchor(base_url('admin/promotion/delete_promotion/' . $v_promotions->id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/promotion');
        }
    }


    public function new_promotion($id = null)
    {
        $data['title'] = lang('promotion'); //Page title
        if (!empty($id)) {
            $edited = can_action('100', 'edited');
            if (!empty($edited)) {
                $data['promotions'] = $this->db->where('id', $id)->get('tbl_promotions')->row();
            }
            if (empty($data['promotions'])) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/promotion/new_promotion');
            }
        }
        $this->load->model('job_circular_model');
        $data['all_dept_info'] = $this->db->get('tbl_departments')->result();
        foreach ($data['all_dept_info'] as $v_dept_info) {
            $data['all_department_info'][] = $this->job_circular_model->get_add_department_by_id($v_dept_info->departments_id);
        }
        $data['all_employee'] = $this->promotion_model->get_all_employee();
        $data['subview'] = $this->load->view('admin/promotion/new_promotion', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }
    public function save_promotion($id = NULL)
    {
        $created = can_action('100', 'created');
        $edited = can_action('100', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $data = $this->promotion_model->array_from_post(array(
                'user_id',
                'designation_id',
                'promotion_title',
                'promotion_date',
                'description',
            ));
            $get_user = get_row('tbl_account_details', array('user_id' => $data['user_id']));
            if (!empty($get_user->designations_id) && $data['designation_id'] == $get_user->designations_id) {
                $msg = lang('you_are_already_in_same_designations');
                $type = "error";
                $message = $msg;
            } else {
                $this->promotion_model->_table_name = "tbl_promotions"; // table name
                $this->promotion_model->_primary_key = "id"; // $id
                $return_id = $this->promotion_model->save($data, $id);
                $this->send_promotions_email($data);

                $notifyUser = array($get_user->user_id);
                if (!empty($notifyUser)) {
                    foreach ($notifyUser as $v_user) {
                        if (!empty($v_user)) {
                            if ($v_user != $this->session->userdata('user_id')) {
                                add_notification(array(
                                    'to_user_id' => $v_user,
                                    // 'description' => 'not_leave_request',
                                    'description' => 'promotions',
                                    'icon' => 'clock-o',
                                    'link' => 'admin/promotion/promotion_details/' . $id,
                                    'value' => lang('by') . ' ' . $get_user->fullname,
                                ));
                            }
                        }
                    }
                }
                if (!empty($notifyUser)) {
                    show_notification($notifyUser);
                }
                $get_user = get_row('tbl_account_details', array('user_id' => $data['user_id']));
                if (!empty($get_user)) {
                    $this->promotion_model->_table_name = "tbl_account_details"; //table name
                    $this->promotion_model->_primary_key = 'account_details_id';
                    $uDate['designations_id'] = $data['designation_id'];
                    $this->promotion_model->save($uDate, $get_user->account_details_id);
                    $this->promotion_model->_table_name = "tbl_promotions"; // table name
                    $this->promotion_model->_primary_key = "id"; // $id
                    $upData['from_designations'] = (!empty($get_user->designations_id) ? $get_user->designations_id : NULL);
                    $this->promotion_model->save($upData, $return_id);
                }
                $activity = 'activity_added_promotions';
                if (!empty($id)) {
                    $activity = 'activity_updated_promotions';
                }
                $activities = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'promotion',
                    'module_field_id' => $id,
                    'activity' => $activity,
                    'icon' => 'fa-ticket',
                );
                // Update into tbl_project
                $this->promotion_model->_table_name = "tbl_activities"; //table name
                $this->promotion_model->_primary_key = "activities_id";
                $this->promotion_model->save($activities);
                // messages for user
                $type = "success";
                $msg = lang('promotions_information_saved');
                if (!empty($id)) {
                    $msg = lang('promotions_information_update');
                }
                $message = $msg;
            }
        }
        set_message($type, $message);
        redirect('admin/promotion');
    }
    public function send_promotions_email($data)
    {
        $all_users = get_row('tbl_account_details', array('user_id' => $data['user_id']));
        $users_email = get_row('tbl_users', array('user_id' => $data['user_id']));
        $all_designations = get_row('tbl_designations', array('designations_id' => $data['designation_id']));
        $promotions_email = config_item('promotions_email');
        if (!empty($promotions_email) && $promotions_email == 1) {
            $email_template = email_templates(array('email_group' => 'promotion_email'));
            $message = $email_template->template_body;
            $subject = $email_template->subject;
            $title = str_replace("{NAME}", $all_users->fullname, $message);
            $designation = str_replace("{DESIGNATIONS}", $all_designations->designations, $title);
            $message = str_replace("{SITE_NAME}", config_item('company_name'), $designation);
            $data['message'] = $message;
            $message = $this->load->view('email_template', $data, TRUE);
            $params['subject'] = $subject;
            $params['message'] = $message;
            $params['resourceed_file'] = '';
            $params['recipient'] = $users_email->email;
            $this->promotion_model->send_email($params);
        }
        return true;
    }
    public function promotion_details($id = null)
    {
        $data['title'] = lang('promotion_details'); //Page title
        $data['promotion_details'] = join_data('tbl_promotions', '*', array('id' => $id), array('tbl_account_details' => 'tbl_account_details.user_id = tbl_promotions.user_id'));
        $this->load->model('job_circular_model');
        $data['all_dept_info'] = get_row('tbl_designations', array('designations_id' => $data['promotion_details']->designations_id));
        if (empty($data['promotion_details'])) {
            $type = "error";
            $message = lang("no_record_found");
            set_message($type, $message);
            redirect('admin/promotion');
        }
        $data['subview'] = $this->load->view('admin/promotion/promotion_details', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data);
    }

    public function delete_promotion($id = NULL)
    {
        $deleted = can_action('100', 'deleted');
        if (!empty($deleted)) {
            $promotions_info = $this->promotion_model->check_by(array('id' => $id), 'tbl_promotions');
            if (empty($promotions_info)) {
                $type = "error";
                $message = lang("no_record_found");
                set_message($type, $message);
                redirect('admin/promotion');
            }
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'promotion',
                'module_field_id' => $id,
                'activity' => 'activity_delete_promotion',
                'icon' => 'fa-ticket',
                'value1' => $promotions_info->title,
            );
            // Update into tbl_project
            $this->promotion_model->_table_name = "tbl_activities"; //table name
            $this->promotion_model->_primary_key = "activities_id";
            $this->promotion_model->save($activities);
            $this->promotion_model->_table_name = "tbl_promotions";
            $this->promotion_model->_primary_key = "id";
            $this->promotion_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('promotion_information_delete');
        }
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
}
