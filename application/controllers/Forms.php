<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Forms extends MY_Controller
{
    public function index()
    {
        show_404();
    }

    /**
     * Web to lead form
     * User no need to see anything like LEAD in the url, this is the reason the method is named wtl
     * @param string $key web to lead form key identifier
     * @return mixed
     */
    public function leads($key)
    {
        $this->load->model('items_model');
        $form = get_row('tbl_lead_form', ['form_key' => $key]);

        if (!$form) {
            show_404();
        }
        $data['form_fields'] = json_decode($form->form_data);
        if (!$data['form_fields']) {
            $data['form_fields'] = [];
        }
        //logo Process
        if ($this->input->post('key')) {
            if ($this->input->post('key') == $key) {

                $post_data = $this->input->post();

                $required = [];

                foreach ($data['form_fields'] as $field) {
                    if (isset($field->required)) {
                        $required[] = $field->name;
                    }
                }

                foreach ($required as $field) {
                    if ($field == 'file-input') {
                        continue;
                    }
                    if (!isset($post_data[$field]) || isset($post_data[$field]) && empty($post_data[$field])) {
                        $this->output->set_status_header(422);
                        die;
                    }
                }

                if (config_item('recaptcha_secret_key') != '' && config_item('recaptcha_site_key') != '' && $form->form_recaptcha == 1) {
                    if (!do_recaptcha_validation($post_data['g-recaptcha-response'])) {
                        set_message('error', lang('recaptcha_error'));
                        if (empty($_SERVER['HTTP_REFERER'])) {
                            redirect('forms/leads/' . $key);
                        } else {
                            redirect($_SERVER['HTTP_REFERER']);
                        }
                    }
                }

                if (isset($post_data['g-recaptcha-response'])) {
                    unset($post_data['g-recaptcha-response']);
                }

                unset($post_data['key']);

                $regular_fields = [];
                $custom_fields = [];
                foreach ($post_data as $name => $val) {
                    if (strpos($name, 'form-cf-') !== false) {
                        array_push($custom_fields, [
                            'name' => $name,
                            'value' => $val,
                        ]);
                    } else {
                        if ($this->db->field_exists($name, 'tbl_leads')) {
                            $regular_fields[$name] = $val;
                        }
                    }
                }

                $success = false;
                $insert_to_db = true;

                if ($form->allow_duplicate == 0) {
                    $where = [];
                    if (!empty($form->track_duplicate_field) && isset($regular_fields[$form->track_duplicate_field])) {
                        $where[$form->track_duplicate_field] = $regular_fields[$form->track_duplicate_field];
                    }
                    if (count(array($where)) > 0) {
                        $total = total_rows('tbl_leads', $where);

                        $duplicateLead = false;
                        /**
                         * Check if the lead is only 1 time duplicate
                         * Because we wont be able to know how user is tracking duplicate and to send the email template for
                         * the request
                         */
                        if ($total == 1) {
                            $this->db->where($where);
                            $duplicateLead = $this->db->get('tbl_leads')->row();
                            $msg = "<strong style='color:#000'>" . $duplicateLead->lead_name . '</strong>  ' . lang('already_exist');
                            set_message('error', $msg);
                            if (empty($_SERVER['HTTP_REFERER'])) {
                                redirect('forms/leads/' . $key);
                            } else {
                                redirect($_SERVER['HTTP_REFERER']);
                            }
                        }

                        if ($total > 0) {
                            // Success set to true for the response.
                            $success = true;
                            $insert_to_db = false;
                        }
                    }
                }

                if ($insert_to_db == true) {
                    $regular_fields['lead_status_id'] = $form->lead_status_id;
                    if ((isset($regular_fields['lead_name']) && empty($regular_fields['lead_name'])) || !isset($regular_fields['lead_name'])) {
                        $regular_fields['lead_name'] = 'Unknown';
                    }
                    $regular_fields['lead_source_id'] = $form->lead_source_id;
                    $regular_fields['last_contact'] = null;
                    $regular_fields['permission'] = $form->permission;
                    $regular_fields['created_time'] = date('Y-m-d H:i:s');
                    $regular_fields['from_form_id'] = $form->lead_form_id;

                    $this->items_model->_table_name = 'tbl_leads';
                    $this->items_model->_primary_key = 'leads_id';
                    $lead_id = $this->items_model->save($regular_fields);

                    $success = false;
                    if ($lead_id) {
                        $success = true;
                        if (!empty($_FILES['file-input']['name'])) {
                            $adata['title'] = $regular_fields['lead_name'] . ' ' . 'attachments';
                            $adata['description'] = '';
                            $adata['leads_id'] = $lead_id;
                            $adata['user_id'] = $this->session->userdata('user_id');
                            $val = $this->items_model->uploadImage('file-input');
                            $val == TRUE || redirect(base_url());

                            $this->items_model->_table_name = "tbl_attachments"; //table name
                            $this->items_model->_primary_key = "attachments_id";
                            $inserted_id = $this->items_model->save($adata);

                            if (!empty($inserted_id)) {
                                $up_data = array(
                                    "files" => $val['path'],
                                    "uploaded_path" => $val['fullPath'],
                                    "file_name" => $val['fileName'],
                                    "size" => $val['size'],
                                    "ext" => $val['ext'],
                                    "is_image" => $val['is_image'],
                                    "image_width" => (!empty($val['image_width'])) ? $val['image_width'] : 0,
                                    "image_height" => (!empty($val['image_height'])) ? $val['image_height'] : 0,
                                    "attachments_id" => $inserted_id,
                                );
                                $this->items_model->_table_name = "tbl_attachments_files"; // table name
                                $this->items_model->_primary_key = "uploaded_files_id"; // $id
                                $uploaded_files_id = $this->items_model->save($up_data);

                                // saved into comments
                                $comment = $regular_fields['lead_name'] . ' ' . 'Added new attachments';
                                if (!empty($comment)) {
                                    $u_cdata = array(
                                        "comment" => $comment,
                                        "leads_id" => $lead_id,
                                        "user_id" => $this->session->userdata('user_id'),
                                        "uploaded_files_id" => $uploaded_files_id,
                                    );
                                    $this->items_model->_table_name = "tbl_task_comment"; // table name
                                    $this->items_model->_primary_key = "task_comment_id"; // $id
                                    $this->items_model->save($u_cdata);
                                }
                            }
                        }

                        $activity = array(
                            'user' => '0',
                            'module' => 'leads',
                            'module_field_id' => $lead_id,
                            'activity' => 'activity_added_leads_from_lead_from',
                            'icon' => 'fa-rocket',
                            'link' => 'admin/leads/leads_details/' . $lead_id,
                            'value1' => $regular_fields['lead_name']
                        );
                        $this->items_model->_table_name = 'tbl_activities';
                        $this->items_model->_primary_key = 'activities_id';
                        $this->items_model->save($activity);

                        if ($form->notify_lead_imported != 0) {
                            $notifiedUsers = array();
                            if (!empty($form->permission) && $form->permission != 'all') {
                                $permissionUsers = json_decode($form->permission);
                                foreach ($permissionUsers as $user => $v_permission) {
                                    array_push($notifiedUsers, $user);
                                }
                            } else {
                                $notifiedUsers = $this->items_model->allowed_user_id('55');
                            }
                            if (!empty($notifiedUsers)) {
                                foreach ($notifiedUsers as $users) {
                                    add_notification(array(
                                        'to_user_id' => $users,
                                        'from_user_id' => true,
                                        'description' => 'not_added_leads_from_lead_from',
                                        'link' => 'admin/leads/leads_details/' . $lead_id,
                                        'value' => lang('lead') . ' ' . $regular_fields['lead_name'],
                                    ));
                                }
                                show_notification($notifiedUsers);
                            }
                        }
                    }
                } // end insert_to_db

                if ($success == true) {
                    set_message('success', $form->submit_btn_msg);
                } else {
                    set_message('error', 'there is an issue on your form');
                }
                if (empty($_SERVER['HTTP_REFERER'])) {
                    redirect('forms/leads/' . $key);
                } else {
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }

        $data['form'] = $form;
        $this->load->view('forms/lead_form', $data);
    }
}
