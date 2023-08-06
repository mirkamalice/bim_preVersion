<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Credit_note extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('credit_note_model');
        $this->load->library('gst');
    }

    public function index($action = NULL, $id = NULL, $item_id = NULL)
    {
        $data['page'] = lang('sales');
        $data['sub_active'] = lang('credit_note');
        // get all_credit_note_info

        if ($action == 'project') {
            $data['project_id'] = $id;
            $data['project_info'] = $this->credit_note_model->check_by(array('project_id' => $id), 'tbl_project');
            $data['active'] = 2;
        } else {
            $data['active'] = 1;
        }
        // get permission user
        $data['permission_user'] = $this->credit_note_model->all_permission_user('156');
        if ($action == 'credit_note_details') {
            redirect('admin/credit_note/credit_note_details/' . $id);
        } elseif ($action == 'credit_note_history') {
            $data['credit_note_info'] = $this->credit_note_model->check_by(array('credit_note_id' => $id), 'tbl_credit_note');
            $data['title'] = "Credit Note History"; //Page title
            $subview = 'credit_note_history';
            $data['all_credit_note_info'] = $this->credit_note_model->get_permission('tbl_credit_note');
        } elseif ($action == 'email_credit_note') {
            $data['credit_note_info'] = $this->credit_note_model->check_by(array('credit_note_id' => $id), 'tbl_credit_note');
            $data['title'] = "Email Credit Note"; //Page title
            $subview = 'email_credit_note';
            $data['all_credit_note_info'] = $this->credit_note_model->get_permission('tbl_credit_note');
        } elseif ($action == 'pdf_credit_note') {
            $data['credit_note_info'] = $this->credit_note_model->check_by(array('credit_note_id' => $id), 'tbl_credit_note');
            $data['title'] = "Credit Note PDF"; //Page title
            $this->load->helper('dompdf');
            $viewfile = $this->load->view('admin/credit_note/credit_note_pdf', $data, TRUE);
            pdf_create($viewfile, slug_it('Credit Note  # ' . $data['credit_note_info']->reference_no));
        } else {
            $data['title'] = "Credit Note"; //Page title
            $subview = 'credit_note';
            $data['all_client'] = get_result('tbl_client');
        }
        $data['subview'] = $this->load->view('admin/credit_note/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function credit_note_details($id, $pdf = NULL)
    {
        $data['title'] = lang('credit_note'); //Page title
        $data['sales_info'] = join_data('tbl_credit_note', '*', array('credit_note_id' => $id), array('tbl_client' => 'tbl_client.client_id = tbl_credit_note.credit_note_id'));
        if (!empty($data['sales_info'])) {
            $data['sales_info']->ref_no = lang('credit_note') . ' : ' . $data['sales_info']->reference_no;
            $data['sales_info']->start_date = lang('credit_note_date') . ' : ' . display_date($data['sales_info']->credit_note_date);
            $data['sales_info']->end_date = '';
            if (!empty($data['sales_info']->user_id)) {
                $data['sales_info']->sales_agent = lang('sales') . ' ' . lang('agent') . ' : ' . fullname($data['sales_info']->user_id);
            }
            if ($data['sales_info']->status == 'accepted') {
                $label = 'success';
            } else {
                $label = 'danger';
            }

            // check overdue invoice
            if (strtotime($data['sales_info']->due_date) < strtotime(date('Y-m-d')) && $data['sales_info']->status == 'draft') {
                // check overdue how many days from due_date
                $date1 = new DateTime($data['sales_info']->due_date);
                $date2 = new DateTime(date('Y-m-d'));
                $interval = $date1->diff($date2);
                $overdue_days = $interval->format('%a');
                $data['sales_info']->overdue_days = lang('credit_note_overdue') . ' ' . lang('by') . ' ' . $overdue_days . ' ' . lang('days');

            }

            $data['sales_info']->status = lang('credit_note_status') . ' :  <span class="label label-' . $label . '">' . lang($data['sales_info']->status) . '</span>';
            $data['sales_info']->custom_field = '';
            $show_custom_fields = custom_form_label(10, $data['sales_info']->credit_note_id);
            if (!empty($show_custom_fields)) {
                foreach ($show_custom_fields as $c_label => $v_fields) {
                    if (!empty($v_fields)) {
                        $data['sales_info']->custom_field .= $c_label . ' : ' . $v_fields . '<br>';
                    }
                }
            }
            $data['items_id'] = 'credit_note_items_id';
            $data['all_items'] = $this->credit_note_model->ordered_items_by_id($data['sales_info']->credit_note_id);
            $data['sales_info']->sub_total = ($this->credit_note_model->credit_note_calculation('credit_note_cost', $data['sales_info']->credit_note_id));
            $data['sales_info']->discount = display_money($this->credit_note_model->credit_note_calculation('discount', $data['sales_info']->credit_note_id));
            $data['sales_info']->total = ($this->credit_note_model->credit_note_calculation('total', $data['sales_info']->credit_note_id));

            // get payment info by id
            $data['footer'] = config_item('credit_note_footer');
        } else {
            set_message('error', 'No data Found');
            redirect('admin/credit_note');
        }
        if (!empty($pdf)) {
            $this->common_model->sales_pdf($data, $pdf);
        }
        $data['subview'] = $this->load->view('admin/credit_note/credit_note_details', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load

    }

    public function createcreditnote($action = NULL, $id = NULL, $item_id = NULL)
    {
        $data['page'] = lang('sales');
        $data['sub_active'] = lang('credit_note');
        // get all_credit_note_info
        if (!empty($item_id)) {
            $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $id));
            if (!empty($can_edit)) {
                $data['item_info'] = $this->credit_note_model->check_by(array('credit_note_items_id' => $item_id), 'tbl_credit_note_items');
            }
        }
        if ($action == 'edit_credit_note') {
            $data['active'] = 2;
            $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $id));
            if (!empty($can_edit)) {
                $data['credit_note_info'] = $this->credit_note_model->check_by(array('credit_note_id' => $id), 'tbl_credit_note');
                if (!empty($data['credit_note_info']->client_id)) {
                    $data['credit_note_to_merge'] = $this->credit_note_model->check_for_merge_credit_note($data['credit_note_info']->client_id, $id);
                }
            }
        }
        // get all client
        $this->credit_note_model->_table_name = 'tbl_client';
        $this->credit_note_model->_order_by = 'client_id';
        $data['all_client'] = $this->credit_note_model->get();
        // get permission user
        $data['permission_user'] = $this->credit_note_model->all_permission_user('156');

        $data['title'] = "Credit Note"; //Page title
        $subview = 'createcreditnote';
        $data['subview'] = $this->load->view('admin/credit_note/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function credit_note_state_report()
    {
        if (!$this->input->is_ajax_request()) {
            redirect('admin/dashboard');
        }
        $data = array();
        $pathonor_jonno = array();
        $pathonor_jonno['state_report'] = $this->load->view("admin/credit_note/credit_note_state_report", $data, true);
        echo json_encode($pathonor_jonno);
        exit;
    }

    public function credit_noteList($filterBy = null, $search_by = null)
    {
        if ($this->input->is_ajax_request()) {
            $edited = can_action('156', 'edited');
            $deleted = can_action('156', 'deleted');
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_credit_note';
            $this->datatables->join_table = array('tbl_client');
            $this->datatables->join_where = array('tbl_credit_note.client_id=tbl_client.client_id');
            $this->datatables->select = 'tbl_credit_note.*,tbl_client.name';
            $custom_field = custom_form_table_search(22);
            $main_column = array('reference_no', 'credit_note_date', 'tbl_client.name', 'tbl_credit_note.status', 'tags');
            $action_array = array('credit_note_id');
            $result = array_merge($main_column, $custom_field, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('credit_note_id' => 'desc');

            if (!empty($edited) && !empty($deleted)) {
                if (empty($filterBy)) {
                    $filterBy = '_' . date('Y');
                }
            }
            if (!empty($filterBy) && !is_numeric($filterBy)) {
                $ex = explode('_', $filterBy);
                if ($ex[0] != 'c') {
                    $filterBy = $filterBy;
                }
            }
            $where = array();
            $where_in = null;
            if (!empty($search_by)) {
                if ($search_by == 'by_project') {
                    $where = array('project_id' => $filterBy);
                }
                if ($search_by == 'by_agent') {
                    $where = array('user_id' => $filterBy);
                }
                if ($search_by == 'by_client') {
                    $where = array('tbl_credit_note.client_id' => $filterBy);
                }
            } else {
                if ($filterBy == 'last_month' || $filterBy == 'this_months') {
                    if ($filterBy == 'last_month') {
                        $month = date('Y-m', strtotime('-1 months'));
                    } else {
                        $month = date('Y-m');
                    }
                    $where = array('credit_note_month' => $month);
                } else if (strstr($filterBy, '_')) {
                    $year = str_replace('_', '', $filterBy);
                    $where = array('credit_note_year' => $year);
                } else if (!empty($filterBy) && $filterBy != 'all') {
                    $where = array('status' => $filterBy);
                }
            }
            // get all credit_note
            $fetch_data = make_datatables($where);

            $data = array();


            foreach ($fetch_data as $_key => $v_credit_note) {
                if (!empty($v_credit_note)) {
                    $action = null;
                    $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $v_credit_note->credit_note_id));
                    $can_delete = $this->credit_note_model->can_action('tbl_credit_note', 'delete', array('credit_note_id' => $v_credit_note->credit_note_id));

                    if ($v_credit_note->status == 'refund') {
                        $label = "info";
                    } elseif ($v_credit_note->status == 'open') {
                        $label = "success";
                    } else {
                        $label = "danger";
                    }

                    $sub_array = array();
                    $name = null;
                    $name .= '<a class="text-info" href="' . base_url() . 'admin/credit_note/index/credit_note_details/' . $v_credit_note->credit_note_id . '">' . $v_credit_note->reference_no . '</a>';
                    $sub_array[] = $name;
                    $sub_array[] = display_date($v_credit_note->credit_note_date);
                    $sub_array[] = '<span class="tags">' . client_name($v_credit_note->client_id) . '</span>';
                    $sub_array[] = display_money($this->credit_note_model->credit_note_calculation('total', $v_credit_note->credit_note_id), client_currency($v_credit_note->client_id));
                    $sub_array[] = "<span class='tags label label-" . $label . "'>" . lang($v_credit_note->status) . "</span>";
                    $sub_array[] = get_tags($v_credit_note->tags, true);

                    $custom_form_table = custom_form_table(22, $v_credit_note->credit_note_id);

                    if (!empty($custom_form_table)) {
                        foreach ($custom_form_table as $c_label => $v_fields) {
                            $sub_array[] = $v_fields;
                        }
                    }
                    if (!empty($can_edit) && !empty($edited)) {
                        $action .= btn_edit('admin/credit_note/createcreditnote/edit_credit_note/' . $v_credit_note->credit_note_id) . ' ';
                    }
                    if (!empty($can_delete) && !empty($deleted)) {
                        $action .= ajax_anchor(base_url("admin/credit_note/delete/delete_credit_note/$v_credit_note->credit_note_id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                    }
                    $sub_array[] = $action;
                    $data[] = $sub_array;
                }
            }
            render_table($data, $where);
        } else {
            redirect('admin/dashboard');
        }
    }

    public
    function client_change_data($customer_id, $current_invoice = 'undefined')
    {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $data['client_currency'] = $this->credit_note_model->client_currency_symbol($customer_id);
            $_data['credit_note_to_merge'] = $this->credit_note_model->check_for_merge_credit_note($customer_id, $current_invoice);
            $data['merge_info'] = $this->load->view('admin/credit_note/merge_credit_note', $_data, true);
            echo json_encode($data);
            exit();
        }
    }

    public
    function invoice_credited($credit_note_id)
    {
        $data['title'] = lang('invoice_credited');
        $data['all_credit_used'] = get_result('tbl_credit_used', array('credit_note_id' => $credit_note_id));
        $data['subview'] = $this->load->view('admin/credit_note/invoice_credited', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public
    function credit_invoices($credit_note_id)
    {
        $data['active'] = 2;
        $data['credit_note'] = get_row('tbl_credit_note', array('credit_note_id' => $credit_note_id));
        if ($data['credit_note']->status = 'open') {
            $data['all_invoices'] = get_result('tbl_invoices', array('status !=' => 'Cancelled', 'client_id' => $data['credit_note']->client_id));
            $data['subview'] = $this->load->view('admin/credit_note/credits_to_invoices', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data);
        } else {
            $type = "error";
            $message = "No Record Found";
            set_message($type, $message);
            redirect('admin/credit_note');
        }
    }

    public
    function apply_credit_invoices($credit_note_id)
    {
        $invoice_amount = $this->input->post('amount', true);
        $added_into_payment = $this->input->post('added_into_payment', true);
        if ($invoice_amount) {
            foreach ($invoice_amount as $invoices_id => $amount) {
                if (!empty($amount)) {
                    $this->load->model('invoice_model');
                    $due = $this->invoice_model->calculate_to('invoice_due', $invoices_id);
                    $inv_info = $this->invoice_model->check_by(array('invoices_id' => $invoices_id), 'tbl_invoices');
                    if ($amount > $due) {
                        // messages for user
                        $error[] = lang('overpaid_amount') . ' the ' . $inv_info->reference_no;
                    } else {
                        $this->credit_note_model->apply_credits($credit_note_id, ['amount' => $amount, 'invoices_id' => $invoices_id, 'added_into_payment' => $added_into_payment]);
                    }
                }
            }
        }
        if (!empty($error)) {
            foreach ($error as $show) {
                set_message('error', $show);
            }
        }
        set_message('success', lang('credit_applied_to_invoices'));
        redirect('admin/credit_note/index/credit_note_details/' . $credit_note_id);
    }


    public
    function get_merge_data($id)
    {
        $invoice_items = $this->credit_note_model->ordered_items_by_id($id);
        $i = 0;
        foreach ($invoice_items as $item) {
            $invoice_items[$i]->taxname = $this->credit_note_model->get_invoice_item_taxes($item->credit_note_items_id, 'credit_note');
            $invoice_items[$i]->qty = $item->quantity;
            $invoice_items[$i]->rate = $item->unit_cost;
            $i++;
        }
        echo json_encode($invoice_items);
        exit();
    }

    public
    function pdf_credit_note($id)
    {
        $this->credit_note_details($id, true);
    }

    public
    function save_credit_note($id = NULL)
    {
        $created = can_action('156', 'created');
        $edited = can_action('156', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $data = $this->credit_note_model->array_from_post(array('reference_no', 'client_id', 'project_id', 'discount_type', 'tags', 'discount_percent', 'adjustment', 'discount_total', 'show_quantity_as'));
            $data['client_visible'] = ($this->input->post('client_visible') == 'Yes') ? 'Yes' : 'No';
            $data['credit_note_date'] = date('Y-m-d', strtotime($this->input->post('credit_note_date', TRUE)));
            if (empty($data['credit_note_date'])) {
                $data['credit_note_date'] = date('Y-m-d');
            }
            $data['credit_note_year'] = date('Y', strtotime($this->input->post('credit_note_date', TRUE)));
            $data['credit_note_month'] = date('Y-m', strtotime($this->input->post('credit_note_date', TRUE)));
            $data['warehouse_id'] = $this->input->post('warehouse_id', TRUE);
            $data['notes'] = $this->input->post('notes', TRUE);
            $tax['tax_name'] = $this->input->post('total_tax_name', TRUE);
            $tax['total_tax'] = $this->input->post('total_tax', TRUE);
            $data['total_tax'] = json_encode($tax);
            $data['user_id'] = my_id();
            $i_tax = 0;
            if (!empty($tax['total_tax'])) {
                foreach ($tax['total_tax'] as $v_tax) {
                    $i_tax += $v_tax;
                }
            }
            $data['tax'] = $i_tax;
            $status = $this->input->post('status', TRUE);
            if (!empty($status)) {
                $data['status'] = $status;
            }
            $currency = $this->credit_note_model->client_currency_symbol($data['client_id']);
            if (!empty($currency->code)) {
                $curren = $currency->code;
            } else {
                $curren = config_item('default_currency');
            }
            $data['currency'] = $curren;

            $permission = $this->input->post('permission', true);
            if (!empty($permission)) {
                if ($permission == 'everyone') {
                    $assigned = 'all';
                } else {
                    $assigned_to = $this->credit_note_model->array_from_post(array('assigned_to'));
                    if (!empty($assigned_to['assigned_to'])) {
                        foreach ($assigned_to['assigned_to'] as $assign_user) {
                            $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                        }
                    }
                }
                if (!empty($assigned)) {
                    if ($assigned != 'all') {
                        $assigned = json_encode($assigned);
                    }
                } else {
                    $assigned = 'all';
                }
                $data['permission'] = $assigned;
            } else {
                set_message('error', lang('assigned_to') . ' Field is required');
                if (empty($_SERVER['HTTP_REFERER'])) {
                    redirect('admin/credit_note');
                } else {
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }

            // get all client
            $this->credit_note_model->_table_name = 'tbl_credit_note';
            $this->credit_note_model->_primary_key = 'credit_note_id';
            if (!empty($id)) {
                $credit_note_id = $id;
                $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $id));
                if (!empty($can_edit)) {
                    $this->credit_note_model->save($data, $id);
                } else {
                    set_message('error', lang('there_in_no_value'));
                    redirect('admin/credit_note');
                }
                $this->credit_note_model->save($data, $id);
                $action = ('activity_credit_note_updated');
                $msg = lang('credit_note_updated');
                $description = 'not_credit_note_updated';
            } else {
                $credit_note_id = $this->credit_note_model->save($data);
                $action = ('activity_credit_note_created');
                $description = 'not_credit_note_created';
                $msg = lang('credit_note_created');
            }
            save_custom_field(22, $credit_note_id);

            // save items
            $invoices_to_merge = $this->input->post('invoices_to_merge', TRUE);
            $cancel_merged_invoices = $this->input->post('cancel_merged_credit_note', TRUE);
            if (!empty($invoices_to_merge)) {
                foreach ($invoices_to_merge as $inv_id) {
                    if (empty($cancel_merged_invoices)) {
                        $this->db->where('credit_note_id', $inv_id);
                        $this->db->delete('tbl_credit_note');

                        $this->db->where('credit_note_id', $inv_id);
                        $this->db->delete('tbl_credit_note_items');
                    } else {
                        $mdata = array('status' => 'cancelled');
                        $this->credit_note_model->_table_name = 'tbl_credit_note';
                        $this->credit_note_model->_primary_key = 'credit_note_id';
                        $this->credit_note_model->save($mdata, $inv_id);
                    }
                }
            }

            $removed_items = $this->input->post('removed_items', TRUE);
            if (!empty($removed_items)) {
                foreach ($removed_items as $r_id) {
                    if ($r_id != 'undefined') {
                        $this->db->where('credit_note_items_id', $r_id);
                        $this->db->delete('tbl_credit_note_items');
                    }
                }
            }

            $itemsid = $this->input->post('credit_note_items_id', TRUE);
            $items_data = $this->input->post('items', true);
            if (!empty($items_data)) {
                $index = 0;
                foreach ($items_data as $items) {
                    $items['credit_note_id'] = $credit_note_id;
                    unset($items['invoice_items_id']);
                    unset($items['total_qty']);
                    if (empty($items['saved_items_id']) && $items['saved_items_id'] == 'undefined') {
                        $items['saved_items_id'] = 0;
                    }
                    $tax = 0;
                    if (!empty($items['taxname'])) {
                        foreach ($items['taxname'] as $tax_name) {
                            $tax_rate = explode("|", $tax_name);
                            $tax += $tax_rate[1];
                        }
                        $items['item_tax_name'] = $items['taxname'];
                        unset($items['taxname']);
                        $items['item_tax_name'] = json_encode($items['item_tax_name']);
                    }
                    $price = $items['quantity'] * $items['unit_cost'];
                    $items['item_tax_total'] = ($price / 100 * $tax);
                    $items['total_cost'] = $price;
                    // get all client
                    $this->credit_note_model->_table_name = 'tbl_credit_note_items';
                    $this->credit_note_model->_primary_key = 'credit_note_items_id';
                    if (!empty($items['items_id'])) {
                        $items_id = $items['items_id'];
                        unset($items['items_id']);
                        $this->credit_note_model->save($items, $items_id);
                    } else {
                        $items_id = $this->credit_note_model->save($items);
                    }
                    $index++;
                }
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'credit_note',
                'module_field_id' => $credit_note_id,
                'activity' => $action,
                'icon' => 'fa-shopping-cart',
                'link' => 'admin/credit_note/index/credit_note_details/' . $credit_note_id,
                'value1' => $data['reference_no']
            );
            $this->credit_note_model->_table_name = 'tbl_activities';
            $this->credit_note_model->_primary_key = 'activities_id';
            $this->credit_note_model->save($activity);

            // send notification to client
            if (!empty($data['client_id'])) {
                $client_info = $this->credit_note_model->check_by(array('client_id' => $data['client_id']), 'tbl_client');
                if (!empty($client_info->primary_contact)) {
                    $notifyUser = array($client_info->primary_contact);
                } else {
                    $user_info = $this->credit_note_model->check_by(array('company' => $data['client_id']), 'tbl_account_details');
                    if (!empty($user_info)) {
                        $notifyUser = array($user_info->user_id);
                    }
                }
            }
            if (!empty($notifyUser)) {
                foreach ($notifyUser as $v_user) {
                    if ($v_user != $this->session->userdata('user_id')) {
                        add_notification(array(
                            'to_user_id' => $v_user,
                            'icon' => 'shopping-cart',
                            'description' => $description,
                            'link' => 'client/credit_note/index/credit_note_details/' . $credit_note_id,
                            'value' => $data['reference_no'],
                        ));
                    }
                }
                show_notification($notifyUser);
            }

            // messages for user
            $type = "success";
            $message = $msg;
            set_message($type, $message);
        }
        if (!empty($data['project_id']) && is_numeric($data['project_id'])) {
            redirect('admin/projects/project_details/' . $data['project_id']);
        } else {
            redirect('admin/credit_note/index/credit_note_details/' . $credit_note_id);
        }
        redirect('admin/credit_note');
    }

    public
    function insert_items($credit_note_id)
    {
        $edited = can_action('156', 'edited');
        $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $credit_note_id));
        if (!empty($can_edit) && !empty($edited) && !empty($credit_note_id)) {
            $data['credit_note_id'] = $credit_note_id;
            $data['modal_subview'] = $this->load->view('admin/credit_note/_modal_insert_items', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/credit_note');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public
    function add_insert_items($credit_note_id)
    {
        $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $credit_note_id));
        $edited = can_action('156', 'edited');
        if (!empty($can_edit) && !empty($edited)) {
            $saved_items_id = $this->input->post('saved_items_id', TRUE);
            if (!empty($saved_items_id)) {
                foreach ($saved_items_id as $v_items_id) {
                    $items_info = $this->credit_note_model->check_by(array('saved_items_id' => $v_items_id), 'tbl_saved_items');
                    $tax_info = json_decode($items_info->tax_rates_id);
                    $tax_name = array();
                    if (!empty($tax_info)) {
                        foreach ($tax_info as $v_tax) {
                            $all_tax = $this->db->where('tax_rates_id', $v_tax)->get('tbl_tax_rates')->row();
                            $tax_name[] = $all_tax->tax_rate_name . '|' . $all_tax->tax_rate_percent;
                        }
                    }
                    if (!empty($tax_name)) {
                        $tax_name = $tax_name;
                    } else {
                        $tax_name = array();
                    }

                    $data['quantity'] = 1;
                    $data['credit_note_id'] = $credit_note_id;
                    $data['item_name'] = $items_info->item_name;
                    $data['item_desc'] = $items_info->item_desc;
                    $data['hsn_code'] = $items_info->hsn_code;
                    $data['unit_cost'] = $items_info->unit_cost;
                    $data['item_tax_rate'] = '0.00';
                    $data['item_tax_name'] = json_encode($tax_name);
                    $data['item_tax_total'] = $items_info->item_tax_total;
                    $data['total_cost'] = $items_info->unit_cost;

                    $this->credit_note_model->_table_name = 'tbl_credit_note_items';
                    $this->credit_note_model->_primary_key = 'credit_note_items_id';
                    $items_id = $this->credit_note_model->save($data);
                    $action = 'activity_credit_note_items_added';
                    $msg = lang('credit_note_item_save');
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'credit_note',
                        'module_field_id' => $items_id,
                        'activity' => $action,
                        'icon' => 'fa-shopping-cart',
                        'link' => 'admin/credit_note/index/credit_note_details/' . $credit_note_id,
                        'value1' => $items_info->item_name
                    );
                    $this->credit_note_model->_table_name = 'tbl_activities';
                    $this->credit_note_model->_primary_key = 'activities_id';
                    $this->credit_note_model->save($activity);
                }
                $type = "success";
                $this->update_invoice_tax($saved_items_id, $credit_note_id);
            } else {
                $type = "error";
                $msg = 'Please Select a items';
            }
            $message = $msg;
            set_message($type, $message);
            redirect('admin/credit_note/index/credit_note_details/' . $credit_note_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/credit_note');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    function update_invoice_tax($saved_items_id, $credit_note_id)
    {

        $invoice_info = $this->credit_note_model->check_by(array('credit_note_id' => $credit_note_id), 'tbl_credit_note');
        $tax_info = json_decode($invoice_info->total_tax);

        $tax_name = $tax_info->tax_name;
        $total_tax = $tax_info->total_tax;
        $invoice_tax = array();
        if (!empty($tax_name)) {
            foreach ($tax_name as $t_key => $v_tax_info) {
                array_push($invoice_tax, array('tax_name' => $v_tax_info, 'total_tax' => $total_tax[$t_key]));
            }
        }
        $all_tax_info = array();
        if (!empty($saved_items_id)) {
            foreach ($saved_items_id as $v_items_id) {
                $items_info = $this->credit_note_model->check_by(array('saved_items_id' => $v_items_id), 'tbl_saved_items');

                $tax_info = json_decode($items_info->tax_rates_id);
                if (!empty($tax_info)) {
                    foreach ($tax_info as $v_tax) {
                        $all_tax = $this->db->where('tax_rates_id', $v_tax)->get('tbl_tax_rates')->row();
                        array_push($all_tax_info, array('tax_name' => $all_tax->tax_rate_name . '|' . $all_tax->tax_rate_percent, 'total_tax' => $items_info->unit_cost / 100 * $all_tax->tax_rate_percent));
                    }
                }
            }
        }
        if (!empty($invoice_tax) && is_array($invoice_tax) && !empty($all_tax_info)) {
            $all_tax_info = array_merge($all_tax_info, $invoice_tax);
        }

        $results = array();
        foreach ($all_tax_info as $value) {
            if (!isset($results[$value['tax_name']])) {
                $results[$value['tax_name']] = 0;
            }
            $results[$value['tax_name']] += $value['total_tax'];
        }
        if (!empty($results)) {
            foreach ($results as $key => $value) {
                $structured_results['tax_name'][] = $key;
                $structured_results['total_tax'][] = $value;
            }
            $invoice_data['tax'] = array_sum($structured_results['total_tax']);
            $invoice_data['total_tax'] = json_encode($structured_results);

            $this->credit_note_model->_table_name = 'tbl_credit_note';
            $this->credit_note_model->_primary_key = 'credit_note_id';
            $this->credit_note_model->save($invoice_data, $credit_note_id);
        }
        return true;
    }

    public
    function add_item($id = NULL)
    {
        $data = $this->credit_note_model->array_from_post(array('credit_note_id', 'item_order'));
        $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $data['credit_note_id']));
        $edited = can_action('156', 'edited');
        if (!empty($can_edit) && !empty($edited)) {
            $quantity = $this->input->post('quantity', TRUE);
            $array_data = $this->credit_note_model->array_from_post(array('item_name', 'item_desc', 'item_tax_rate', 'unit_cost'));
            if (!empty($quantity)) {
                foreach ($quantity as $key => $value) {
                    $data['quantity'] = $value;
                    $data['item_name'] = $array_data['item_name'][$key];
                    $data['item_desc'] = $array_data['item_desc'][$key];
                    $data['unit_cost'] = $array_data['unit_cost'][$key];
                    $data['item_tax_rate'] = $array_data['item_tax_rate'][$key];
                    $sub_total = $data['unit_cost'] * $data['quantity'];

                    $data['item_tax_total'] = ($data['item_tax_rate'] / 100) * $sub_total;
                    $data['total_cost'] = $sub_total + $data['item_tax_total'];

                    // get all client
                    $this->credit_note_model->_table_name = 'tbl_credit_note_items';
                    $this->credit_note_model->_primary_key = 'credit_note_items_id';
                    if (!empty($id)) {
                        $credit_note_items_id = $id;
                        $this->credit_note_model->save($data, $id);
                        $action = ('activity_credit_note_items_updated');
                    } else {
                        $credit_note_items_id = $this->credit_note_model->save($data);
                        $action = 'activity_credit_note_items_added';
                    }
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'credit_note',
                        'module_field_id' => $credit_note_items_id,
                        'activity' => $action,
                        'icon' => 'fa-shopping-cart',
                        'link' => 'admin/credit_note/index/credit_note_details/' . $data['credit_note_id'],
                        'value1' => $data['item_name']
                    );
                    $this->credit_note_model->_table_name = 'tbl_activities';
                    $this->credit_note_model->_primary_key = 'activities_id';
                    $this->credit_note_model->save($activity);
                }
            }
            // messages for user
            $type = "success";
            $message = lang('credit_note_item_save');
            set_message($type, $message);
            redirect('admin/credit_note/index/credit_note_details/' . $data['credit_note_id']);
        } else {
            set_message('error', lang('there_in_no_value'));
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/credit_note');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public
    function clone_credit_note($credit_note_id)
    {
        $edited = can_action('156', 'edited');
        $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $credit_note_id));
        if (!empty($can_edit) && !empty($edited) && !empty($credit_note_id)) {
            $data['credit_note_info'] = $this->credit_note_model->check_by(array('credit_note_id' => $credit_note_id), 'tbl_credit_note');
            // get all client
            $this->credit_note_model->_table_name = 'tbl_client';
            $this->credit_note_model->_order_by = 'client_id';
            $data['all_client'] = $this->credit_note_model->get();

            $data['modal_subview'] = $this->load->view('admin/credit_note/_modal_clone_credit_note', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data);
        } else {
            set_message('error', lang('there_in_no_value'));
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/credit_note');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public
    function cloned_credit_note($id)
    {
        $edited = can_action('156', 'edited');
        $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $id));
        if (!empty($can_edit) && !empty($edited)) {
            if (config_item('increment_credit_note_number') == 'FALSE') {
                $this->load->helper('string');
                $reference_no = config_item('credit_note_prefix') . ' ' . random_string('nozero', 6);
            } else {
                $reference_no = config_item('credit_note_prefix') . ' ' . $this->credit_note_model->generate_credit_note_number();
            }

            $invoice_info = $this->credit_note_model->check_by(array('credit_note_id' => $id), 'tbl_credit_note');
            $data['credit_note_date'] = date('Y-m-d', strtotime($this->input->post('credit_note_date', TRUE)));
            if (empty($data['credit_note_date'])) {
                $data['credit_note_date'] = date('Y-m-d');
            }
            // save into invoice table
            $new_invoice = array(
                'reference_no' => $reference_no,
                'client_id' => $this->input->post('client_id', true),
                'project_id' => $invoice_info->project_id,
                'credit_note_date' => date('Y-m-d', strtotime($this->input->post('credit_note_date', TRUE))),
                'credit_note_month' => date('Y-m', strtotime($this->input->post('credit_note_date', TRUE))),
                'credit_note_year' => date('Y', strtotime($this->input->post('credit_note_date', TRUE))),
                'notes' => $invoice_info->notes,
                'total_tax' => $invoice_info->total_tax,
                'tax' => $invoice_info->tax,
                'discount_type' => $invoice_info->discount_type,
                'discount_percent' => $invoice_info->discount_percent,
                'user_id' => $invoice_info->user_id,
                'adjustment' => $invoice_info->adjustment,
                'discount_total' => $invoice_info->discount_total,
                'show_quantity_as' => $invoice_info->show_quantity_as,
                'currency' => $invoice_info->currency,
                'status' => $invoice_info->status,
                'date_saved' => $invoice_info->date_saved,
                'emailed' => $invoice_info->emailed,
                'permission' => $invoice_info->permission,
            );
            $this->credit_note_model->_table_name = "tbl_credit_note"; //table name
            $this->credit_note_model->_primary_key = "credit_note_id";
            $new_invoice_id = $this->credit_note_model->save($new_invoice);

            $invoice_items = $this->db->where('credit_note_id', $id)->get('tbl_credit_note_items')->result();

            if (!empty($invoice_items)) {
                foreach ($invoice_items as $new_item) {
                    $items = array(
                        'credit_note_id' => $new_invoice_id,
                        'item_name' => $new_item->item_name,
                        'item_desc' => $new_item->item_desc,
                        'unit_cost' => $new_item->unit_cost,
                        'quantity' => $new_item->quantity,
                        'item_tax_rate' => $new_item->item_tax_rate,
                        'item_tax_name' => $new_item->item_tax_name,
                        'item_tax_total' => $new_item->item_tax_total,
                        'total_cost' => $new_item->total_cost,
                        'unit' => $new_item->unit,
                        'order' => $new_item->order,
                        'date_saved' => $new_item->date_saved,
                    );
                    $this->credit_note_model->_table_name = "tbl_credit_note_items"; //table name
                    $this->credit_note_model->_primary_key = "credit_note_items_id";
                    $this->credit_note_model->save($items);
                }
            }
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'credit_note',
                'module_field_id' => $new_invoice_id,
                'activity' => ('activity_clone_credit_note'),
                'icon' => 'fa-shopping-cart',
                'link' => 'admin/credit_note/index/credit_note_details/' . $new_invoice_id,
                'value1' => ' from ' . $invoice_info->reference_no . ' to ' . $reference_no,
            );
            // Update into tbl_project
            $this->credit_note_model->_table_name = "tbl_activities"; //table name
            $this->credit_note_model->_primary_key = "activities_id";
            $this->credit_note_model->save($activities);

            // messages for user
            $type = "success";
            $message = lang('credit_note_created');
            set_message($type, $message);
            redirect('admin/credit_note/index/credit_note_details/' . $new_invoice_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/credit_note');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public
    function change_status($action, $id)
    {
        $can_edit = $this->credit_note_model->can_action('tbl_credit_note', 'edit', array('credit_note_id' => $id));
        $edited = can_action('156', 'edited');
        if (!empty($can_edit) && !empty($edited)) {
            $where = array('credit_note_id' => $id);
            if ($action == 'sent') {
                $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));
            } elseif (!empty($action)) {
                $data = array('status' => $action);
            }
            $this->credit_note_model->set_action($where, $data, 'tbl_credit_note');
            // messages for user
            $type = "success";
            $message = lang('credit_note_status_changed', $action);
            set_message($type, $message);
            redirect('admin/credit_note/index/credit_note_details/' . $id);
        } else {
            set_message('error', lang('there_in_no_value'));
            if (empty($_SERVER['HTTP_REFERER'])) {
                redirect('admin/credit_note');
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public
    function delete($action, $credit_note_id, $item_id = NULL)
    {
        $can_delete = $this->credit_note_model->can_action('tbl_credit_note', 'delete', array('credit_note_id' => $credit_note_id));
        $deleted = can_action('156', 'deleted');
        if (!empty($can_delete) && !empty($deleted)) {
            if ($action == 'delete_item') {
                $this->credit_note_model->_table_name = 'tbl_credit_note_items';
                $this->credit_note_model->_primary_key = 'credit_note_items_id';
                $this->credit_note_model->delete($item_id);
            } elseif ($action == 'delete_credit_note') {
                $this->credit_note_model->_table_name = 'tbl_credit_note_items';
                $this->credit_note_model->delete_multiple(array('credit_note_id' => $credit_note_id));

                $this->credit_note_model->_table_name = 'tbl_reminders';
                $this->credit_note_model->delete_multiple(array('module' => 'credit_note', 'module_id' => $credit_note_id));

                $this->credit_note_model->_table_name = 'tbl_pinaction';
                $this->credit_note_model->delete_multiple(array('module_name' => 'credit_note', 'module_id' => $credit_note_id));

                $this->credit_note_model->_table_name = 'tbl_credit_used';
                $this->credit_note_model->delete_multiple(array('credit_note_id' => $credit_note_id));

                $this->credit_note_model->_table_name = 'tbl_credit_note';
                $this->credit_note_model->_primary_key = 'credit_note_id';
                $this->credit_note_model->delete($credit_note_id);
            } elseif ($action == 'delete_invoice_credited') {

                $credit_used = get_row('tbl_credit_used', array('credit_used_id' => $item_id));
                if (!empty($credit_used->payments_id) && $credit_used->payments_id != 0) {
                    $this->credit_note_model->_table_name = 'tbl_payments';
                    $this->credit_note_model->_primary_key = 'payments_id';
                    $this->credit_note_model->delete($credit_used->payments_id);
                }
                $this->credit_note_model->_table_name = 'tbl_credit_used';
                $this->credit_note_model->_primary_key = 'credit_used_id';
                $this->credit_note_model->delete($item_id);
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'credit_note',
                'module_field_id' => $credit_note_id,
                'activity' => ('activity_' . $action),
                'icon' => 'fa-shopping-cart',
                'value1' => $action
            );

            $this->credit_note_model->_table_name = 'tbl_activities';
            $this->credit_note_model->_primary_key = 'activities_id';
            $this->credit_note_model->save($activity);
            $type = 'success';
            if ($action == 'delete_item') {
                $text = lang('credit_note_item_deleted');
            } else if ($action == 'delete_invoice_credited') {
                $text = lang('invoice_credited') . ' ' . ('deleted');
            } else {
                $text = lang('credit_note_deleted');
            }
            echo json_encode(array("status" => $type, 'message' => $text));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public
    function send_credit_note_email($credit_note_id, $row = null)
    {
        if (!empty($row)) {
            $credit_note_info = $this->credit_note_model->check_by(array('credit_note_id' => $credit_note_id), 'tbl_credit_note');
            $client_info = $this->credit_note_model->check_by(array('client_id' => $credit_note_info->client_id), 'tbl_client');
            if (!empty($client_info)) {
                $client = $client_info->name;
                $currency = $this->credit_note_model->client_currency_symbol($client_info->client_id);;
            } else {
                $client = '-';
                $currency = $this->credit_note_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
            }

            $amount = $this->credit_note_model->credit_note_calculation('total', $credit_note_info->credit_note_id);
            $currency = $currency->code;
            $email_template = email_templates(array('email_group' => 'credit_note_email'), $client_info->client_id);
            $message = $email_template->template_body;
            $ref = $credit_note_info->reference_no;
            $subject = $email_template->subject;
        } else {
            $message = $this->input->post('message', TRUE);
            $ref = $this->input->post('ref', TRUE);
            $subject = $this->input->post('subject', TRUE);
            $client = $this->input->post('client_name', TRUE);
            $amount = $this->input->post('amount', true);
            $currency = $this->input->post('currency', TRUE);
        }
        $client_name = str_replace("{CLIENT}", $client, $message);
        $Ref = str_replace("{credit_note_REF}", $ref, $client_name);
        $Amount = str_replace("{AMOUNT}", $amount, $Ref);
        $Currency = str_replace("{CURRENCY}", $currency, $Amount);
        $link = str_replace("{credit_note_LINK}", base_url() . 'client/credit_note/index/credit_note_details/' . $credit_note_id, $Currency);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $link);


        $this->send_email_credit_note($credit_note_id, $message, $subject); // Email credit_note

        $data = array('status' => 'sent', 'emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

        $this->credit_note_model->_table_name = 'tbl_credit_note';
        $this->credit_note_model->_primary_key = 'credit_note_id';
        $this->credit_note_model->save($data, $credit_note_id);

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'credit_note',
            'module_field_id' => $credit_note_id,
            'activity' => 'activity_credit_note_sent',
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/credit_note/index/credit_note_details/' . $credit_note_id,
            'value1' => $ref
        );
        $this->credit_note_model->_table_name = 'tbl_activities';
        $this->credit_note_model->_primary_key = 'activities_id';
        $this->credit_note_model->save($activity);

        $type = 'success';
        $text = lang('credit_note_email_sent');
        set_message($type, $text);
        redirect('admin/credit_note/index/credit_note_details/' . $credit_note_id);
    }

    function send_email_credit_note($credit_note_id, $message, $subject)
    {
        $credit_note_info = $this->credit_note_model->check_by(array('credit_note_id' => $credit_note_id), 'tbl_credit_note');
        $client_info = $this->credit_note_model->check_by(array('client_id' => $credit_note_info->client_id), 'tbl_client');

        $recipient = $client_info->email;

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);
        $params = array(
            'recipient' => $recipient,
            'subject' => $subject,
            'message' => $message
        );
        $params['resourceed_file'] = 'uploads/' . slug_it(lang('credit_note') . '_pdf_' . $credit_note_info->reference_no) . '.pdf';
        $params['resourcement_url'] = base_url() . 'uploads/' . slug_it(lang('credit_note') . '_pdf_' . $credit_note_info->reference_no) . '.pdf';

        $this->attach_pdf($credit_note_id);
        $this->credit_note_model->send_email($params);
        //Delete credit_note in tmp folder
        if (is_file('uploads/' . slug_it(lang('credit_note') . '_pdf_' . $credit_note_info->reference_no) . '.pdf')) {
            unlink('uploads/' . slug_it(lang('credit_note') . '_pdf_' . $credit_note_info->reference_no) . '.pdf');
        }
        // send notification to client
        if (!empty($client_info->primary_contact)) {
            $notifyUser = array($client_info->primary_contact);
        } else {
            $user_info = $this->credit_note_model->check_by(array('company' => $credit_note_info->client_id), 'tbl_account_details');
            if (!empty($user_info)) {
                $notifyUser = array($user_info->user_id);
            }
        }
        if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
                if ($v_user != $this->session->userdata('user_id')) {
                    add_notification(array(
                        'to_user_id' => $v_user,
                        'icon' => 'shopping-cart',
                        'description' => 'not_email_send_alert',
                        'link' => 'client/credit_note/index/credit_note_details/' . $credit_note_id,
                        'value' => lang('credit_note') . ' ' . $credit_note_info->reference_no,
                    ));
                }
            }
            show_notification($notifyUser);
        }
    }


    public
    function attach_pdf($id)
    {
        $this->credit_note_details($id, 'attach');
    }

    function credit_note_email($credit_note_id)
    {
        $data['credit_note_info'] = $this->credit_note_model->check_by(array('credit_note_id' => $credit_note_id), 'tbl_credit_note');
        $credit_note_info = $data['credit_note_info'];
        $client_info = $this->credit_note_model->check_by(array('client_id' => $data['credit_note_info']->client_id), 'tbl_client');

        $recipient = $client_info->email;

        $message = $this->load->view('admin/credit_note/credit_note_pdf', $data, TRUE);

        $data['message'] = $message;

        $message = $this->load->view('email_template', $data, TRUE);
        $params = array(
            'recipient' => $recipient,
            'subject' => '[ ' . config_item('company_name') . ' ]' . ' New credit_note' . ' ' . $data['credit_note_info']->reference_no,
            'message' => $message
        );
        $params['resourceed_file'] = '';

        $this->credit_note_model->send_email($params);

        $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));

        $this->credit_note_model->_table_name = 'tbl_credit_note';
        $this->credit_note_model->_primary_key = 'credit_note_id';
        $this->credit_note_model->save($data, $credit_note_id);

        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'credit_note',
            'module_field_id' => $credit_note_id,
            'activity' => 'activity_credit_note_sent',
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/credit_note/index/credit_note_details/' . $credit_note_id,
            'value1' => $credit_note_info->reference_no
        );
        $this->credit_note_model->_table_name = 'tbl_activities';
        $this->credit_note_model->_primary_key = 'activities_id';
        $this->credit_note_model->save($activity);

        // send notification to client
        if (!empty($client_info->primary_contact)) {
            $notifyUser = array($client_info->primary_contact);
        } else {
            $user_info = $this->credit_note_model->check_by(array('company' => $credit_note_info->client_id), 'tbl_account_details');
            if (!empty($user_info)) {
                $notifyUser = array($user_info->user_id);
            }
        }
        if (!empty($notifyUser)) {
            foreach ($notifyUser as $v_user) {
                if ($v_user != $this->session->userdata('user_id')) {
                    add_notification(array(
                        'to_user_id' => $v_user,
                        'icon' => 'shopping-cart',
                        'description' => 'not_email_send_alert',
                        'link' => 'client/credit_note/index/credit_note_details/' . $credit_note_id,
                        'value' => lang('credit_note') . ' ' . $credit_note_info->reference_no,
                    ));
                }
            }
            show_notification($notifyUser);
        }


        $type = 'success';
        $text = lang('credit_note_email_sent');
        set_message($type, $text);
        redirect('admin/credit_note/index/credit_note_details/' . $credit_note_id);
    }
}
