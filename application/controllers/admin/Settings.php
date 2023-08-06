<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        ini_set('max_input_vars', '3000');
        $this->load->model('settings_model');
        $this->load->model('admin_model');
        $this->auth_key = config_item('api_key'); // Set our API KEY

        $this->load->helper('ckeditor');
        $this->data['ckeditor'] = array(
            'id' => 'ck_editor',
            'path' => 'asset/js/ckeditor',
            'config' => array(
                'toolbar' => "Full",
                'width' => "100%",
                'height' => "400px"
            )
        );
        $this->language_files = $this->settings_model->all_files();
    }

    public function index()
    {
        $settings = $this->input->get('settings', TRUE) ? $this->input->get('settings', TRUE) : 'general';
        $data['title'] = lang('company_details'); //Page title
        $can_do = can_do(111);
        if (!empty($can_do)) {
            $data['load_setting'] = $settings;
        } else {
            $data['load_setting'] = 'not_found';
        }
        $data['page'] = lang('settings');
        $this->settings_model->_table_name = "tbl_countries"; //table name
        $this->settings_model->_order_by = "id";
        $data['countries'] = $this->settings_model->get();

        $data['translations'] = $this->settings_model->translations();
        $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load

    }

    public function save_settings()
    {

        $edited = can_action('25', 'edited');
        $created = can_action('25', 'created');


        if (!empty($created) || !empty($edited)) {
            $input_data = $this->settings_model->array_from_post(array(
                'company_name', 'company_legal_name',
                'contact_person', 'company_address', 'company_city', 'company_zip_code',
                'company_country', 'company_phone', 'company_email', 'company_domain', 'company_vat'
            ));

            foreach ($input_data as $key => $value) {
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_general_settings'),
                'value1' => $input_data['company_name']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('save_general_settings');
            set_message($type, $message);
        }
        redirect('admin/settings');
    }

    public function system()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'system';
        $data['title'] = lang('system_settings'); //Page title
        $data['languages'] = $this->settings_model->get_active_languages();
        // get all location
        $this->settings_model->_table_name = 'tbl_locales';
        $this->settings_model->_order_by = 'name';
        $data['locales'] = $this->settings_model->get();

        // get all timezone
        $data['timezones'] = $this->settings_model->timezones();
        // get all currencies
        $this->settings_model->_table_name = 'tbl_currencies';
        $this->settings_model->_order_by = 'name';
        $data['currencies'] = $this->settings_model->get();
        $can_do = can_do(112);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load

    }

    public function save_system()
    {
        $can_do = can_do(112);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array(
                'default_language', 'locale', 'office_location', 'office_lat', 'office_long', 'allowed_radius',
                'timezone', 'default_currency', 'chat_interval_time', 'default_payment_method', 'default_account', 'date_format', 'time_format', 'project_details_view', 'task_details_view', 'allow_multiple_client_in_project',
                'enable_languages', 'allow_client_registration', 'allow_apply_job_from_login', 'allow_custom_permission', 'allow_client_project', 'allow_sub_tasks', 'only_allowed_ip_can_clock', 'currency_position', 'money_format', 'decimal_separator', 'allowed_files', 'google_api_key',
                'auto_close_ticket', 'attendance_report', 'tables_pagination_limit', 'max_file_size', 'recaptcha_secret_key', 'recaptcha_site_key',
                'allow_geo_clock_in',
                'allow_weekend_excluded_from_leave',
            ));
            $client_default_menu = serialize($this->settings_model->array_from_post(array('client_default_menu')));
            $default_tax = serialize($this->input->post('default_tax', true));
            if (!empty($default_tax)) {
                $input_data['default_tax'] = $default_tax;
            } else {
                $input_data['default_tax'] = '-';
            }
            if (!empty($client_default_menu)) {
                $input_data['client_default_menu'] = $client_default_menu;
            } else {
                $input_data['client_default_menu'] = '-';
            }

            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }

                if ($key == 'default_account') {
                    if (empty($value)) {
                        $value = '1';
                    }
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
            $date_format = $this->input->post('date_format', true);
            //Set date format for date picker
            switch ($date_format) {
                case "%d-%m-%Y":
                    $picker = "dd-mm-yyyy";
                    $phptime = "d-m-Y";
                    break;
                case "%m-%d-%Y":
                    $picker = "mm-dd-yyyy";
                    $phptime = "m-d-Y";
                    break;
                case "%Y-%m-%d":
                    $picker = "yyyy-mm-dd";
                    $phptime = "Y-m-d";
                    break;
                case "%m.%d.%Y":
                    $picker = "yyyy.mm.dd";
                    $phptime = "Y.m.d";
                    break;
                case "%d.%m.%Y":
                    $picker = "dd.mm.yyyy";
                    $phptime = "d.m.Y";
                    break;
                case "%Y.%m.%d":
                    $picker = "yyyy.mm.dd";
                    $phptime = "Y.m.d";
                    break;
            }

            $this->db->where('config_key', 'date_picker_format')->update('tbl_config', array("value" => $picker));
            $this->db->where('config_key', 'date_php_format')->update('tbl_config', array("value" => $phptime));

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_system_settings'),
                'value1' => $input_data['default_language']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            // messages for user
            $type = "success";
            $message = lang('save_system_settings');
            set_message($type, $message);
        }
        redirect('admin/settings/system');
    }

    public function payments($payment = NULL)
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'payments';
        $data['title'] = "Payments"; //Page title
        $data['payment'] = $payment;
        $can_do = can_do(116);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_payments($payment)
    {
        $can_do = can_do(116);
        $edited = can_action('15', 'edited');
        $created = can_action('15', 'created');
        if (!empty($edited)) {
            $payment_info = get_row('tbl_online_payment', array('gateway_name' => $payment));
            $pdata[slug_it(strtolower($payment)) . '_status'] = $this->input->post(slug_it(strtolower($payment)) . '_status', true);
            for ($key = 1; $key <= 5; $key++) {
                $field = 'field_' . $key;
                $value = explode('|', $payment_info->$field);
                if (!empty($value[0])) {
                    $pdata[$value[0]] = $this->input->post($value[0], true);
                    if (!empty($value[1]) && $value[1] == 'password') {
                        if (!empty($pdata[$value[0]])) {
                            $pdata[$value[0]] = encrypt($pdata[$value[0]]);
                        } else {
                            unset($pdata[$value[0]]);
                        }
                    } else if (!empty($value[1]) && $value[1] == 'checkbox') {
                        if (empty($this->input->post($value[0], true))) {
                            $pdata[$value[0]] = 'FALSE';
                        }
                    }
                }
            }
            foreach ($pdata as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }

            // messages for user
            $type = "success";
            $message = lang('payment_update_success');
            set_message($type, $message);
        }
        redirect('admin/settings/payments');
    }

    public function theme()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'theme';
        $data['title'] = lang('theme_settings'); //Page title
        $can_do = can_do(120);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_theme()
    {
        $can_do = can_do(120);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array(
                'website_name', 'logo_or_icon', 'sidebar_theme', 'aside-float', 'show-scrollbar', 'aside-collapsed', 'layout-h', 'layout-boxed', 'layout-fixed', 'login_position', 'RTL',
                'active_custom_color', 'navbar_logo_background', 'top_bar_background', 'top_bar_color', 'sidebar_background', 'sidebar_color', 'sidebar_active_background', 'sidebar_active_color', 'submenu_open_background',
                'active_background', 'active_color', 'body_background', 'active_pre_loader', 'sidebar_font_size', 'body_font_size'
            ));

            if (empty($input_data['active_custom_color'])) {
                $input_data['active_custom_color'] = '0';
            }
            if (empty($input_data['RTL'])) {
                $input_data['RTL'] = 0;
            }
            //logo Process
            if (!empty($_FILES['company_logo']['name'])) {
                $val = $this->settings_model->uploadImage('company_logo');
                $val == TRUE || redirect('admin/settings/theme');
                $input_data['company_logo'] = $val['path'];
            }
            //favicon Process
            if (!empty($_FILES['favicon']['name'])) {
                $val = $this->settings_model->uploadImage('favicon');
                $val == TRUE || redirect('admin/settings/theme');
                $input_data['favicon'] = $val['path'];
            }
            if (!empty($_FILES['login_background']['name'])) {
                $val = $this->settings_model->uploadImage('login_background');
                $val == TRUE || redirect('admin/settings/theme');
                $input_data['login_background'] = $val['path'];
            }

            foreach ($input_data as $key => $value) {
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_theme_settings'),
                'value1' => $input_data['website_name']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('save_theme_settings');
            set_message($type, $message);
        }
        redirect('admin/settings/theme');
    }

    public function dashboard()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'dashboard';
        $data['title'] = lang('dashboard_settings'); //Page title
        $can_do = can_do(145);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_dashboard($d_id = null, $flag = null)
    {
        if ($this->input->is_ajax_request()) {
            $can_do = can_do(145);
            if (!empty($can_do)) {
                $report_menu_id = json_decode($this->input->post('report_menu', true));
                if (!empty($report_menu_id)) {
                    foreach ($report_menu_id as $mrkey => $r_id) {
                        $rdata['order_no'] = $mrkey + 1;
                        $this->settings_model->_table_name = 'tbl_dashboard';
                        $this->settings_model->_primary_key = 'id';
                        $this->settings_model->save($rdata, $r_id);
                    }
                }
                $menu_id = json_decode($this->input->post('menu', true));
                if (!empty($menu_id)) {
                    foreach ($menu_id as $mkey => $id) {
                        $data['order_no'] = $mkey + 1;
                        $this->settings_model->_table_name = 'tbl_dashboard';
                        $this->settings_model->_primary_key = 'id';
                        $this->settings_model->save($data, $id);
                    }
                }
                if (!empty($d_id)) {
                    $where = array('id' => $d_id);
                    if (is_numeric($flag)) {
                        $action = array('status' => $flag);
                    } else {
                        if (strpos($flag, 's_') !== false) {
                            $ex = explode('_', $flag);
                            $action = array('for_staff' => $ex[1]);
                        } else {
                            $action = array('col' => $flag);
                        }
                    }
                    $this->settings_model->set_action($where, $action, 'tbl_dashboard');
                }
            }
            $type = "success";
            $message = lang('update_settings');
            echo json_encode(array('status' => $type, 'message' => $message));
            exit();
        } else {
            redirect('admin/dashboard');
        }
    }


    public function email()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'email';
        $data['title'] = lang('email_settings'); //Page title
        $can_do = can_do(113);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function update_email()
    {
        $can_do = can_do(113);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array(
                'company_email', 'use_postmark',
                'postmark_api_key', 'postmark_from_address', 'protocol', 'smtp_host', 'smtp_user', 'smtp_port', 'smtp_encryption'
            ));

            $smtp_pass = $this->input->post('smtp_pass', true);

            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
            $smtp_pass = $this->input->post('smtp_pass', true);

            if (!empty($smtp_pass)) {
                $smtp_data['value'] = encrypt($smtp_pass);
                $this->db->where('config_key', 'smtp_pass')->update('tbl_config', $smtp_data);
                $exists = $this->db->where('config_key', 'smtp_pass')->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_email_settings'),
                'value1' => $input_data['company_email']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('save_email_settings');
            set_message($type, $message);
        }
        redirect('admin/settings/email');
    }

    public function sent_test_email()
    {
        $test_email = $this->input->post('test_email', true);
        if (!empty($test_email)) {
            $params['subject'] = 'SMTP Setup Testing';
            $params['message'] = 'This is test SMTP email. <br />If you received this message that means that your SMTP settings is Corrects.';
            $params['recipient'] = $test_email;
            $params['resourceed_file'] = '';
            $result = $this->settings_model->send_email($params, true);
            if ($result == true) {
                set_message('success', 'Seems like your SMTP settings is Corrects. Check your email now. :)');
            } else {
                $s_data['email_error'] = '<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . show_error($this->email->print_debugger());
                $this->session->set_userdate($s_data);
            }
            //            $this->email->initialize();
            //            $this->email->set_newline("\r\n");
            //            $this->email->clear();
            //            $this->email->from(config_item('company_email'), config_item('company_name'));
            //            $this->email->to($test_email);
            //            $this->email->subject('SMTP Setup Testing');
            //            $this->email->message('This is test SMTP email. <br />If you received this message that means that your SMTP settings is Corrects.');
            //            if ($this->email->send()) {
            //                set_message('success', 'Seems like your SMTP settings is Corrects. Check your email now. :)');
            //            } else {
            //                $s_data['email_error'] = '<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . show_error($this->email->print_debugger());
            //                $this->session->set_userdate($s_data);
            //
            //            }
        }
        redirect('admin/settings/email');
    }


    public function credit_note()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'credit_note';
        $data['title'] = lang('credit_note_settings'); //Page title
        $can_do = can_do(118);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_credit_note()
    {
        $can_do = can_do(118);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('credit_note_prefix', 'credit_note_start_no', 'credit_note_number_format', 'show_credit_note_tax', 'increment_credit_note_number', 'credit_note_terms', 'credit_note_footer'));
            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_credit_note_settings'),
                'value1' => $input_data['credit_note_prefix']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('save_credit_note_settings');
            set_message($type, $message);
        }
        redirect('admin/settings/credit_note');
    }


    public function estimate()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'estimate';
        $data['title'] = lang('estimate_settings'); //Page title
        $can_do = can_do(118);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_estimate()
    {
        $can_do = can_do(118);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('estimate_prefix', 'estimate_start_no', 'estimate_number_format', 'show_estimate_tax', 'increment_estimate_number', 'estimate_terms', 'estimate_footer'));
            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_estimate_settings'),
                'value1' => $input_data['estimate_prefix']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('save_estimate_settings');
            set_message($type, $message);
        }
        redirect('admin/settings/estimate');
    }

    public function proposals()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'proposals';
        $data['title'] = lang('proposals_settings'); //Page title
        $can_do = can_do(118);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_proposals()
    {
        $can_do = can_do(118);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('proposal_prefix', 'proposal_start_no', 'proposal_number_format', 'show_proposal_tax', 'increment_proposal_number', 'proposal_terms', 'proposal_footer'));
            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_proposal_settings'),
                'value1' => $input_data['proposal_prefix']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('save_proposal_settings');
            set_message($type, $message);
        }
        redirect('admin/settings/proposals');
    }

    public function invoice()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'invoice';
        $this->load->library('gst');
        $data['title'] = lang('invoice_settings'); //Page title
        $can_do = can_do(117);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }


    public function save_invoice()
    {
        $can_do = can_do(117);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('invoice_prefix', 'invoices_due_after', 'invoice_start_no', 'amount_to_words', 'amount_to_words_lowercase', 'invoice_number_format', 'qty_calculation_from_items', 'item_total_qty_alert', 'allow_customer_edit_amount', 'increment_invoice_number', 'show_invoice_tax', 'send_email_when_recur', 'default_terms', 'invoice_footer', 'invoice_view', 'gst_state', 'invoice_print_view', 'invoice_layout_view', 'invoice_layout_pdf', 'show_watermark'));
            //image Process
            if (!empty($_FILES['invoice_logo']['name'])) {
                $val = $this->settings_model->uploadImage('invoice_logo');
                $val == TRUE || redirect('admin/settings/invoice');
                $input_data['invoice_logo'] = $val['path'];
            }

            if (empty($input_data['qty_calculation_from_items'])) {
                $input_data['qty_calculation_from_items'] = 'No';
            }
            if (empty($input_data['item_total_qty_alert'])) {
                $input_data['item_total_qty_alert'] = 'No';
            }
            if (empty($input_data['allow_customer_edit_amount'])) {
                $input_data['allow_customer_edit_amount'] = 'No';
            }

            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_invoice_settings'),
                'value1' => $input_data['invoice_prefix']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('save_invoice_settings');
            set_message($type, $message);
        }
        redirect('admin/settings/invoice');
    }

    public function projects()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'projects';
        $data['title'] = lang('projects') . ' ' . lang('settings'); //Page title
        $can_do = can_do(159);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_projects()
    {
        $can_do = can_do(159);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('projects_prefix', 'projects_start_no', 'projects_number_format', 'return_stock_prefix', 'return_stock_start_no', 'return_stock_number_format', 'projects_notes'));
            //image Process
            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_projects_settings'),
                'value1' => $input_data['projects_prefix']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('update_msg', lang('projects') . ' ' . lang('settings'));
            set_message($type, $message);
        }
        redirect('admin/settings/projects');
    }

    public function award()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'award';
        $data['title'] = lang('award') . ' ' . lang('settings'); //Page title
        $can_do = can_do(155);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function awardlist()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_award_rule';
            $this->datatables->join_table = array('tbl_client');
            $this->datatables->join_where = array('tbl_award_rule.client_id=tbl_client.client_id');
            $custom_field = custom_form_table_search(15);
            $action_array = array('award_rule_id');
            $main_column = array('rule_name', 'tbl_client.name', 'award_point_from', 'award_point_to', 'card', 'date_create');
            $result = array_merge($main_column, $custom_field, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('award_rule_id ' => 'desc');
            $fetch_data = $this->datatables->get_datatable_permission();
            // print('<pre>'.print_r($fetch_data,true).'</pre>'); exit;
            $edited = $can_do = can_do(111);
            $deleted = can_do(111);
            // print('<pre>'.print_r($fetch_data,true).'</pre>'); exit;    
            $data = array();
            foreach ($fetch_data as $_key => $v_rule) {
                $profile_info = $this->db->where('client_id', $v_rule->client_id)->get('tbl_client')->row();
                $can_edit = $this->settings_model->can_action('tbl_award_rule', 'edit', array('award_rule_id' => $v_rule->award_rule_id));
                $can_delete = $this->settings_model->can_action('tbl_award_rule', 'delete', array('award_rule_id' => $v_rule->award_rule_id));
                if (!empty($profile_info->name)) {
                    $clientname = $profile_info->name;
                } else {
                    $clientname = '-';
                }

                $action = null;
                $sub_array = array();
                $sub_array[] = $v_rule->rule_name;
                $sub_array[] = $clientname;
                $sub_array[] = $v_rule->award_point_from;
                $sub_array[] = $v_rule->award_point_to;
                $sub_array[] = $v_rule->card;
                $sub_array[] = display_date($v_rule->date_create);


                if (!empty($can_edit) && !empty($edited)) {
                    $action .= '<a href="' . base_url() . 'admin/settings/new_rule/' . $v_rule->award_rule_id . '"
                               class="btn btn-primary btn-xs" title="' . lang('edit') . '" data-toggle="modal"
                               data-target="#myModal_large"><span class="fa fa-pencil-square-o"></span></a>  ';
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url('admin/settings/delete_new_rule/' . $v_rule->award_rule_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }


                $sub_array[] = $action;
                $data[] = $sub_array;
            }

            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function save_awardpoint()
    {
        $can_do = can_do(155);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('client_spent_amount', 'client_award_ponint', 'staff_spent_amount', 'staff_award_ponint'));
            //image Process
            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_award_settings'),
                'value1' => $input_data['client_award_ponint']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('update_msg', lang('purchase') . ' ' . lang('settings'));
            set_message($type, $message);
        }
        redirect('admin/settings/award');
    }

    public function purchase()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'purchase';
        $data['title'] = lang('purchase') . ' ' . lang('settings'); //Page title
        $can_do = can_do(155);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_purchase()
    {
        $can_do = can_do(155);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('purchase_prefix', 'purchase_start_no', 'purchase_number_format', 'return_stock_prefix', 'return_stock_start_no', 'return_stock_number_format', 'purchase_notes', 'update_stock_when_make_payment'));
            //image Process
            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_purchase_settings'),
                'value1' => $input_data['purchase_prefix']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('update_msg', lang('purchase') . ' ' . lang('settings'));
            set_message($type, $message);
        }
        redirect('admin/settings/purchase');
    }


    public function transfer()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'transfer';
        $data['title'] = lang('transfer') . ' ' . lang('settings'); //Page title
        $can_do = can_do(155);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_transfer()
    {
        $can_do = can_do(155);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('transfer_prefix', 'transfer_start_no', 'transfer_number_format', 'transfer_notes'));
            //image Process
            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_transfer_settings'),
                'value1' => $input_data['transfer_prefix']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('update_msg', lang('transfer') . ' ' . lang('settings'));
            set_message($type, $message);
        }
        redirect('admin/settings/transfer');
    }

    public function templates()
    {
        if ($_POST) {
            $data = array(
                'subject' => $this->input->post('subject', true),
                'template_body' => $this->input->post('email_template', true),
                'email_group' => $this->input->post('email_group', true),
                'code' => $this->input->post('code', true),
            );
            $check_group = $this->db->where(array('email_group' => $_POST['email_group'], 'code' => $_POST['code']))->get('tbl_email_templates')->row();
            if (!empty($check_group)) {
                $this->db->where(array('email_group' => $_POST['email_group'], 'code' => $_POST['code']))->update('tbl_email_templates', $data);
                $return_url = $_POST['return_url'];
                redirect($return_url);
            }

            $this->db->insert('tbl_email_templates', $data);
            $return_url = $_POST['return_url'];
            redirect($return_url);
        } else {
            $data['page'] = lang('settings');
            $data['load_setting'] = 'templates';
            $data['title'] = lang('email_templates'); //Page title
            $can_do = can_do(114);
            if (!empty($can_do)) {
                $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
            } else {
                $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
            }
            $this->load->view('admin/_layout_main', $data); //page load
        }
    }

    public function translations($lang = null)
    {
        $data['page'] = lang('settings');


        if (!empty($lang)) {
            $data['language'] = $lang;
            $data['language_files'] = $this->language_files;
        } else {
            $data['active_language'] = $this->settings_model->get_active_languages();
            $data['availabe_language'] = $this->settings_model->available_translations();
        }

        $data['translation_stats'] = $this->settings_model->translation_stats($this->language_files);

        $data['load_setting'] = 'translations';
        $data['title'] = lang('translations'); //Page title
        $can_do = can_do(137);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function translations_status($language, $status)
    {
        $data['active'] = $status;
        $this->db->where('name', $language)->update('tbl_languages', $data);
        $type = 'success';
        if ($status == 1) {
            $message = lang('language_active_successfully');
        } else {
            $message = lang('language_deactive_successfully');
        }
        set_message($type, $message);
        redirect('admin/settings/translations');
    }

    public function add_language()
    {
        $can_do = can_do(137);
        if (!empty($can_do)) {
            $language = $this->input->post('language', TRUE);
            $this->settings_model->add_language($language, $this->language_files);
            $type = 'success';
            $message = lang('language_added_successfully');
            set_message($type, $message);
        }
        redirect('admin/settings/translations');
    }

    public function edit_translations($lang, $file)
    {

        $path = $this->language_files[$file . '_lang.php'];

        $data['language'] = $lang;
        //CI will record your lang file is loaded, unset it and then you will able to load another
        //unset the lang file to allow the loading of another file
        if (isset($this->lang->is_loaded)) {
            $loaded = sizeof($this->lang->is_loaded);
            if ($loaded < 3) {
                for ($i = 3; $i <= $loaded; $i++) {
                    unset($this->lang->is_loaded[$i]);
                }
            } else {
                for ($i = 0; $i <= $loaded; $i++) {
                    unset($this->lang->is_loaded[$i]);
                }
            }
        }
        $data['english'] = $this->lang->load($file, 'english', TRUE, TRUE, $path);
        if ($lang == 'english') {
            $data['translation'] = $data['english'];
        } else {
            $data['translation'] = $this->lang->load($file, $lang, TRUE, TRUE);
        }
        $data['active_language_files'] = $file;

        $data['load_setting'] = 'translations';
        $data['current_languages'] = $lang;

        $data['title'] = "Edit Translations"; //Page title
        $can_do = can_do(137);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function set_translations()
    {
        $can_do = can_do(137);
        if (!empty($can_do)) {
            $jpost = array();
            //            $jsondata = json_decode(html_entity_decode($_POST['hello']));
            $jpost = $_POST['hello'];
            $_language = $_POST['_language'];
            $_file = $_POST['_file'];
            $jpost['_language'] = $_language;
            $jpost['_file'] = $_file;
            $result = $this->settings_model->save_translation($jpost);
            if ($result == true) {
                // messages for user
                $type = "success";
                $message = '<strong style=color:#000>' . $jpost['_language'] . '</strong>' . " Information Successfully Update!";
            } else {
                $type = "error";
                $message = '<strong style=color:#000>' . $jpost['_language'] . '</strong>' . " Make sure the permission to 0777 to the folder";
            }
            set_message($type, $message);
            redirect('admin/settings/translations/' . $_language);
        }
        redirect('admin/settings/translations');
    }

    public function income_category($action = NULL, $id = NULL)
    {
        $created = can_action('123', 'created');
        $edited = can_action('123', 'edited');

        $data['page'] = lang('settings');
        if ($action == 'edit_income_category') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['income_category_info'] = $this->settings_model->check_by(array('income_category_id' => $id), 'tbl_income_category');
            }
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('income_category');
        if ($action == 'update_income_category') {
            if (!empty($created) || !empty($edited) && !empty($id)) {
                $this->settings_model->_table_name = 'tbl_income_category';
                $this->settings_model->_primary_key = 'income_category_id';

                $cate_data['income_category'] = $this->input->post('income_category', TRUE);
                $cate_data['description'] = $this->input->post('description', TRUE);

                // update root category
                $where = array('income_category' => $cate_data['income_category']);
                // duplicate value check in DB
                if (!empty($id)) { // if id exist in db update data
                    $income_category_id = array('income_category_id !=' => $id);
                } else { // if id is not exist then set id as null
                    $income_category_id = null;
                }
                // check whether this input data already exist or not
                $check_category = $this->settings_model->check_update('tbl_income_category', $where, $income_category_id);
                if (!empty($check_category)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['income_category'] . '</strong>  ' . lang('already_exist');
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_a_income_category'),
                        'value1' => $cate_data['income_category']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = lang('income_category_added');
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/income_category');
        } else {
            $data['title'] = lang('income_category'); //Page title
            $data['load_setting'] = 'income_category';
        }

        $this->settings_model->_table_name = 'tbl_income_category';
        $this->settings_model->_order_by = 'income_category_id';
        $data['all_income_category'] = $this->settings_model->get();

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(123);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_income_category($id)
    {
        $deleted = can_action('122', 'deleted');
        if (!empty($deleted)) {
            $income_category = $this->settings_model->check_by(array('income_category_id' => $id), 'tbl_income_category');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_category'),
                'value1' => $income_category->income_category,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $this->settings_model->_table_name = 'tbl_income_category';
            $this->settings_model->_primary_key = 'income_category_id';
            $this->settings_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('income_category_deleted');
            // messages for user
            echo json_encode(array("status" => $type, 'message' => $message));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function lead_status($action = NULL, $id = NULL)
    {
        $created = can_action('127', 'created');
        $edited = can_action('127', 'edited');
        $data['page'] = lang('settings');
        if ($action == 'edit_lead_status') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['lead_status_info'] = $this->settings_model->check_by(array('lead_status_id' => $id), 'tbl_lead_status');
            }
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('lead_status');
        if ($action == 'update_lead_status') {
            if (!empty($created) || !empty($edited) && !empty($id)) {
                $this->settings_model->_table_name = 'tbl_lead_status';
                $this->settings_model->_primary_key = 'lead_status_id';

                $cate_data['lead_status'] = $this->input->post('lead_status', TRUE);
                $cate_data['lead_type'] = $this->input->post('lead_type', TRUE);
                $cate_data['order_no'] = $this->input->post('order_no', TRUE);

                // update root category
                $where = array('lead_status' => $cate_data['lead_status']);
                // duplicate value check in DB
                if (!empty($id)) { // if id exist in db update data
                    $lead_status_id = array('lead_status_id !=' => $id);
                } else { // if id is not exist then set id as null
                    $lead_status_id = null;
                }
                // check whether this input data already exist or not
                $check_lead_status = $this->settings_model->check_update('tbl_lead_status', $where, $lead_status_id);
                if (!empty($check_lead_status)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['lead_status'] . '</strong>  ' . lang('already_exist');
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_a_lead_status'),
                        'value1' => $cate_data['lead_status']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = lang('lead_status_added');
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/lead_status');
        } else {
            $data['title'] = lang('lead_status'); //Page title
            $data['load_setting'] = 'lead_status';
        }
        $data['all_lead_status'] = $this->db->get('tbl_lead_status')->result();

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(127);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_lead_status($id)
    {
        $deleted = can_action('127', 'deleted');
        if (!empty($deleted)) {
            $dept_info = $this->settings_model->check_by(array('lead_status_id' => $id), 'tbl_lead_status');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_lead_status'),
                'value1' => $dept_info->lead_status,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $this->settings_model->_table_name = 'tbl_lead_status';
            $this->settings_model->_primary_key = 'lead_status_id';
            $this->settings_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('lead_status_deleted');
            echo json_encode(array("status" => $type, 'message' => $message));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function lead_source($action = NULL, $id = NULL)
    {
        $created = can_action('128', 'created');
        $edited = can_action('128', 'edited');
        $data['page'] = lang('settings');
        if ($action == 'edit_lead_source') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['lead_source_info'] = $this->settings_model->check_by(array('lead_source_id' => $id), 'tbl_lead_source');
            }
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('lead_source');
        if ($action == 'update_lead_source') {
            if (!empty($created) || !empty($edited) && !empty($id)) {
                $this->settings_model->_table_name = 'tbl_lead_source';
                $this->settings_model->_primary_key = 'lead_source_id';

                $source_data['lead_source'] = $this->input->post('lead_source', TRUE);
                // update root category
                $where = array('lead_source' => $source_data['lead_source']);
                // duplicate value check in DB
                if (!empty($id)) { // if id exist in db update data
                    $lead_source_id = array('lead_source_id !=' => $id);
                } else { // if id is not exist then set id as null
                    $lead_source_id = null;
                }
                // check whether this input data already exist or not
                $check_lead_status = $this->settings_model->check_update('tbl_lead_source', $where, $lead_source_id);
                if (!empty($check_lead_status)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $source_data['lead_source'] . '</strong>  ' . lang('already_exist');
                } else { // save and update query
                    $id = $this->settings_model->save($source_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_a_lead_source'),
                        'value1' => $source_data['lead_source']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = lang('lead_source_added');
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/lead_source');
        } else {
            $data['title'] = lang('lead_source'); //Page title
            $data['load_setting'] = 'lead_source';
        }

        $this->settings_model->_table_name = 'tbl_lead_source';
        $this->settings_model->_order_by = 'lead_source_id';
        $data['all_lead_source'] = $this->settings_model->get();

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(128);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_lead_source($id)
    {
        $deleted = can_action('128', 'deleted');
        if (!empty($deleted)) {
            $lead_source = $this->settings_model->check_by(array('lead_source_id' => $id), 'tbl_lead_source');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_lead_source'),
                'value1' => $lead_source->lead_source,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $this->settings_model->_table_name = 'tbl_lead_source';
            $this->settings_model->_primary_key = 'lead_source_id';
            $this->settings_model->delete($id);

            // messages for user
            $type = "success";
            $message = lang('lead_source_deleted');
            // messages for user
            echo json_encode(array("status" => $type, 'message' => $message));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }


    public function opportunities_state_reason($id = NULL)
    {
        $edited = can_action('129', 'edited');

        if (!empty($id) && !empty($edited)) {
            $data['state_info'] = $this->settings_model->check_by(array('opportunities_state_reason_id' => $id), 'tbl_opportunities_state_reason');
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('lead_status');
        $data['title'] = lang('opportunities_state_reason'); //Page title
        $data['load_setting'] = 'opportunities_state_reason';
        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(129);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function update_state_reason($id = NULL)
    {
        $created = can_action('129', 'created');
        $edited = can_action('129', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $input_data = $this->settings_model->array_from_post(array('opportunities_state', 'opportunities_state_reason'));
            $this->settings_model->_table_name = 'tbl_opportunities_state_reason';
            $this->settings_model->_primary_key = 'opportunities_state_reason_id';
            $id = $this->settings_model->save($input_data, $id);
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_update_state_reason'),
                'value1' => $input_data['opportunities_state_reason'],
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $type = "success";
            $message = lang('update_state_reason_success');
            set_message($type, $message);
        }
        redirect('admin/settings/opportunities_state_reason');
    }

    public function delete_state_reason($id)
    {
        $deleted = can_action('129', 'deleted');
        if (!empty($deleted)) {
            $dept_info = $this->settings_model->check_by(array('opportunities_state_reason_id' => $id), 'tbl_opportunities_state_reason');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_state_reason'),
                'value1' => '(' . $dept_info->opportunities_state . ') ' . $dept_info->opportunities_state_reason,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $this->settings_model->_table_name = 'tbl_opportunities_state_reason';
            $this->settings_model->_primary_key = 'opportunities_state_reason_id';
            $this->settings_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('delete_state_reason_success');
            // messages for user
            echo json_encode(array("status" => $type, 'message' => $message));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function inline_payment_method()
    {
        $data['title'] = lang('payment_method');
        $data['subview'] = $this->load->view('admin/settings/inline_payment_method', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function update_payment_method($id = null)
    {
        $this->settings_model->_table_name = 'tbl_payment_methods';
        $this->settings_model->_primary_key = 'payment_methods_id';
        $cate_data['method_name'] = $this->input->post('method_name', TRUE);
        // update root category
        $where = array('method_name' => $cate_data['method_name']);
        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $payment_methods_id = array('payment_methods_id !=' => $id);
        } else { // if id is not exist then set id as null
            $payment_methods_id = null;
        }
        // check whether this input data already exist or not
        $check_category = $this->settings_model->check_update('tbl_payment_methods', $where, $payment_methods_id);
        if (!empty($check_category)) { // if input data already exist show error alert
            // massage for user
            $type = 'error';
            $msg = "<strong style='color:#000'>" . $cate_data['method_name'] . '</strong>  ' . lang('already_exist');
        } else { // save and update query
            $id = $this->settings_model->save($cate_data, $id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_added_a_payment_method'),
                'value1' => $cate_data['method_name']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            // messages for user
            $type = "success";
            $msg = lang('payment_method_added');
        }
        if (!empty($id)) {
            $result = array(
                'id' => $id,
                'method_name' => $cate_data['method_name'],
                'status' => $type,
                'message' => $msg,
            );
        } else {
            $result = array();
        }
        echo json_encode($result);
        exit();
    }


    public function payment_method($action = NULL, $id = NULL)
    {
        $data['page'] = lang('settings');
        if ($action == 'edit_payment_method') {
            $data['active'] = 2;
            if (!empty($id)) {
                $data['method_info'] = $this->settings_model->check_by(array('payment_methods_id' => $id), 'tbl_payment_methods');
            }
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('payment_method');
        if ($action == 'update_payment_method') {
            $this->settings_model->_table_name = 'tbl_payment_methods';
            $this->settings_model->_primary_key = 'payment_methods_id';
            $cate_data['method_name'] = $this->input->post('method_name', TRUE);
            // update root category
            $where = array('method_name' => $cate_data['method_name']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $payment_methods_id = array('payment_methods_id !=' => $id);
            } else { // if id is not exist then set id as null
                $payment_methods_id = null;
            }
            // check whether this input data already exist or not
            $check_category = $this->settings_model->check_update('tbl_payment_methods', $where, $payment_methods_id);
            if (!empty($check_category)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = "<strong style='color:#000'>" . $cate_data['method_name'] . '</strong>  ' . lang('already_exist');
            } else { // save and update query
                $id = $this->settings_model->save($cate_data, $id);

                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $id,
                    'activity' => ('activity_added_a_payment_method'),
                    'value1' => $cate_data['method_name']
                );
                $this->settings_model->_table_name = 'tbl_activities';
                $this->settings_model->_primary_key = 'activities_id';
                $this->settings_model->save($activity);

                // messages for user
                $type = "success";
                $msg = lang('payment_method_added');
            }
            $message = $msg;
            set_message($type, $message);
            redirect('admin/settings/payment_method');
        } else {
            $data['title'] = lang('payment_method'); //Page title
            $data['load_setting'] = 'payment_method';
        }

        $this->settings_model->_table_name = 'tbl_payment_methods';
        $this->settings_model->_order_by = 'payment_methods_id';
        $data['all_method_info'] = $this->settings_model->get();

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;
        $can_do = can_do(131);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_payment_method($id)
    {
        $method_info = $this->settings_model->check_by(array('payment_methods_id' => $id), 'tbl_payment_methods');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('activity_delete_a_method'),
            'value1' => $method_info->method_name,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        $this->settings_model->_table_name = 'tbl_payment_methods';
        $this->settings_model->_primary_key = 'payment_methods_id';
        $this->settings_model->delete($id);
        // messages for user
        $type = "success";
        $message = lang('payment_method_deleted');
        // messages for user
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }

    public function tags($action = NULL, $id = NULL)
    {
        $data['page'] = lang('settings');
        if ($action == 'edit_tags') {
            $data['active'] = 2;
            if (!empty($id)) {
                $data['method_info'] = $this->settings_model->check_by(array('tag_id' => $id), 'tbl_tags');
            }
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('tags');
        if ($action == 'update_tags') {
            $this->settings_model->_table_name = 'tbl_tags';
            $this->settings_model->_primary_key = 'tag_id';
            $cate_data['name'] = $this->input->post('name', TRUE);

            $style = '';
            if (!empty($this->input->post('border', TRUE))) {
                $style .= 'border:1px solid ' . $this->input->post('border', TRUE) . ';';
            } else {
                $style .= 'border:1px solid #dde6e9;';
            }
            if (!empty($this->input->post('background', TRUE))) {
                $style .= 'background:' . $this->input->post('background', TRUE) . ';';
            } else {
                $style .= 'background:#ffffff;';
            }
            if (!empty($this->input->post('color', TRUE))) {
                $style .= 'color:' . $this->input->post('color', TRUE);
            } else {
                $style .= 'color:#333';
            }
            $cate_data['style'] = $style;
            // update root category
            $where = array('name' => $cate_data['name']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $tag_id = array('tag_id !=' => $id);
            } else { // if id is not exist then set id as null
                $tag_id = null;
            }
            // check whether this input data already exist or not
            $check_category = $this->settings_model->check_update('tbl_tags', $where, $tag_id);
            if (!empty($check_category)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = "<strong style='color:#000'>" . $cate_data['name'] . '</strong>  ' . lang('already_exist');
            } else { // save and update query
                $id = $this->settings_model->save($cate_data, $id);

                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $id,
                    'activity' => ('activity_added_a_tags'),
                    'value1' => $cate_data['name']
                );
                $this->settings_model->_table_name = 'tbl_activities';
                $this->settings_model->_primary_key = 'activities_id';
                $this->settings_model->save($activity);

                // messages for user
                $type = "success";
                $msg = lang('tags_added');
            }
            $message = $msg;
            set_message($type, $message);
            redirect('admin/settings/tags');
        } else {
            $data['title'] = lang('tags'); //Page title
            $data['load_setting'] = 'tags';
        }

        $this->settings_model->_table_name = 'tbl_tags';
        $this->settings_model->_order_by = 'tag_id';
        $data['all_method_info'] = $this->settings_model->get();

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;
        $can_do = can_do(131);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_tags($id)
    {
        $method_info = $this->settings_model->check_by(array('tag_id' => $id), 'tbl_tags');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('activity_delete_a_method'),
            'value1' => $method_info->name,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        $this->settings_model->_table_name = 'tbl_tags';
        $this->settings_model->_primary_key = 'tag_id';
        $this->settings_model->delete($id);
        // messages for user
        $type = "success";
        $message = lang('tags_deleted');
        // messages for user
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }

    public function expense_category($action = NULL, $id = NULL)
    {
        $created = can_action('124', 'created');
        $edited = can_action('124', 'edited');
        $data['page'] = lang('settings');
        if ($action == 'edit_expense_category') {
            $data['active'] = 2;
            if (!empty($id) || !empty($edited)) {
                $data['expense_category_info'] = $this->settings_model->check_by(array('expense_category_id' => $id), 'tbl_expense_category');
            }
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('expense_category');
        if ($action == 'update_expense_category') {
            if (!empty($created) || !empty($edited) && !empty($id)) {
                $this->settings_model->_table_name = 'tbl_expense_category';
                $this->settings_model->_primary_key = 'expense_category_id';

                $cate_data['expense_category'] = $this->input->post('expense_category', TRUE);
                $cate_data['description'] = $this->input->post('description', TRUE);

                // update root category
                $where = array('expense_category' => $cate_data['expense_category']);
                // duplicate value check in DB
                if (!empty($id)) { // if id exist in db update data
                    $expense_category_id = array('expense_category_id !=' => $id);
                } else { // if id is not exist then set id as null
                    $expense_category_id = null;
                }
                // check whether this input data already exist or not
                $check_category = $this->settings_model->check_update('tbl_expense_category', $where, $expense_category_id);
                if (!empty($check_category)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['expense_category'] . '</strong>  ' . lang('already_exist');
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_a_expense_category'),
                        'value1' => $cate_data['expense_category']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = lang('expense_category_added');
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/expense_category');
        } else {
            $data['title'] = lang('expense_category'); //Page title
            $data['load_setting'] = 'expense_category';
        }

        $this->settings_model->_table_name = 'tbl_expense_category';
        $this->settings_model->_order_by = 'expense_category_id';
        $data['all_expense_category'] = $this->settings_model->get();

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(124);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function customer_group($action = NULL, $id = NULL)
    {
        $created = can_action('125', 'created');
        $edited = can_action('125', 'edited');
        $data['page'] = lang('settings');
        if ($action == 'edit_customer_group') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['customer_group_info'] = $this->settings_model->check_by(array('customer_group_id' => $id), 'tbl_customer_group');
            }
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('customer_group');
        if ($action == 'update_customer_group') {
            if (!empty($created) || !empty($edited) && !empty($id)) {
                $this->settings_model->_table_name = 'tbl_customer_group';
                $this->settings_model->_primary_key = 'customer_group_id';

                $cate_data['customer_group'] = $this->input->post('customer_group', TRUE);
                $cate_data['description'] = $this->input->post('description', TRUE);
                $cate_data['type'] = 'client';

                // update root category
                $where = array('type' => 'client', 'customer_group' => $cate_data['customer_group']);
                // duplicate value check in DB
                if (!empty($id)) { // if id exist in db update data
                    $customer_group_id = array('customer_group_id !=' => $id);
                } else { // if id is not exist then set id as null
                    $customer_group_id = null;
                }
                // check whether this input data already exist or not
                $check_category = $this->settings_model->check_update('tbl_customer_group', $where, $customer_group_id);
                if (!empty($check_category)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['customer_group'] . '</strong>  ' . lang('already_exist');
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('customer_group_added'),
                        'value1' => $cate_data['customer_group']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = lang('customer_group_added');
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/customer_group');
        } else {
            $data['title'] = lang('customer_group'); //Page title
            $data['load_setting'] = 'customer_group';
        }
        $data['all_customer_group'] = $this->db->where('type', 'client')->get('tbl_customer_group')->result();

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(125);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_expense_category($id)
    {
        $deleted = can_action('124', 'deleted');
        if (!empty($deleted)) {
            $expense_category = $this->settings_model->check_by(array('expense_category_id' => $id), 'tbl_expense_category');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_expense_category'),
                'value1' => $expense_category->expense_category,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $this->settings_model->_table_name = 'tbl_expense_category';
            $this->settings_model->_primary_key = 'expense_category_id';
            $this->settings_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('category_deleted');
            // messages for user
            echo json_encode(array("status" => $type, 'message' => $message));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function delete_customer_group($id)
    {
        $deleted = can_action('125', 'deleted');
        if (!empty($deleted)) {
            $customer_group = $this->settings_model->check_by(array('customer_group_id' => $id), 'tbl_customer_group');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_customer_group'),
                'value1' => $customer_group->customer_group,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $this->settings_model->_table_name = 'tbl_customer_group';
            $this->settings_model->_primary_key = 'customer_group_id';
            $this->settings_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('message_deleted');
            // messages for user
            echo json_encode(array("status" => $type, 'message' => $message));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function notification()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('notification_settings');
        // check notififation status by where
        $where = array('notify_me' => '1');
        // check email notification status
        $data['email'] = $this->settings_model->check_by($where, 'tbl_inbox');
        $data['load_setting'] = 'notification';
        $can_do = can_do(134);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);
    }

    public function set_noticifation()
    {
        $input_data = $this->settings_model->array_from_post(array(
            'pusher_app_id', 'pusher_app_key', 'pusher_app_secret',
            'pusher_cluster', 'auto_check_for_new_notifications', 'desktop_notifications', 'realtime_notification'
        ));
        if (!empty($input_data['realtime_notification']) && $input_data['realtime_notification'] == 1 || empty($input_data['auto_check_for_new_notifications'])) {
            $input_data['auto_check_for_new_notifications'] = 0;
        }
        if (empty($input_data['realtime_notification'])) {
            $input_data['realtime_notification'] = 0;
        }

        foreach ($input_data as $key => $value) {

            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }

        $type = "success";
        $message = lang('notification_settings_changes');
        set_message($type, $message);
        redirect('admin/settings/notification'); //redirect page
    }

    public function tickets()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'tickets';
        $data['title'] = lang('tickets_settings'); //Page title
        $data['assign_user'] = $this->settings_model->allowed_user('55');
        // get all leads status
        $status_info = $this->db->get('tbl_lead_status')->result();
        if (!empty($status_info)) {
            foreach ($status_info as $v_status) {
                $data['status_info'][$v_status->lead_type][] = $v_status;
            }
        }

        $can_do = can_do(119);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_tickets()
    {
        $can_do = can_do(119);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array(
                'notify_ticket_reopened', 'default_department', 'default_status', 'default_priority',
                'default_leads_source', 'default_lead_status'
            ));

            foreach ($input_data as $key => $value) {

                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_tickets_settings'),
                'value1' => $input_data['default_status']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            // messages for user
            $type = "success";
            $message = lang('save_tickets_settings');
            set_message($type, $message);
        }
        redirect('admin/settings/tickets');
    }

    public function update_profile()
    {
        $data['title'] = lang('update_profile');
        $data['subview'] = $this->load->view('admin/settings/update_profile', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function profile_updated()
    {
        $user_id = $this->session->userdata('user_id');
        $profile_data = $this->settings_model->array_from_post(array('fullname', 'phone', 'language', 'locale'));

        if (!empty($_FILES['avatar']['name'])) {
            $val = $this->settings_model->uploadImage('avatar');
            $val == TRUE || redirect('admin/settings/update_profile');
            $profile_data['avatar'] = $val['path'];
        }

        $this->settings_model->_table_name = 'tbl_account_details';
        $this->settings_model->_primary_key = 'user_id';
        $this->settings_model->save($profile_data, $user_id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => ('activity_update_profile'),
            'value1' => $profile_data['fullname'],
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        $client_id = $this->input->post('client_id', TRUE);
        if (!empty($client_id)) {
            $client_data = $this->settings_model->array_from_post(array('name', 'email', 'address'));
            $this->settings_model->_table_name = 'tbl_client';
            $this->settings_model->_primary_key = 'client_id';
            $this->settings_model->save($client_data, $client_id);
        }
        $type = "success";
        $message = lang('profile_updated');
        set_message($type, $message);
        redirect('admin/settings/update_profile'); //redirect page
    }

    public function set_password()
    {
        $user_id = $this->session->userdata('user_id');
        $password = $this->hash($this->input->post('old_password', TRUE));
        $check_old_pass = $this->admin_model->check_by(array('password' => $password), 'tbl_users');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        if (!empty($check_old_pass)) {
            $new_password = $this->input->post('new_password', true);
            $confirm_password = $this->input->post('confirm_password', true);
            if ($new_password == $confirm_password) {
                $data['password'] = $this->hash($new_password);
                $this->settings_model->_table_name = 'tbl_users';
                $this->settings_model->_primary_key = 'user_id';
                $this->settings_model->save($data, $user_id);
                $type = "success";
                $message = lang('password_updated');
                $action = ('activity_password_update');
            } else {
                $type = "error";
                $message = lang('password_does_not_match');
                $action = ('activity_password_error');
            }
        } else {
            $type = "error";
            $message = lang('password_error');
            $action = ('activity_password_error');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => $action,
            'value1' => $user_info->username,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        set_message($type, $message);
        redirect('admin/settings/update_profile'); //redirect page
    }

    public function change_email()
    {
        $user_id = $this->session->userdata('user_id');
        $password = $this->hash($this->input->post('password', TRUE));
        $check_old_pass = $this->settings_model->check_by(array('password' => $password), 'tbl_users');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        if (!empty($check_old_pass)) {
            $new_email = $this->input->post('email', TRUE);
            if ($check_old_pass->email == $new_email) {
                $type = 'error';
                $message = lang('current_email');
                $action = lang('trying_update_email');
            } elseif ($this->is_email_available($new_email)) {
                $data = array(
                    'new_email' => $new_email,
                    'new_email_key' => md5(rand() . microtime()),
                );

                $this->settings_model->_table_name = 'tbl_users';
                $this->settings_model->_primary_key = 'user_id';
                $this->settings_model->save($data, $user_id);
                $data['user_id'] = $user_id;
                $this->send_email_change_email($new_email, $data);
                $type = "success";
                $message = lang('succesffuly_change_email');
                $action = lang('activity_updated_email');
            } else {
                $type = "error";
                $message = lang('duplicate_email');
                $action = ('trying_update_email');
            }
        } else {
            $type = "error";
            $message = lang('password_error');
            $action = ('trying_update_email');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => $action,
            'value1' => $user_info->email,
            'value2' => $new_email,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        set_message($type, $message);
        redirect('admin/settings/update_profile'); //redirect page
    }

    function send_email_change_email($email, $data)
    {
        $email_template = $this->settings_model->check_by(array('email_group' => 'change_email'), 'tbl_email_templates');
        $message = $email_template->template_body;
        $subject = $email_template->subject;

        $email_key = str_replace("{NEW_EMAIL_KEY_URL}", base_url() . 'login/reset_email/' . $data['user_id'] . '/' . $data['new_email_key'], $message);
        $new_email = str_replace("{NEW_EMAIL}", $data['new_email'], $email_key);
        $site_url = str_replace("{SITE_URL}", base_url(), $new_email);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $site_url);

        $params['recipient'] = $email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['resourceed_file'] = '';
        $this->settings_model->send_email($params);
    }

    function is_email_available($email)
    {

        $this->db->select('1', FALSE);
        $this->db->where('LOWER(email)=', strtolower($email));
        $this->db->or_where('LOWER(new_email)=', strtolower($email));
        $query = $this->db->get('tbl_users');
        return $query->num_rows() == 0;
    }

    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public function change_username()
    {
        $user_id = $this->session->userdata('user_id');
        $password = $this->hash($this->input->post('password', TRUE));
        $check_old_pass = $this->admin_model->check_by(array('password' => $password), 'tbl_users');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        if (!empty($check_old_pass)) {
            $data['username'] = $this->input->post('username');
            $this->settings_model->_table_name = 'tbl_users';
            $this->settings_model->_primary_key = 'user_id';
            $this->settings_model->save($data, $user_id);
            $type = "success";
            $message = lang('username_updated');
            $action = ('activity_username_updated');
        } else {
            $type = "error";
            $message = lang('password_error');
            $action = ('username_changed_error');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => $action,
            'value1' => $user_info->username,
            'value2' => $this->input->post('username'),
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        set_message($type, $message);
        redirect('admin/settings/update_profile'); //redirect page
    }

    public function database_backup()
    {
        $data['title'] = lang('database_backup');
        $data['page'] = lang('database_backup');
        $data['load_setting'] = 'database_backup';
        $this->load->helper('file');
        $data['backups'] = get_filenames('./uploads/backup/');
        $can_do = can_do(136);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);
    }

    public function db_backup()
    {
        $can_do = can_do(136);
        if (!empty($can_do)) {
            // NNN1q2w3e4r56
            $this->load->helper('file');
            $this->load->dbutil();
            $prefs = array('format' => 'zip', 'filename' => 'BD-backup_' . jdate('Y-m-d_H-i'));

            $backup = $this->dbutil->backup($prefs);

            if (!write_file('./uploads/backup/BD-backup_' . jdate('Y-m-d_H-i') . '.zip', $backup)) {
                $type = 'error';
                $message = lang('backup_error');
            } else {
                $type = 'success';
                $message = lang('backup_success');
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => 'activity_database_backup',
                'value1' => $prefs['filename']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            set_message($type, $message);
        }
        redirect('admin/settings/database_backup');
    }

    function download_backup($file)
    {
        $can_do = can_do(136);
        if (!empty($can_do)) {
            $this->load->helper('file');
            $this->load->helper('download');
            $data = file_get_contents('./uploads/backup/' . $file);
            force_download($file, $data);
        }
        redirect('admin/settings/database_backup');
    }

    public function delete_backup($file)
    {
        $can_do = can_do(136);
        if (!empty($can_do)) {
            $backup = file_exists('./uploads/backup/' . $file);
            if (!empty($backup)) {
                unlink('./uploads/backup/' . $file);
                $type = 'success';
                $message = lang('backup_delete_success');
            } else {
                $type = 'error';
                $message = lang('backup_error');
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => 'activity_backup_delete_success',
                'value1' => $file
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            set_message($type, $message);
        }
        redirect('admin/settings/database_backup');
    }

    function restore_database()
    {
        if ($_POST) {
            ini_set('max_execution_time', 30000);
            $this->load->helper('file');
            $this->load->helper('unzip');
            $this->load->database();

            $config['upload_path'] = './uploads/temp/';
            $config['allowed_types'] = 'zip';
            $config['max_size'] = '9000';
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('upload_file')) {
                $error = $this->upload->display_errors('', ' ');
                $type = 'error';
                $message = $error;
                set_message($type, $message);
                redirect('admin/settings/database_backup');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $backup = "uploads/temp/" . $data['upload_data']['file_name'];
            }
            if (!unzip($backup, "uploads/temp/", true, true)) {
                $type = 'error';
                $message = lang('backup_restore_error');
            } else {
                $this->load->dbforge();
                $backup = str_replace('.zip', '', $backup);
                $file_content = file_get_contents($backup . ".sql");
                $this->db->query('USE ' . $this->db->database . ';');
                foreach (explode(";\n", $file_content) as $sql) {
                    $sql = trim($sql);
                    if ($sql) {
                        $this->db->query($sql);
                    }
                }
                $type = 'success';
                $message = lang('backup_restore_success');
            }
            unlink($backup . ".sql");
            unlink($backup . ".zip");

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => 'activity_restore_database',
                'value1' => $backup
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            set_message($type, $message);
            redirect('admin/settings/database_backup');
        } else {
            $can_do = can_do(136);
            if (!empty($can_do)) {
                $data['title'] = lang('restore_database');
                $data['subview'] = $this->load->view('admin/settings/restore_database', $data, FALSE);
                $this->load->view('admin/_layout_modal', $data);
            } else {
                redirect('admin/settings/database_backup');
            }
        }
    }

    public function activities()
    {
        $data['title'] = lang('activities');
        $data['activities_info'] = $this->db->where(array('user' => $this->session->userdata('user_id')))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();

        $data['subview'] = $this->load->view('admin/settings/activities', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function activitiesList($type = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_activities';
            $this->datatables->column_order = array('activity_date', 'module', 'activity', 'value1');
            $this->datatables->column_search = array('activity_date', 'module', 'activity', 'value1');
            $this->datatables->order = array('activities_id' => 'desc');
            if (!empty($type)) {
                $where = array('user' => $this->session->userdata('user_id'), 'module' => $type);
            } else {
                $where = array('user' => $this->session->userdata('user_id'));
            }
            // get all invoice
            $fetch_data = get_result('tbl_activities', $where);

            $data = array();

            foreach ($fetch_data as $_key => $v_activity) {
                $action = null;
                $sub_array = array();
                $sub_array[] = display_datetime($v_activity->activity_date);
                $sub_array[] = fullname($v_activity->user);
                $sub_array[] = lang($v_activity->module);
                $sub_array[] = lang($v_activity->activity) . ' <strong>' . $v_activity->value1 . ' ' . $v_activity->value2 . '</strong>';
                $data[] = $sub_array;
            }

            render_table($data, $where);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function clear_activities()
    {
        $this->db->where(array('user' => $this->session->userdata('user_id')))->delete('tbl_activities');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => 'activity_deleted',
            'value1' => lang('all_activity') . ' ' . jdate('Y-m-d')
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        $type = "success";
        $message = lang('activities_deleted');
        set_message($type, $message);
        redirect('admin/dashboard');
    }

    public function new_currency($action = null, $code = null)
    {
        if (!empty($action)) {
            $data = $this->settings_model->array_from_post(array('code', 'name', 'symbol'));
            if (!empty($code)) {
                $this->db->set($data);
                $this->db->where('code', $code);
                $this->db->update('tbl_currencies');
                redirect('admin/settings/all_currency');
            } else {
                $this->settings_model->_table_name = 'tbl_currencies';
                $this->settings_model->save($data);
                redirect('admin/settings/system');
            }
        }
        $data['title'] = lang('activities');
        $data['modal_subview'] = $this->load->view('admin/settings/_modal_new_currency', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function custom_field($id = null)
    {
        $edited = can_action('130', 'edited');
        $data['page'] = lang('settings');
        $data['load_setting'] = 'custom_field';
        if (!empty($id) && !empty($edited)) {
            $data['active'] = 2;
            $data['field_info'] = $this->db->where('custom_field_id', $id)->get('tbl_custom_field')->row();
        } else {
            $data['active'] = 1;
        }

        $data['title'] = lang('custom_field'); //Page title
        $can_do = can_do(130);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function custom_fieldList($type = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_custom_field';
            $this->datatables->join_table = array('tbl_form');
            $this->datatables->join_where = array('tbl_form.form_id=tbl_custom_field.form_id');
            $this->datatables->column_order = array('tbl_form.form_name', 'field_label', 'field_type', 'help_text', 'required', 'show_on_table', 'show_on_details', 'visible_for_admin');
            $this->datatables->column_search = array('tbl_form.form_name', 'field_label', 'field_type', 'help_text', 'required', 'show_on_table', 'show_on_details', 'visible_for_admin');
            $this->datatables->order = array('custom_field_id' => 'desc');

            if (!empty($type)) {
                $where = array('tbl_custom_field.form_id' => $type);
            } else {
                $where = null;
            }
            // get all invoice
            $fetch_data = make_datatables($where);

            $data = array();

            $edited = can_action('130', 'edited');
            $deleted = can_action('130', 'deleted');
            foreach ($fetch_data as $_key => $v_custom_fields) {

                $form_info = $this->db->where('form_id', $v_custom_fields->form_id)->get('tbl_form')->row();
                if ($v_custom_fields->field_type == 'dropdown') {
                    $type = lang('dropdowns');
                } else {
                    $type = lang($v_custom_fields->field_type);
                }

                $action = null;

                $sub_array = array();
                $sub_array[] = $v_custom_fields->field_label;
                $sub_array[] = lang($form_info->form_name);
                $sub_array[] = $type;
                $sub_array[] = '<div class="status"><input data-toggle="toggle" id="' . $v_custom_fields->custom_field_id . '" name="status" value="active" ' . ((!empty($v_custom_fields->status) && $v_custom_fields->status == 'active') ? 'checked' : '') . ' data-on="' . lang('yes') . '" data-off="' . lang('no') . '" data-onstyle="success btn-xs status" data-offstyle="danger btn-xs status" type="checkbox"></div>';

                if (!empty($edited) || !empty($deleted)) {
                    if (!empty($edited)) {
                        $action .= btn_edit('admin/settings/custom_field/' . $v_custom_fields->custom_field_id) . ' ';
                    }
                    if (!empty($deleted)) {
                        $action .= ajax_anchor(base_url("admin/settings/detele_custom_field/" . $v_custom_fields->custom_field_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                    }
                    $sub_array[] = $action;
                }
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public
    function save_custom_field($id = null)
    {
        $created = can_action('130', 'created');
        $edited = can_action('130', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $data = $this->settings_model->array_from_post(array('form_id', 'field_label', 'field_type', 'help_text', 'required', 'show_on_table', 'show_on_details', 'visible_for_admin', 'visible_for_client', 'status'));

            $data['default_value'] = json_encode($this->input->post('default_value', true));

            if (empty($data['required'])) {
                $data['required'] = 'false';
            }
            if (empty($data['show_on_details'])) {
                $data['show_on_details'] = 'No';
            }
            if (empty($data['status'])) {
                $data['status'] = 'deactive';
            }
            $form_info = $this->db->where('form_id', $data['form_id'])->get('tbl_form')->row();

            $field_name = slug_it($data['field_label']);
            $fieldName = slug_it($data['field_label']);

            $fields = array(
                $fieldName => array(
                    'type' => 'TEXT',
                    'null' => true
                )
            );

            $this->load->dbforge();
            if (!empty($id)) {
                $field_info = $this->db->where('custom_field_id', $id)->get('tbl_custom_field')->row();
                $oldFieldName = slug_it($field_info->field_label);
                $m_fields = array(
                    $oldFieldName => array(
                        'name' => $field_name,
                        'type' => 'TEXT',
                        'null' => true
                    ),
                );
                if ($this->db->field_exists($oldFieldName, $form_info->tbl_name)) {
                    $result = $this->dbforge->modify_column($form_info->tbl_name, $m_fields);
                } else {
                    $result = $this->dbforge->add_column($form_info->tbl_name, $fields);
                }
            } else {
                $result = $this->dbforge->add_column($form_info->tbl_name, $fields);
            }
            if ($data['form_id'] == 1 || $data['form_id'] == 2) {
                $this->settings_model->_table_name = 'tbl_custom_field';
                $this->settings_model->_primary_key = 'custom_field_id';
                $this->settings_model->save($data, $id);
                $type = "success";
                $message = lang('save_custom_field');
            } elseif (!empty($result)) {
                $this->settings_model->_table_name = 'tbl_custom_field';
                $this->settings_model->_primary_key = 'custom_field_id';
                $this->settings_model->save($data, $id);

                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $this->session->userdata('user_id'),
                    'activity' => ('activity_new_custom_field'),
                    'value1' => $data['field_label']
                );

                $this->settings_model->_table_name = 'tbl_activities';
                $this->settings_model->_primary_key = 'activities_id';
                $this->settings_model->save($activity);
                // messages for user
                $type = "success";
                $message = lang('save_custom_field');
            } else {
                $type = "error";
                $message = lang('custom_field_already_exist');
            }
            $type = $type;
            $message = $message;
            set_message($type, $message);
        }
        redirect('admin/settings/custom_field');
    }

    public
    function change_field_status($id = null)
    {
        $data['status'] = $this->input->post('status', true);
        $this->settings_model->_table_name = 'tbl_custom_field';
        $this->settings_model->_primary_key = 'custom_field_id';
        $this->settings_model->save($data, $id);
        echo true;
        exit();
    }

    public function detele_custom_field($id)
    {
        $deleted = can_action('130', 'deleted');
        if (!empty($deleted)) {
            $field_info = $this->db->where('custom_field_id', $id)->get('tbl_custom_field')->row();
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_custom_field'),
                'value1' => $field_info->field_label
            );

            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $fName = slug_it($field_info->field_label);
            $form = $this->db->where('form_id', $field_info->form_id)->get('tbl_form')->row();
            $field_exists = $this->db->field_exists($fName, $form->tbl_name);

            if (!empty($field_exists)) {
                $this->load->dbforge();
                $this->dbforge->drop_column($form->tbl_name, $fName);
            }

            $this->settings_model->_table_name = 'tbl_custom_field';
            $this->settings_model->_primary_key = 'custom_field_id';
            $this->settings_model->delete($id);
            // messages for user
            echo json_encode(array("status" => 'success', 'message' => lang('delete_custom_field')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function email_integration()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'email_integration';
        $data['title'] = lang('email_integration'); //Page title
        $can_do = can_do(115);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_email_integration()
    {
        $departments_id = $this->input->post('departments_id', true);
        if (!empty($departments_id)) {

            $input_data['email'] = $this->input->post('email_' . $departments_id, true);
            $input_data['encryption'] = $this->input->post('encryption_' . $departments_id, true);
            $input_data['delete_mail_after_import'] = $this->input->post('delete_mail_after_import_' . $departments_id, true);
            $input_data['host'] = $this->input->post('host_' . $departments_id, true);
            $input_data['username'] = $this->input->post('username_' . $departments_id, true);
            $input_data['mailbox'] = $this->input->post('mailbox_' . $departments_id, true);
            $input_data['unread_email'] = $this->input->post('unread_email_' . $departments_id, true);
            $password = $this->input->post('password_' . $departments_id, true);
            if (!empty($password)) {
                $input_data['password'] = encrypt($password);
            }
            if ($input_data['encryption'] == 'on') {
                $input_data['encryption'] = null;
            }
            if (empty($input_data['unread_email'])) {
                $input_data['unread_email'] = 0;
            }
            if (empty($input_data['delete_mail_after_import'])) {
                $input_data['delete_mail_after_import'] = 0;
            }
            $this->settings_model->_table_name = 'tbl_departments';
            $this->settings_model->_primary_key = 'departments_id';
            $this->settings_model->save($input_data, $departments_id);
        } else {
            $input_data = $this->settings_model->array_from_post(array(
                'encryption',
                'delete_mail_after_import', 'config_host', 'config_username', 'config_mailbox', 'unread_email', 'for_leads', 'imap_search_for_leads', 'leads_keyword',
                'imap_search_for_tickets', 'tickets_keyword', 'for_tickets'
            ));

            $config_password = $this->input->post('config_password', true);
            if (!empty($config_password)) {
                $input_data['config_password'] = encrypt($config_password);
            }
            if ($input_data['encryption'] == 'on') {
                $input_data['encryption'] = null;
            }
            if (empty($input_data['unread_email'])) {
                $input_data['unread_email'] = 'on';
            }
            if (empty($input_data['for_leads'])) {
                $input_data['for_leads'] = null;
            }
            if (empty($input_data['for_tickets'])) {
                $input_data['for_tickets'] = null;
            }
            if (empty($input_data['delete_mail_after_import'])) {
                $input_data['delete_mail_after_import'] = null;
            }

            $input_data['notified_user'] = json_encode($this->input->post('notified_user'), true);
            foreach ($input_data as $key => $value) {
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_save_email_integration'),
            'value1' => (!empty($departments_id) ? lang('for') . '' . lang('tickets') . $input_data['host'] : lang('for') . '' . lang('tickets') . ' ' . $input_data['config_host'])
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        // messages for user
        $type = "success";
        $message = lang('save_email_integration');
        set_message($type, $message);
        redirect('admin/settings/email_integration/' . (!empty($departments_id) ? $departments_id : ''));
    }

    public function test_email($etype = null)
    {
        require_once(APPPATH . 'libraries/Imap.php');

        if (!empty($etype) && $etype == 'Leads') {
            $username = config_item('config_username');
            $password = decrypt(config_item('config_password'));
            $mailbox = config_item('config_host');
            //            $mailbox = config_item('config_mailbox');
            $encryption = config_item('encryption');
        } elseif (!empty($etype) && is_numeric($etype)) {
            $dept_info = get_row('tbl_departments', array('departments_id' => $etype));
            $username = $dept_info->username;
            $password = decrypt($dept_info->password);
            $mailbox = $dept_info->host;
            //            $mailbox = config_item('config_mailbox');
            $encryption = $dept_info->encryption;
        }
        if (!empty($mailbox)) {
            $imap = new Imap($mailbox, $username, $password, $encryption);

            if ($imap->isConnected() === false) {
                $type = "error";
                $header = $imap->getError();
            } else {
                $type = "success";
                $header = lang('connection_success');
            }
            $imap->close();
            $s_data['header'] = $header;
            $s_data['type'] = $type;
            $this->session->set_userdata($s_data);

            set_message($type, $header);
        }
        redirect('admin/settings/email_integration/' . $etype);
    }

    public function cronjob()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'cronjob';
        $data['title'] = lang('cronjob'); //Page title
        $can_do = can_do(132);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function all_currency($code = null)
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'all_currency';
        $data['title'] = lang('all_currency'); //Page title
        if (!empty($code)) {
            $data['currency'] = $this->db->where('code', $code)->get('tbl_currencies')->row();
        }
        $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_cronjob()
    {
        $input_data = $this->settings_model->array_from_post(array('active_cronjob', 'automatic_database_backup'));
        foreach ($input_data as $key => $value) {
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_save_cronjob'),
            'value1' => $input_data['active_cronjob']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        // messages for user
        $type = "success";
        $message = lang('save_cronjob');
        set_message($type, $message);
        redirect('admin/settings/cronjob');
    }

    public function working_days($action = NULL, $id = NULL)
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('working_days'); //Page title
        $data['load_setting'] = 'working_days';

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(121);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_working_days()
    {
        $office_time['office_time'] = $this->input->post('office_time', TRUE);
        if (!empty($office_time)) {
            foreach ($office_time as $key => $value) {
                $office_data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $office_data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
        }
        $get_day = $this->input->post('day', TRUE);

        $day_id = $this->input->post('day_id', TRUE);
        $working_days_id = $this->input->post('working_days_id', TRUE);

        foreach ($day_id as $skey => $day) {
            $data['flag'] = 0;
            $data['day_id'] = $day;
            $data['start_hours'] = '00:00:00';
            $data['end_hours'] = '00:00:00';
            // if it's same time so input same time data else get different time
            if ($office_time['office_time'] == 'same_time') {
                $data['start_hours'] = ($this->input->post('s_start_hours', TRUE));
                $data['end_hours'] = ($this->input->post('s_end_hours', TRUE));
            }
            if (!empty($get_day)) {
                if (in_array($day, $get_day)) {
                    $data['flag'] = 1;
                }
                if ($office_time['office_time'] == 'different_time') {
                    $data['start_hours'] = $this->input->post('start_hours_' . $day, TRUE);
                    $data['end_hours'] = $this->input->post('end_hours_' . $day, TRUE);
                }
            }

            $this->settings_model->_table_name = "tbl_working_days"; // table name
            $this->settings_model->_primary_key = "working_days_id"; // $id
            if (!empty($working_days_id[$skey])) {
                $this->settings_model->save($data, $working_days_id[$skey]);
            } else {
                $this->settings_model->save($data);
            }
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_update_working_days'),
            'value1' => $office_time['office_time']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        // messages for user
        $type = "success";
        $message = lang('update_working_days');
        set_message($type, $message);
        redirect('admin/settings/working_days');
    }

    public function leave_category($action = NULL, $id = NULL)
    {
        $edited = can_action('122', 'edited');
        $created = can_action('122', 'created');

        $data['page'] = lang('settings');
        if ($action == 'edit_leave_category') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['leave_category_info'] = $this->settings_model->check_by(array('leave_category_id' => $id), 'tbl_leave_category');
            }
        } else {
            $data['active'] = 1;
        }

        if ($action == 'update_leave_category') {
            if (!empty($created) || !empty($edited) && !empty($id)) {
                $this->settings_model->_table_name = 'tbl_leave_category';
                $this->settings_model->_primary_key = 'leave_category_id';
                // input data
                $cate_data = $this->settings_model->array_from_post(array('leave_category', 'leave_quota')); //input post
                // dublicacy check
                if (!empty($id)) {
                    $leave_category_id = array('leave_category_id !=' => $id);
                } else {
                    $leave_category_id = null;
                }
                // check check_leave_category by where
                // if not empty show alert message else save data
                $check_leave_category = $this->settings_model->check_update('tbl_leave_category', $where = array('leave_category' => $cate_data['leave_category']), $leave_category_id);

                if (!empty($check_leave_category)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['leave_category'] . '</strong>  ' . lang('already_exist');
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_a_leave_category'),
                        'value1' => $cate_data['leave_category']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = lang('leave_category_added');
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/leave_category');
        } else {
            $data['title'] = lang('leave_category'); //Page title
            $data['load_setting'] = 'leave_category';
        }

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(122);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_leave_category($id)
    {
        $deleted = can_action('122', 'deleted');
        if (!empty($deleted)) {
            // check into application list
            $where = array('leave_category_id' => $id);
            // check existing leave category into tbl_application_list
            $check_existing_ctgry = $this->settings_model->check_by($where, 'tbl_leave_application');
            if (!empty($check_existing_ctgry)) { // if not empty do not delete this else delete
                // messages for user
                $type = "error";
                $message = lang('leave_category_used');
            } else {

                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $id,
                    'activity' => ('activity_delete_a_leave_category'),
                    'value1' => $this->db->where('leave_category_id', $id)->get('tbl_leave_category')->row()->leave_category,
                );
                $this->settings_model->_table_name = 'tbl_activities';
                $this->settings_model->_primary_key = 'activities_id';
                $this->settings_model->save($activity);


                $this->settings_model->_table_name = "tbl_leave_category"; //table name
                $this->settings_model->_primary_key = "leave_category_id";    //id
                $this->settings_model->delete($id);

                $type = "success";
                $message = lang('leave_category_deleted');
            }
            // messages for user
            echo json_encode(array("status" => $type, 'message' => $message));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function menu_allocation()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('menu_allocation'); //Page title
        $data['load_setting'] = 'menu_allocation';

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;
        $data['active_menu'] = $this->all_active_menu();
        $data['inactive_menu'] = $this->all_inactive_menu();

        $can_do = can_do(133);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function all_active_menu()
    {
        $user_menu = $this->db->where('status', 1)->order_by('sort')->get('tbl_menu')->result();
        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        // Builds the array lists with data from the menu table
        foreach ($user_menu as $v_menu) {
            $menu['items'][$v_menu->menu_id] = $v_menu;
            $menu['parents'][$v_menu->parent][] = $v_menu->menu_id;
        }
        return $output = $this->buildMenu(0, $menu);
    }

    public function all_inactive_menu()
    {
        $user_menu = $this->db->where('status', 0)->order_by('sort', 'time')->get('tbl_menu')->result();

        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        // Builds the array lists with data from the menu table
        foreach ($user_menu as $v_menu) {
            $menu['items'][$v_menu->menu_id] = $v_menu;
            $menu['parents'][$v_menu->parent][] = $v_menu->menu_id;
        }
        return $output = $this->buildMenu(0, $menu);
    }

    public function buildMenu($parent, $menu, $sub = NULL)
    {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            if (!empty($sub)) {
                $html .= "<ol id=" . $sub . " class='dd-list'>\n";
            } else {
                $html .= "<ol class='dd-list'>\n";
            }
            foreach ($menu['parents'][$parent] as $itemId) {
                $active = '';

                if (!isset($menu['parents'][$itemId])) { //if condition is false only view menu
                    $html .= "<li data-id='" . $itemId . "' class='dd-item' >\n  <div class='dd-handle'>" . lang($menu['items'][$itemId]->label) . "</div> \n</li> \n";
                }
                if (isset($menu['parents'][$itemId])) { //if condition is true show with submenu
                    $html .= "<li data-id='" . $itemId . "' class='dd-item'>\n<div class='dd-handle'>" . lang($menu['items'][$itemId]->label) . "</div>\n";
                    $html .= self::buildMenu($itemId, $menu, $menu['items'][$itemId]->label);
                    $html .= "</li> \n";
                }
            }
            $html .= "</ol> \n";
        }
        return $html;
    }

    public function update_menu_allocation()
    {
        $all_menu = json_decode($this->input->post('all_active_menu', true));
        foreach ($all_menu as $r_sort => $root_menu) {

            $r_data['sort'] = $r_sort;
            $r_data['status'] = 1;
            $r_data['parent'] = 0;
            $this->settings_model->_table_name = "tbl_menu"; //table name
            $this->settings_model->_primary_key = "menu_id"; // $id
            $this->settings_model->save($r_data, $root_menu->id);

            if (!empty($root_menu->children)) {
                foreach ($root_menu->children as $child_sort => $sub_menu) {
                    $c_data['sort'] = $child_sort;
                    $c_data['status'] = 1;
                    $c_data['parent'] = $root_menu->id;
                    $this->settings_model->_table_name = "tbl_menu"; //table name
                    $this->settings_model->_primary_key = "menu_id"; // $id
                    $this->settings_model->save($c_data, $sub_menu->id);

                    if (!empty($sub_menu->children)) {
                        foreach ($sub_menu->children as $sub_child_sort => $sub_child_menu) {

                            $c_s_data['sort'] = $sub_child_sort;
                            $c_s_data['status'] = 1;
                            $c_s_data['parent'] = $sub_menu->id;
                            $this->settings_model->_table_name = "tbl_menu"; //table name
                            $this->settings_model->_primary_key = "menu_id"; // $id
                            $this->settings_model->save($c_s_data, $sub_child_menu->id);
                        }
                    }
                }
            }
        }
        $all_inactive_menu = json_decode($this->input->post('all_inactive_menu', true));
        foreach ($all_inactive_menu as $i_r_sort => $in_root_menu) {

            $in_r_data['sort'] = $i_r_sort;
            $in_r_data['status'] = 0;
            $in_r_data['parent'] = 0;

            $this->settings_model->_table_name = "tbl_menu"; //table name
            $this->settings_model->_primary_key = "menu_id"; // $id
            $this->settings_model->save($in_r_data, $in_root_menu->id);

            if (!empty($in_root_menu->children)) {
                foreach ($in_root_menu->children as $in_child_sort => $in_sub_menu) {
                    $in_c_data['sort'] = $in_child_sort;
                    $in_c_data['status'] = 0;
                    $in_c_data['parent'] = $in_root_menu->id;

                    $this->settings_model->_table_name = "tbl_menu"; //table name
                    $this->settings_model->_primary_key = "menu_id"; // $id
                    $this->settings_model->save($in_c_data, $in_sub_menu->id);

                    if (!empty($in_sub_menu->children)) {
                        foreach ($in_sub_menu->children as $in_sub_child_sort => $in_sub_child_menu) {

                            $in_c_s_data['sort'] = $in_sub_child_sort;
                            $in_c_s_data['status'] = 0;
                            $in_c_s_data['parent'] = $in_sub_menu->id;
                            $this->settings_model->_table_name = "tbl_menu"; //table name
                            $this->settings_model->_primary_key = "menu_id"; // $id
                            $this->settings_model->save($in_c_s_data, $in_sub_child_menu->id);
                        }
                    }
                }
            }
        }
        redirect('admin/settings/menu_allocation');
    }

    public function email_notification()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('email') . ' ' . lang('notification');

        $data['load_setting'] = 'email_notification';
        $can_do = can_do(135);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);
    }

    public function save_email_notification()
    {
        $input_data = $this->settings_model->array_from_post(array(
            'send_clock_email', 'leave_email', 'overtime_email', 'projects_email', 'tasks_email',
            'payslip_email', 'advance_salary_email', 'award_email', 'job_circular_email', 'announcements_email', 'training_email', 'expense_email', 'deposit_email'
        ));
        foreach ($input_data as $key => $value) {
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        // messages for user
        $type = "success";
        $message = lang('notification_settings_changes');
        set_message($type, $message);
        redirect('admin/settings/email_notification');
    }

    public function set_default($key, $value)
    {
        $input_data = array($key => $value);
        foreach ($input_data as $key => $value) {
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        // messages for user
        $type = "success";
        $message = lang('successfully_set_default');
        set_message($type, $message);
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/settings');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }


    public function system_update()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('system_update');
        if (!extension_loaded('curl')) {
            $data['update_errors'][] = 'CURL Extension not enabled';
            $data['latest_version'] = 0;
            $data['update_info'] = json_decode("");
        } else {
            $data['update_info'] = $this->admin_model->get_update_info();
            if (strpos($data['update_info'], 'Curl Error -') !== FALSE) {
                $data['update_errors'][] = $data['update_info'];
                $data['latest_version'] = 0;
                $data['update_info'] = json_decode("");
            } else {
                $data['update_info'] = json_decode($data['update_info']);
                $data['latest_version'] = $data['update_info']->latest_version;
                $data['update_errors'] = array();
            }
        }
        if (!extension_loaded('zip')) {
            $data['update_errors'][] = 'ZIP Extension not enabled';
        }

        $tmp_dir = get_temp_dir();
        if (!$tmp_dir || !is_writable($tmp_dir)) {
            $tmp_dir = app_temp_dir();
        }
        if (!is_writeable($tmp_dir)) {
            $data['update_errors'][] = "Temporary directory not writable - <b>$tmp_dir</b><br />Please contact your hosting provider make this directory writable. The directory needs to be writable for the update files.";
        }

        $data['current_version'] = $this->db->get('tbl_migrations')->row()->version;
        $data['load_setting'] = 'system_update';
        $can_do = can_do(138);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);
    }

    public function manage_status($status)
    {
        $data['title'] = lang('reminder') . ' ' . lang('list');
        if ($this->input->post()) {
            if ($status == 'status') {
                $r_data['status'] = $this->input->post('status', true);
                $this->settings_model->_table_name = 'tbl_status';
                $this->settings_model->_primary_key = 'status_id';
                $id = $this->settings_model->save($r_data);
            } elseif ($status == 'priority') {
                $r_data['priority'] = $this->input->post('status', true);
                $this->settings_model->_table_name = 'tbl_priority';
                $this->settings_model->_primary_key = 'priority_id';
                $id = $this->settings_model->save($r_data);
            }

            // Log Activity
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_added_' . $status),
                'icon' => 'fa-circle-o',
                'value1' => $this->input->post('status', true),
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $type = "success";
            $message = lang('update_message');
            set_message($type, $message);
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/settings');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        } else {
            if (!empty($id)) {
                $data['active'] = 2;
                $data['reminder_info'] = $this->db->where('reminder_id', $id)->get('tbl_reminders')->row();
            } else {
                $data['active'] = 1;
            }
            $data['all_status'] = $this->db->get('tbl_' . $status)->result();
            $data['status'] = $status;
            $data['subview'] = $this->load->view('admin/settings/status', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data);
        }
    }

    public function delete_status($module, $module_id)
    {
        if ($module == 'status') {
            $where = 'status';
        } else {
            $where = 'priority';
        }
        $status_info = $this->db->where($where . '_id', $module_id)->get('tbl_' . $where)->row();

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => $module,
            'module_field_id' => $module_id,
            'activity' => ('activity_delete_' . $module),
            'icon' => 'fa-circle-o',
            'value1' => !empty($status_info->status) ? $status_info->status : $status_info->priority,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        $this->settings_model->_table_name = 'tbl_' . $where;
        $this->settings_model->_primary_key = $where . '_id';
        $this->settings_model->delete($module_id);

        $type = "success";
        $message = lang('activity_delete_' . $module);
        set_message($type, $message);
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/settings');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function allowed_ip($action = NULL, $id = NULL)
    {
        $edited = can_action('149', 'edited');
        $created = can_action('149', 'created');
        $deleted = can_action('149', 'deleted');

        $data['page'] = lang('settings');
        if ($action == 'edit_allowed_ip') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['allowed_ip_info'] = $this->settings_model->check_by(array('allowed_ip_id' => $id), 'tbl_allowed_ip');
            }
        } else {
            $data['active'] = 1;
        }

        if ($action == 'update_allowed_ip') {
            if (!empty($created) || !empty($edited) && !empty($id)) {
                $this->settings_model->_table_name = 'tbl_allowed_ip';
                $this->settings_model->_primary_key = 'allowed_ip_id';
                // input data
                $cate_data = $this->settings_model->array_from_post(array('allowed_ip', 'status')); //input post
                // dublicacy check
                if (!empty($id)) {
                    $allowed_ip_id = array('allowed_ip_id !=' => $id);
                } else {
                    $allowed_ip_id = null;
                }
                // check check_allowed_ip by where
                // if not empty show alert message else save data
                $check_allowed_ip = $this->settings_model->check_update('tbl_allowed_ip', $where = array('allowed_ip' => $cate_data['allowed_ip']), $allowed_ip_id);

                if (!empty($check_allowed_ip)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['allowed_ip'] . '</strong>  ' . lang('already_exist');
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_a_allowed_ip'),
                        'value1' => $cate_data['allowed_ip']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = lang('allowed_ip_added');
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/allowed_ip');
        } else if ($action == 'change_status') {
            if (!empty($edited)) {
                $allowed_ip_id = $this->uri->segment(6);
                $this->settings_model->_table_name = 'tbl_allowed_ip';
                $this->settings_model->_primary_key = 'allowed_ip_id';
                // input data
                $action_data['status'] = $id;
                $this->settings_model->save($action_data, $allowed_ip_id);
                // messages for user
                $type = "success";
                $msg = lang('allowed_ip_added');

                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/allowed_ip');
        } else if ($action == 'delete_allowed_ip') {
            if (!empty($deleted)) {

                $allowed_ip_info = $this->settings_model->check_by(array('allowed_ip_id' => $id), 'tbl_allowed_ip');
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $id,
                    'activity' => ('activity_deleted_a_allowed_ip'),
                    'value1' => $allowed_ip_info->allowed_ip
                );
                $this->settings_model->_table_name = 'tbl_activities';
                $this->settings_model->_primary_key = 'activities_id';
                $this->settings_model->save($activity);

                $this->settings_model->_table_name = 'tbl_allowed_ip';
                $this->settings_model->_primary_key = 'allowed_ip_id';
                $this->settings_model->delete($id);

                // messages for user
                $type = "success";
                $message = lang('allowed_ip_deleted');
                // messages for user
                echo json_encode(array("status" => $type, 'message' => $message));
                exit();
            } else {
                echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
                exit();
            }
        } else {
            $data['title'] = lang('allowed_ip'); //Page title
            $data['load_setting'] = 'allowed_ip';
        }

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(149);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function sms_settings()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('sms_settings');

        $data['load_setting'] = 'sms_settings';
        $can_do = can_do(135);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);
    }

    public function save_sms_settings()
    {
        $input_data = $this->settings_model->array_from_post(array(
            'purchase_confirmation_sms_number',
            'transaction_record_sms_number', 'sms_template_purchase_confirmation', 'sms_template_purchase_payment_confirmation',
            'sms_template_return_stock', 'sms_template_return_stock_payment', 'sms_template_transaction_record',
            'sms_template_reminder_invoice', 'sms_template_overdue_invoice', 'sms_template_invoice_payment',
            'sms_template_estimate_expiration', 'sms_template_proposal_expiration', 'sms_template_staff_reminder'
        ));
        $all_gateway = $this->sms->get_gateways();
        foreach ($all_gateway as $gatwayName => $all_field) {
            foreach ($all_field['options'] as $key => $field) {
                $input_data[$field['name']]
                    = $this->input->post($field['name'], true);
            }
            $gateway_status = $gatwayName . '_status';
            $input_data[$gatwayName . '_status'] =
                $this->input->post($gateway_status, true);
        }

        foreach ($input_data as $key => $value) {
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        // messages for user
        $type = "success";
        $message = lang('notification_settings_changes');
        set_message($type, $message);
        redirect('admin/settings/sms_settings');
    }

    public function test_sms()
    {
        if ($this->input->post('sms_gateway_test', true)) {
            $callable = $this->input->post('id') . '_send_sms';
            if (function_exists($callable)) {
                $message = nl2br($this->input->post('message', true));
                $number = $this->input->post('number', true);
                $retval = call_user_func_array($callable, [$number, clear_textarea_breaks($message)]);
                $response = ['success' => false];
                if (isset($GLOBALS['sms_error'])) {
                    $response['error'] = $GLOBALS['sms_error'];
                } else {
                    $response['success'] = true;
                }
            } else {
                $response['error'] = 'This function is not exist';
            }
        } else {
            $response['error'] = 'This is not working';
        }
        echo json_encode($response);
        die;
    }


    public function transactions()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('transactions');

        $data['load_setting'] = 'transactions';
        $can_do = can_do(162);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);
    }

    public function save_transactions()
    {
        $can_do = can_do(162);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('deposit_prefix', 'deposit_start_no', 'deposit_number_format', 'expense_prefix', 'expense_start_no', 'expense_number_format'));

            foreach ($input_data as $key => $value) {
                if (strtolower($value) == 'on') {
                    $value = 'TRUE';
                } elseif (strtolower($value) == 'off') {
                    $value = 'FALSE';
                }
                $data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_transaction_settings'),
                'value1' => $input_data['deposit_prefix']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('save_transactions_settings');
            set_message($type, $message);
            redirect('admin/settings/transactions');
        }
    }


    public function warehouse($id = NULL)
    {
        $data['title'] = lang('manage_warehouse');
        if ($id) {
            $data['active'] = 2;
            $can_edit = $this->settings_model->can_action('tbl_warehouse', 'edit', array('warehouse_id' => $id));
            $edited = can_action('186', 'edited');
            if (!empty($can_edit) && !empty($edited)) {
                $data['warehouse_info'] = $this->settings_model->check_by(array('warehouse_id' => $id), 'tbl_warehouse');
            }
        } else {
            $data['active'] = 1;
        }
        $data['permission_user'] = $this->settings_model->all_permission_user('186');
        $data['all_warehouse'] = $this->settings_model->get_permission('tbl_warehouse');
        $data['subview'] = $this->load->view('admin/warehouse/manage_warehouse', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function award_rule_settingh()
    {

        $data['page'] = lang('settings');
        $data['load_setting'] = 'award_rule';
        $data['title'] = lang('award_rule') . ' ' . lang('settings'); //Page title
        $data['all_client'] = $this->settings_model->select_data('tbl_client', 'client_id', 'name');


        $can_do = can_do(155);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function award_program_settings()
    {

        $data['page'] = lang('settings');
        $data['load_setting'] = 'program_settings';
        $data['title'] = lang('award_rule') . ' ' . lang('settings'); //Page title
        $data['all_client'] = $this->settings_model->select_data('tbl_client', 'client_id', 'name');


        $can_do = can_do(155);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_new_rule($id)
    {
        $reminder_info = $this->db->where('award_rule_id', $id)->get('tbl_award_rule')->row();

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => $module,
            'module_field_id' => $module_id,
            'activity' => ('activity_award_rule_reminder'),
            'icon' => 'fa-shopping-cart',
            'link' => $url,
            'value1' => $reminder_info->description,
        );
        $this->invoice_model->_table_name = 'tbl_activities';
        $this->invoice_model->_primary_key = 'activities_id';
        $this->invoice_model->save($activity);

        $this->invoice_model->_table_name = 'tbl_award_rule';
        $this->invoice_model->_primary_key = 'award_rule_id';
        $this->invoice_model->delete($id);
        echo json_encode(array("status" => 'success', 'message' => lang('delete_award_rule')));
        exit();
    }

    public function new_program($id = null)
    {


        $data['title'] = lang('new') . ' ' . lang('program');
        $data['all_client'] = $this->settings_model->select_data('tbl_client', 'client_id', 'name');
        $data['all_awardrule'] = $this->settings_model->select_data('tbl_award_rule', 'award_rule_id', 'rule_name');

        if (!empty($id)) {

            $can_edit = $this->settings_model->can_action('tbl_award_rule', 'edit', array('award_rule_id' => $id));
            $edited = can_action('11', 'edited');
            if (!empty($can_edit) && !empty($edited)) {

                $data['awardrule_info'] = $this->db->where('award_rule_id', $id)->get('tbl_award_rule')->row();
                // $this->settings_model->('tbl_award_rule',array('award_rule_id' =>$id));
            }
            // print('<pre>'.print_r($data,true).'</pre>'); exit;


        }
        $data['subview'] = $this->load->view('admin/settings/new_program', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function save_program()
    {
        $can_do = can_do(155);
        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('program_name', 'award_rule_id', 'client_id', 'start_date', 'end_date', 'description', 'status'));

            //   print('<pre>'.print_r($input_data,true).'</pre>'); exit;

            //image Process
            $this->settings_model->_table_name = 'tbl_award_program';
            $this->settings_model->_primary_key = 'award_program_id';
            if (!empty($id)) {

                $id = $this->settings_model->save($input_data, $id);
            } else {

                $id = $this->settings_model->save($input_data);
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_award_program_settings'),
                'value1' => $input_data['name']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('update_msg', lang('award_program') . ' ' . lang('settings'));
            set_message($type, $message);
        }
        redirect('admin/settings/award_rule_settingh');
    }

    public function awardprogramlist()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_award_program';
            $this->datatables->join_table = array('tbl_award_rule', 'tbl_client');
            $this->datatables->join_where = array('tbl_award_rule.award_rule_id=tbl_award_program.award_rule_id', 'tbl_client.client_id=tbl_award_program.client_id');
            $custom_field = custom_form_table_search(15);
            $action_array = array('award_program_id');
            $main_column = array('program_name', 'rule_name', 'tbl_client.name', 'start_date', 'end_date', 'status');
            $result = array_merge($main_column, $custom_field, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('award_program_id' => 'desc');
            $fetch_data = $this->datatables->get_datatable_permission();
            // print('<pre>'.print_r($fetch_data,true).'</pre>'); exit;
            $edited = $can_do = can_do(111);
            $deleted = can_do(111);
            // print('<pre>'.print_r($fetch_data,true).'</pre>'); exit;    
            $data = array();
            foreach ($fetch_data as $_key => $v_rule) {
                $profile_info = $this->db->where('client_id', $v_rule->client_id)->get('tbl_client')->row();
                $rule_name = $this->db->where('award_rule_id', $v_rule->award_rule_id)->get('tbl_award_rule')->row()->rule_name;
                $can_edit = $this->settings_model->can_action('tbl_award_program', 'edit', array('award_program_id ' => $v_rule->award_program_id));
                $can_delete = $this->settings_model->can_action('tbl_award_program', 'delete', array('award_program_id ' => $v_rule->award_program_id));
                if (!empty($profile_info->name)) {
                    $clientname = $profile_info->name;
                } else {
                    $clientname = '-';
                }

                $action = null;
                $sub_array = array();
                $sub_array[] = $v_rule->program_name;
                $sub_array[] = $rule_name;
                $sub_array[] = $clientname;
                $sub_array[] = display_date($v_rule->start_date);
                $sub_array[] = display_date($v_rule->end_date);
                $sub_array[] = display_date($v_rule->end_date);

                if ($v_rule->status == 1) {
                    $statusss = "<span class='label label-success'>" . lang('active') . "</span>";
                } else {
                    $statusss = "<span class='label label-danger'>" . lang('deactive') . "</span>";
                };

                $sub_array[] = $statusss;
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= '<a href="' . base_url() . 'admin/settings/new_rule/' . $v_rule->award_rule_id . '"
                               class="btn btn-primary btn-xs" title="' . lang('edit') . '" data-toggle="modal"
                               data-target="#myModal_large"><span class="fa fa-pencil-square-o"></span></a>  ';
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url('admin/settings/delete_new_rule/' . $v_rule->award_rule_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }


                $sub_array[] = $action;
                $data[] = $sub_array;
            }

            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function new_rule($id = null)
    {


        $data['title'] = lang('new') . ' ' . lang('award');
        $data['all_client'] = $this->settings_model->select_data('tbl_client', 'client_id', 'name');
        if (!empty($id)) {

            $can_edit = $this->settings_model->can_action('tbl_award_rule', 'edit', array('award_rule_id' => $id));
            $edited = can_action('11', 'edited');
            if (!empty($can_edit) && !empty($edited)) {

                $data['awardrule_info'] = $this->db->where('award_rule_id', $id)->get('tbl_award_rule')->row();
                // $this->settings_model->('tbl_award_rule',array('award_rule_id' =>$id));
            }
            // print('<pre>'.print_r($data,true).'</pre>'); exit;


        }
        $data['subview'] = $this->load->view('admin/settings/new_rule', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

    public function awardrulelist()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_award_rule';
            $this->datatables->join_table = array('tbl_client');
            $this->datatables->join_where = array('tbl_award_rule.client_id=tbl_client.client_id');
            $custom_field = custom_form_table_search(15);
            $action_array = array('award_rule_id');
            $main_column = array('rule_name', 'tbl_client.name', 'award_point_from', 'award_point_to', 'card', 'date_create');
            $result = array_merge($main_column, $custom_field, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('award_rule_id ' => 'desc');
            $fetch_data = $this->datatables->get_datatable_permission();

            $edited = can_action('204', 'edited');
            $created = can_action('204', 'created');
            $deleted = can_action('204', 'deleted');


            $data = array();
            foreach ($fetch_data as $_key => $v_rule) {
                $profile_info = $this->db->where('client_id', $v_rule->client_id)->get('tbl_client')->row();
                $can_edit = $this->settings_model->can_action('tbl_award_rule', 'edit', array('award_rule_id' => $v_rule->award_rule_id));
                $can_delete = $this->settings_model->can_action('tbl_award_rule', 'delete', array('award_rule_id' => $v_rule->award_rule_id));
                if (!empty($profile_info->name)) {
                    $clientname = $profile_info->name;
                } else {
                    $clientname = '-';
                }

                $action = null;
                $sub_array = array();
                $sub_array[] = $v_rule->rule_name;
                $sub_array[] = $clientname;
                $sub_array[] = $v_rule->award_point_from;
                $sub_array[] = $v_rule->award_point_to;
                $sub_array[] = $v_rule->card;
                $sub_array[] = display_date($v_rule->date_create);


                if (!empty($created) && !empty($edited)) {
                    $action .= '<a href="' . base_url() . 'admin/settings/new_rule/' . $v_rule->award_rule_id . '"
                               class="btn btn-primary btn-xs" title="' . lang('edit') . '" data-toggle="modal"
                               data-target="#myModal_large"><span class="fa fa-pencil-square-o"></span></a>  ';
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url('admin/settings/delete_new_rule/' . $v_rule->award_rule_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }


                $sub_array[] = $action;
                $data[] = $sub_array;
            }

            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function save_award_rule($id = null)
    {
        $can_do = can_do(155);


        if (!empty($can_do)) {
            $input_data = $this->settings_model->array_from_post(array('rule_name', 'client_id', 'card', 'award_point_to', 'award_point_from', 'description'));

            $input_data['date_create'] = jdate('Y-m-d');

            $this->settings_model->_table_name = 'tbl_award_rule';
            $this->settings_model->_primary_key = 'award_rule_id';
            if (!empty($id)) {

                $id = $this->settings_model->save($input_data, $id);
            } else {
                $id = $this->settings_model->save($input_data);
            }

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_save_award_rule_settings'),
                'value1' => $input_data['name']
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('update_msg', lang('purchase') . ' ' . lang('settings'));
            set_message($type, $message);
        }
        redirect('admin/settings/award_rule_settingh');
    }

    public function card_config()
    {

        $data['page'] = lang('settings');
        $data['load_setting'] = 'award_config';
        $data['title'] = lang('award_config') . ' ' . lang('settings'); //Page title
        $data['all_client'] = $this->settings_model->select_data('tbl_client', 'client_id', 'name');
        $can_do = can_do(155);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function invoice_layout($layout = null, $type = null)
    {
        $data['type'] = (!empty($type) ? $type : 'view');
        if (empty($layout)) {
            $layout = config_item('invoice_layout_' . $data['type']);
        }
        $iLayout = $this->input->post('layout', TRUE);
        if (!empty($iLayout)) {
            $layout = $iLayout;
        }
        $data['page'] = lang('settings');
        $data['load_setting'] = 'invoice_layout';
        $data['title'] = lang('invoice_layout') . ' ' . lang('settings'); //Page title

        $data['layout'] = $layout;
        $sales_info = get_result('tbl_invoices')[0];
        $data['sales_info'] = join_data('tbl_invoices', '*', array('invoices_id' => $sales_info->invoices_id), array('tbl_client' => 'tbl_client.client_id = tbl_invoices.client_id'));
        $url = base_url('frontend/view_invoice/') . url_encode($data['sales_info']->invoices_id);
        $data['qrcode'] = generate_qrcode($url);
        $data['sales_info']->ref_no = lang('invoice') . ' : ' . $data['sales_info']->reference_no;
        $data['sales_info']->start_date = lang('invoice_date') . ' : ' . display_date($data['sales_info']->invoice_date);
        $data['sales_info']->end_date = lang('due_date') . ' : ' . display_date($data['sales_info']->due_date);
        if (!empty($data['sales_info']->user_id)) {
            $data['sales_info']->sales_agent = lang('sales') . ' ' . lang('agent') . ' : ' . fullname($data['sales_info']->user_id);
        }
        $payment_status = $this->invoice_model->get_payment_status($data['sales_info']->invoices_id);
        if ($payment_status == lang('fully_paid')) {
            $label = "success";
        } elseif ($payment_status == lang('draft')) {
            $label = "default";
            $text = lang('status_as_draft');
        } elseif ($payment_status == lang('cancelled')) {
            $label = "danger";
        } elseif ($payment_status == lang('partially_paid')) {
            $label = "warning";
        } elseif ($data['sales_info']->emailed == 'Yes') {
            $label = "info";
            $payment_status = lang('sent');
        } else {
            $label = "danger";
        }
        $data['sales_info']->status = lang('status') . ' :  <span class="label label-' . $label . '">' . lang($payment_status) . '</span>';
        if (!empty($text)) {
            $data['sales_info']->status .= '<br><p style="padding: 15px;margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;;background: color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;">' . $text . '</p>';
        }
        $data['all_items'] = $this->invoice_model->ordered_items_by_id($data['sales_info']->invoices_id);
        $data['sales_info']->sub_total = ($this->invoice_model->calculate_to('invoice_cost', $data['sales_info']->invoices_id));
        $data['sales_info']->discount = ($this->invoice_model->calculate_to('discount', $data['sales_info']->invoices_id));
        $data['sales_info']->total = ($this->invoice_model->calculate_to('total', $data['sales_info']->invoices_id));
        $data['paid_amount'] = $this->invoice_model->calculate_to('paid_amount', $data['sales_info']->invoices_id);
        $data['invoice_due'] = $this->invoice_model->calculate_to('invoice_due', $data['sales_info']->invoices_id);
        $data['payment_status'] = $payment_status;
        $data['all_payment_info'] = get_result('tbl_payments', array('invoices_id' => $data['sales_info']->invoices_id));
        $data['footer'] = config_item('invoice_footer');
        $data['sales_info']->custom_field = '';
        if (!empty($iLayout)) {
            $_data['subview'] = $this->load->view('admin/common/sales/' . $layout, $data, TRUE);
            echo json_encode($_data);
            exit();
        }
        $data['subview'] = $this->load->view('admin/settings/invoice_layout', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
}
