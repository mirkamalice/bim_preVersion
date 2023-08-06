<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase extends Admin_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_model');
        $this->load->library('gst');
    }
    
    public function index($id = NULL)
    {
        $data['title'] = lang('all') . ' ' . lang('purchase');
        
        $data['active'] = 1;
        $data['subview'] = $this->load->view('admin/purchase/manage_purchase', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    public function new_purchase($id = NULL)
    {
        $data['title'] = lang('all') . ' ' . lang('purchase');
        if (!empty($id)) {
            $edited = can_action('152', 'edited');
            if (!empty($edited) && is_numeric($id)) {
                $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
            }
        }
        $data['active'] = 2;
        $data['dropzone'] = true;

        $data['permission_user'] = $this->purchase_model->all_permission_user('152');
        $data['all_supplier'] = $this->purchase_model->get_permission('tbl_suppliers');
        $data['subview'] = $this->load->view('admin/purchase/newpurchase', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    public function purchaseList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_purchases';
            $this->datatables->join_table = array('tbl_suppliers');
            $this->datatables->join_where = array('tbl_suppliers.supplier_id=tbl_purchases.supplier_id');
            $custom_field = custom_form_table_search(20);
            $action_array = array('purchase_id');
            $main_column = array('reference_no', 'tbl_suppliers.name', 'purchase_date', 'due_date', 'status', 'tags');
            $result = array_merge($main_column, $custom_field, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            
            $this->datatables->order = array('purchase_id' => 'desc');
            $fetch_data = make_datatables();
            
            
            $data = array();
            
            $edited = can_action('152', 'edited');
            $deleted = can_action('152', 'deleted');
            foreach ($fetch_data as $_key => $v_purchase) {
                if (!empty($v_purchase)) {
                    $action = null;
                    $sub_array = array();
                    $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $v_purchase->purchase_id));
                    $can_delete = $this->purchase_model->can_action('tbl_purchases', 'delete', array('purchase_id' => $v_purchase->purchase_id));
                    
                    $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    
                    $sub_array[] = '<a href="' . base_url() . 'admin/purchase/purchase_details/' . $v_purchase->purchase_id . '">' . ($v_purchase->reference_no) . '</a>';
                    $sub_array[] = !empty($v_purchase) ? '<span class="tags">' . $v_purchase->name . '</span>' : '-';
                    $sub_array[] = display_date($v_purchase->purchase_date);
                    $sub_array[] = display_money($this->purchase_model->calculate_to('purchase_due', $v_purchase->purchase_id), $currency->symbol);
                    $status = $this->purchase_model->get_payment_status($v_purchase->purchase_id);
                    if ($status == ('fully_paid')) {
                        $bg = "success";
                    } elseif ($status == ('partially_paid')) {
                        $bg = "warning";
                    } elseif ($status == ('not_paid')) {
                        $bg = "danger";
                    } elseif ($v_purchase->emailed == 'Yes') {
                        $bg = "info";
                    } else {
                        $bg = "danger";
                    }
                    
                    $sub_array[] = '<span class="badge bg-' . $bg . '">' . lang($status) . '</span>';
                    $sub_array[] = get_tags($v_purchase->tags, true);
                    
                    $custom_form_table = custom_form_table(20, $v_purchase->purchase_id);
                    
                    if (!empty($custom_form_table)) {
                        foreach ($custom_form_table as $c_label => $v_fields) {
                            $sub_array[] = $v_fields;
                        }
                    }
                    if (!empty($can_edit) && !empty($edited)) {
                        $action .= btn_edit('admin/purchase/new_purchase/' . $v_purchase->purchase_id) . ' ';
                    }
                    if (!empty($can_delete) && !empty($deleted)) {
                        $action .= ajax_anchor(base_url("admin/purchase/delete_purchase/" . $v_purchase->purchase_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                    }
                    $action .= btn_view('admin/purchase/purchase_details/' . $v_purchase->purchase_id) . ' ';
                    if (!empty($can_edit) && !empty($edited)) {
                        $action .= '<a class="btn btn-success btn-xs" data-popup="tooltip" data-placement="top" title="Payment" href="' . base_url() . 'admin/purchase/payment/' . $v_purchase->purchase_id . '">' . lang('pay') . '</a>';
                    }
                    $sub_array[] = $action;
                    $data[] = $sub_array;
                }
            }
            
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }
    
    public function save_purchase($id = NULL)
    {
        $data = $this->purchase_model->array_from_post(array('reference_no', 'supplier_id', 'discount_type', 'tags', 'discount_percent', 'user_id', 'adjustment', 'discount_total', 'show_quantity_as'));
        $data['update_stock'] = ($this->input->post('update_stock') == 'Yes') ? 'Yes' : 'No';
        $data['purchase_date'] = date('Y-m-d', strtotime($this->input->post('purchase_date', TRUE)));
        if (empty($data['purchase_date'])) {
            $data['purchase_date'] = date('Y-m-d');
        }
        if (empty($data['discount_type'])) {
            $data['discount_type'] = null;
        }
        $data['due_date'] = date('Y-m-d', strtotime($this->input->post('due_date', TRUE)));
        $data['warehouse_id'] = $this->input->post('warehouse_id', TRUE);
        $data['notes'] = $this->input->post('notes', TRUE);
        $tax['tax_name'] = $this->input->post('total_tax_name', TRUE);
        $tax['total_tax'] = $this->input->post('total_tax', TRUE);
        $data['total_tax'] = json_encode($tax);
        $i_tax = 0;
        if (!empty($tax['total_tax'])) {
            foreach ($tax['total_tax'] as $v_tax) {
                $i_tax += $v_tax;
            }
        }
        $data['tax'] = $i_tax;
        
        $permission = $this->input->post('permission', true);
        if (!empty($permission)) {
            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->purchase_model->array_from_post(array('assigned_to'));
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
            redirect($_SERVER['HTTP_REFERER']);
        }
        
        // get all client
        $this->purchase_model->_table_name = 'tbl_purchases';
        $this->purchase_model->_primary_key = 'purchase_id';
        if (!empty($id)) {
            $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
            if ($purchase_info->update_stock == 'No' && $data['update_stock'] == 'Yes') {
                $this->send_purchase_sms($id);
            }
            $purchase_id = $id;
            $this->purchase_model->save($data, $id);
            $action = ('purchase_updated');
            $msg = lang('purchase_updated');
        } else {
            $data['created_by'] = my_id();
            $purchase_id = $this->purchase_model->save($data);
            $action = ('purchase_created');
            $msg = lang('purchase_created');
            if ($data['update_stock'] == 'Yes') {
                $this->send_purchase_sms($purchase_id);
            }
        }
        save_custom_field(20, $purchase_id);
        $removed_items = $this->input->post('removed_items', TRUE);
        if (!empty($removed_items)) {
            foreach ($removed_items as $r_id) {
                if ($r_id != 'undefined') {
                    $itemInfo = get_row('tbl_items', array('items_id' => $r_id));
                    $this->purchase_model->return_items($itemInfo->saved_items_id, $itemInfo->quantity, $data['warehouse_id']);
                    
                    $this->db->where('items_id', $r_id);
                    $this->db->delete('tbl_purchase_items');
                }
            }
        }
        $items_data = $this->input->post('items', true);
        if (!empty($items_data)) {
            $index = 0;
            foreach ($items_data as $items) {
                $items['purchase_id'] = $purchase_id;
                unset($items['invoice_items_id']);
                unset($items['total_qty']);
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
                if (empty($items['saved_items_id']) || $items['saved_items_id'] == 'undefined') {
                    $items['saved_items_id'] = 0;
                }
                if (config_item('update_stock_when_make_payment') != 'Yes' && $data['update_stock'] == 'Yes') {
                    if (!empty($items['saved_items_id']) && $items['saved_items_id'] != 'undefined') {
                        if (!empty($items['items_id'])) {
                            $old_quantity = get_any_field('tbl_purchase_items', array('items_id' => $items['items_id']), 'quantity');
                            if ($old_quantity != $items['quantity']) {
                                // $a < $b	Less than TRUE if $a is strictly less than $b.
                                // $a > $b	Greater than TRUE if $a is strictly greater than $b.
                                if ($old_quantity > $items['quantity']) {
                                    $quantity = $old_quantity - $items['quantity'];
                                    $this->purchase_model->reduce_items($items['saved_items_id'], $quantity, $data['warehouse_id']);
                                } else {
                                    $quantity = $items['quantity'] - $old_quantity;
                                    $this->purchase_model->return_items($items['saved_items_id'], $quantity, $data['warehouse_id']);
                                }
                            }
                        } else {
                            $this->purchase_model->return_items($items['saved_items_id'], $items['quantity'], $data['warehouse_id']);
                        }
                    }
                }
                
                $price = $items['quantity'] * $items['unit_cost'];
                $items['item_tax_total'] = ($price / 100 * $tax);
                $items['total_cost'] = $price;
                // get all client
                $this->purchase_model->_table_name = 'tbl_purchase_items';
                $this->purchase_model->_primary_key = 'items_id';
                if (!empty($items['items_id'])) {
                    $items_id = $items['items_id'];
                    $this->purchase_model->save($items, $items_id);
                } else {
                    $items_id = $this->purchase_model->save($items);
                }
                $index++;
            }
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'purchase',
            'module_field_id' => $purchase_id,
            'activity' => $action,
            'icon' => 'fa fa-truck',
            'link' => 'admin/purchase/purchase_details/' . $purchase_id,
            'value1' => $data['reference_no']
        );
        $this->purchase_model->_table_name = 'tbl_activities';
        $this->purchase_model->_primary_key = 'activities_id';
        $this->purchase_model->save($activity);
        // messages for user
        $type = "success";
        $message = $msg;
        set_message($type, $message);
        redirect('admin/purchase/purchase_details/' . $purchase_id);
    }
    
    public function send_purchase_sms($purchase_id)
    {
        $mobile = can_received_sms('purchase_confirmation_sms_number');
        if (!empty($mobile)) {
            $merge_fields = [];
            $merge_fields = array_merge($merge_fields, merge_purchase_template($purchase_id));
            $this->sms->send(SMS_PURCHASE_CONFIRMATION, $mobile, $merge_fields);
        }
        return true;
    }
    
    public function purchase_details($id, $pdf = NULL)
    {
        $data['title'] = lang('purchase'); //Page title
        $data['sales_info'] = join_data('tbl_purchases', '*', array('purchase_id' => $id), array('tbl_suppliers' => 'tbl_suppliers.supplier_id = tbl_purchases.supplier_id'));
        if (!empty($data['sales_info'])) {
            $payment_status = $this->purchase_model->get_payment_status($id);
            $data['sales_info']->ref_no = lang('purchase') . ' : ' . $data['sales_info']->reference_no;
            $data['sales_info']->start_date = lang('purchase_date') . ' : ' . display_date($data['sales_info']->purchase_date);
            
            // check overdue invoice
            if (strtotime($data['sales_info']->due_date) < strtotime(date('Y-m-d')) && $payment_status != lang('fully_paid')) {
                // check overdue how many days from due_date
                $date1 = new DateTime($data['sales_info']->due_date);
                $date2 = new DateTime(date('Y-m-d'));
                $interval = $date1->diff($date2);
                $overdue_days = $interval->format('%a');
                $data['sales_info']->overdue_days = lang('invoice_overdue') . ' ' . lang('by') . ' ' . $overdue_days . ' ' . lang('days');
            }
            $data['sales_info']->end_date = lang('due_date') . ' : ' . display_date($data['sales_info']->due_date);
            if (!empty($data['sales_info']->user_id)) {
                $data['sales_info']->sales_agent = lang('purchase') . ' ' . lang('agent') . ' : ' . fullname($data['sales_info']->user_id);
            }
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
                $payment_status = ('sent');
            } else {
                $label = "danger";
            }
            $data['sales_info']->status = lang('payment_status') . ' :  <span class="label label-' . $label . '">' . lang($payment_status) . '</span>';
            if (!empty($text)) {
                $data['sales_info']->status .= '<br><p style="padding: 15px;margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;;background: color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;">' . $text . '</p>';
            }
            $data['sales_info']->custom_field = '';
            $show_custom_fields = custom_form_label(9, $data['sales_info']->purchase_id);
            if (!empty($show_custom_fields)) {
                foreach ($show_custom_fields as $c_label => $v_fields) {
                    if (!empty($v_fields)) {
                        $data['sales_info']->custom_field .= $c_label . ' : ' . $v_fields . '<br>';
                    }
                }
            }
            $data['all_items'] = $this->purchase_model->ordered_items_by_id($data['sales_info']->purchase_id);
            
            $data['sales_info']->sub_total = ($this->purchase_model->calculate_to('purchase_cost', $data['sales_info']->purchase_id));
            $data['sales_info']->discount = ($this->purchase_model->calculate_to('discount', $data['sales_info']->purchase_id));
            $data['sales_info']->total = ($this->purchase_model->calculate_to('total', $data['sales_info']->purchase_id));
            $data['paid_amount'] = $this->purchase_model->calculate_to('paid_amount', $data['sales_info']->purchase_id);
            $data['invoice_due'] = $this->purchase_model->calculate_to('purchase_due', $data['sales_info']->purchase_id);
            $data['payment_status'] = $payment_status;
            
            
            if ($payment_status != lang('cancelled') && $payment_status != lang('fully_paid')) {
                $this->load->model('credit_note_model');
                $data['total_available_credit'] = $this->credit_note_model->get_available_credit_by_client($data['sales_info']->client_id);
            }
            // get payment info by id
            $data['all_payments_history'] = get_result('tbl_payments', array('invoices_id' => $id));
            $data['footer'] = config_item('purchase_footer');
        } else {
            set_message('error', 'No data Found');
            redirect('admin/purchase');
        }
        if (!empty($pdf)) {
            $this->common_model->sales_pdf($data, $pdf);
        }
        $data['subview'] = $this->load->view('admin/purchase/purchase_details', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
        
    }
    
    public
    function clone_purchase($purchase_id)
    {
        $edited = can_action('13', 'edited');
        $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $purchase_id));
        if (!empty($can_edit) && !empty($edited)) {
            $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');
            
            $data['permission_user'] = $this->purchase_model->all_permission_user('152');
            $data['all_supplier'] = $this->purchase_model->get_permission('tbl_suppliers');
            
            $data['modal_subview'] = $this->load->view('admin/purchase/_modal_clone_purchase', $data, FALSE);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    public
    function cloned_purchase($id)
    {
        $edited = can_action('152', 'edited');
        $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $id));
        if (!empty($can_edit) && !empty($edited)) {
            if (config_item('increment_purchase_number') == 'FALSE') {
                $this->load->helper('string');
                $reference_no = config_item('purchase_prefix') . ' ' . random_string('nozero', 6);
            } else {
                $reference_no = config_item('purchase_prefix') . ' ' . $this->purchase_model->generate_purchase_number();
            }
            $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
            // save into invoice table
            $new_invoice = array(
                'reference_no' => $reference_no,
                'supplier_id' => $this->input->post('supplier_id', true),
                'purchase_date' => $this->input->post('purchase_date', true),
                'due_date' => $this->input->post('due_date', true),
                'notes' => $purchase_info->notes,
                'total_tax' => $purchase_info->total_tax,
                'tax' => $purchase_info->tax,
                'discount_type' => $purchase_info->discount_type,
                'discount_percent' => $purchase_info->discount_percent,
                'user_id' => $purchase_info->user_id,
                'created_by' => my_id(),
                'adjustment' => $purchase_info->adjustment,
                'warehouse_id' => $purchase_info->warehouse_id,
                'discount_total' => $purchase_info->discount_total,
                'show_quantity_as' => $purchase_info->show_quantity_as,
                'status' => $purchase_info->status,
                'update_stock' => $purchase_info->update_stock,
                'emailed' => $purchase_info->emailed,
                'permission' => $purchase_info->permission,
            );
            
            $this->purchase_model->_table_name = "tbl_purchases";
            $this->purchase_model->_primary_key = "purchase_id";
            $new_purchase_id = $this->purchase_model->save($new_invoice);
            
            $purchase_items = $this->db->where('purchase_id', $id)->get('tbl_purchase_items')->result();
            if (!empty($purchase_items)) {
                foreach ($purchase_items as $new_item) {
                    if ($purchase_info->update_stock == 'Yes') {
                        if (!empty($new_item->saved_items_id) && $new_item->saved_items_id != 'undefined') {
                            $this->purchase_model->return_items($new_item->saved_items_id, $new_item->quantity, $purchase_info->warehouse_id);
                        }
                    }
                    $items = array(
                        'purchase_id' => $new_purchase_id,
                        'saved_items_id' => $new_item->saved_items_id,
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
                    $this->purchase_model->_table_name = "tbl_purchase_items";
                    $this->purchase_model->_primary_key = "items_id";
                    $this->purchase_model->save($items);
                }
            }
            // save into activities
            $activities = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'purchase',
                'module_field_id' => $new_purchase_id,
                'activity' => ('activity_cloned_purchase'),
                'icon' => 'fa-shopping-cart',
                'link' => 'admin/purchase/purchase_details/' . $new_purchase_id,
                'value1' => ' from ' . $purchase_info->reference_no . ' to ' . $reference_no,
            );
            // Update into tbl_project
            $this->purchase_model->_table_name = "tbl_activities";
            $this->purchase_model->_primary_key = "activities_id";
            $this->purchase_model->save($activities);
            
            // messages for user
            $type = "success";
            $message = lang('purchase_created');
            set_message($type, $message);
            redirect('admin/purchase/purchase_details/' . $new_purchase_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    
    public function payment($id)
    {
        $data['title'] = lang('purchase') . ' ' . lang('payment');
        // get payment info by id
        $this->purchase_model->_table_name = 'tbl_purchase_payments';
        $this->purchase_model->_order_by = 'payments_id';
        $data['all_payments_history'] = $this->purchase_model->get_by(array('purchase_id' => $id), FALSE);
        $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
        
        $data['all_purchases'] = $this->purchase_model->get_permission('tbl_purchases');
        $data['subview'] = $this->load->view('admin/purchase/payment', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    public function update_stock($id)
    {
        $purchaseInfo = get_row('tbl_purchases', array('purchase_id' => $id));
        $purchaseItems = get_result('tbl_purchase_items', array('purchase_id' => $id));
        if (!empty($purchaseItems)) {
            foreach ($purchaseItems as $v_items) {
                if ($v_items->saved_items_id != 'undefined') {
                    $this->purchase_model->return_items($v_items->saved_items_id, $v_items->quantity, $purchaseInfo->warehouse_id);
                }
            }
        }
        
        $data['stock_updated'] = 'Yes';
        $this->purchase_model->_table_name = 'tbl_purchases';
        $this->purchase_model->_primary_key = 'purchase_id';
        $this->purchase_model->save($data, $id);
        return true;
        
    }
    
    public
    function get_payment($purchase_id)
    {
        $edited = can_action('152', 'edited');
        $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $purchase_id));
        if (!empty($can_edit) && !empty($edited)) {
            $due = round($this->purchase_model->calculate_to('purchase_due', $purchase_id), 2);
            $paid_amount = $this->input->post('amount', TRUE);
            if ($paid_amount != 0) {
                if ($paid_amount > $due) {
                    // messages for user
                    $type = "error";
                    $message = lang('overpaid_amount');
                    set_message($type, $message);
                    redirect('admin/purchase/payment/' . $purchase_id);
                } else {
                    $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');
                    $data = array(
                        'purchase_id' => $purchase_id,
                        'paid_to' => $purchase_info->supplier_id,
                        'paid_by' => my_id(),
                        'payment_method' => $this->input->post('payment_methods_id', TRUE),
                        'currency' => $this->input->post('currency', TRUE),
                        'amount' => $paid_amount,
                        'payment_date' => date('Y-m-d', strtotime($this->input->post('payment_date', TRUE))),
                        'trans_id' => $this->input->post('trans_id'),
                        'notes' => $this->input->post('notes'),
                        'month_paid' => date("m", strtotime($this->input->post('payment_date', TRUE))),
                        'year_paid' => date("Y", strtotime($this->input->post('payment_date', TRUE))),
                    );
                    $this->purchase_model->_table_name = 'tbl_purchase_payments';
                    $this->purchase_model->_primary_key = 'payments_id';
                    $payments_id = $this->purchase_model->save($data);
                    
                    if (config_item('update_stock_when_make_payment') == 'Yes') {
                        $this->update_stock($purchase_id);
                    }
                    
                    if ($paid_amount < $due) {
                        $status = 'partially_paid';
                    }
                    if ($paid_amount == $due) {
                        $status = 'Paid';
                    }
                    
                    $purchase_data['status'] = $status;
                    update('tbl_purchases', array('purchase_id' => $purchase_id), $purchase_data);
                    $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'purchase',
                        'module_field_id' => $purchase_id,
                        'activity' => ('activity_new_payment'),
                        'icon' => 'fa-shopping-cart',
                        'link' => 'admin/purchase/purchase_details/' . $purchase_id,
                        'value1' => display_money($paid_amount, $currency->symbol),
                        'value2' => $purchase_info->reference_no,
                    );
                    $this->purchase_model->_table_name = 'tbl_activities';
                    $this->purchase_model->_primary_key = 'activities_id';
                    $this->purchase_model->save($activity);
                    
                    if ($this->input->post('deduct_from_account') == 'on') {
                        $account_id = $this->input->post('account_id', true);
                        if (empty($account_id)) {
                            $account_id = config_item('default_account');
                        }
                        if (!empty($account_id)) {
                            $reference = lang('purchase') . ' ' . lang('reference_no') . ": <a href='" . base_url('admin/purchase/purchase_details/' . $purchase_info->purchase_id) . "' >" . $purchase_info->reference_no . "</a> and " . lang('trans_id') . ": <a href='" . base_url('admin/purchase/payments_details/' . $payments_id) . "'>" . $this->input->post('trans_id', true) . "</a>";
                            $trans_id = $this->input->post('trans_id', true);
                            // save into tbl_transaction
                            $tr_data = array(
                                'name' => lang('purchase_payment', lang('trans_id') . '# ' . $trans_id),
                                'type' => 'Expense',
                                'amount' => $paid_amount,
                                'debit' => $paid_amount,
                                'credit' => 0,
                                'date' => date('Y-m-d', strtotime($this->input->post('payment_date', TRUE))),
                                'paid_by' => $purchase_info->supplier_id,
                                'payment_methods_id' => $this->input->post('payment_methods_id', TRUE),
                                'reference' => $trans_id,
                                'notes' => lang('this_expense_from_purchase_payment', $reference),
                                'permission' => 'all',
                            );
                            $account_info = $this->purchase_model->check_by(array('account_id' => $account_id), 'tbl_accounts');
                            if (!empty($account_info)) {
                                $ac_data['balance'] = $account_info->balance - $tr_data['amount'];
                                $this->purchase_model->_table_name = "tbl_accounts";
                                $this->purchase_model->_primary_key = "account_id";
                                $this->purchase_model->save($ac_data, $account_info->account_id);
                                
                                $aaccount_info = $this->purchase_model->check_by(array('account_id' => $account_id), 'tbl_accounts');
                                
                                $tr_data['total_balance'] = $aaccount_info->balance;
                                $tr_data['account_id'] = $account_id;
                                
                                // save into tbl_transaction
                                $this->purchase_model->_table_name = "tbl_transactions";
                                $this->purchase_model->_primary_key = "transactions_id";
                                $return_id = $this->purchase_model->save($tr_data);
                                
                                $deduct_account['account_id'] = $account_id;
                                $this->purchase_model->_table_name = 'tbl_purchase_payments';
                                $this->purchase_model->_primary_key = 'payments_id';
                                $this->purchase_model->save($deduct_account, $payments_id);
                                
                                // save into activities
                                $activities = array(
                                    'user' => $this->session->userdata('user_id'),
                                    'module' => 'transactions',
                                    'module_field_id' => $return_id,
                                    'activity' => 'activity_new_expense',
                                    'icon' => 'fa-building-o',
                                    'link' => 'admin/transactions/view_details/' . $return_id,
                                    'value1' => $account_info->account_name,
                                    'value2' => $paid_amount,
                                );
                                // Update into tbl_project
                                $this->purchase_model->_table_name = "tbl_activities";
                                $this->purchase_model->_primary_key = "activities_id";
                                $this->purchase_model->save($activities);
                            }
                        }
                    }
                    if ($this->input->post('send_thank_you') == 'on') {
                        $this->send_payment_email($purchase_id, $paid_amount); //send thank you email
                    }
                    if ($this->input->post('send_sms') == 'on') {
                        $this->send_payment_sms($purchase_id, $payments_id); //send thank you email
                    }
                }
            }
            // messages for user
            $type = "success";
            $message = lang('generate_payment');
            set_message($type, $message);
            redirect('admin/purchase/purchase_details/' . $purchase_id);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    public function send_payment_sms($purchase_id, $payments_id)
    {
        $mobile = can_received_sms('purchase_confirmation_sms_number');
        if (!empty($mobile)) {
            $merge_fields = [];
            $merge_fields = array_merge($merge_fields, merge_purchase_template($purchase_id));
            $merge_fields = array_merge($merge_fields, merge_purchase_template($purchase_id, $payments_id));
            $this->sms->send(SMS_PURCHASE_PAYMENT_CONFIRMATION, $mobile, $merge_fields);
        }
        return true;
    }
    
    public function all_payments($id = NULL)
    {
        if (!empty($id)) {
            $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $id));
            if (!empty($can_edit)) {
                $payments_info = $this->purchase_model->check_by(array('payments_id' => $id), 'tbl_purchase_payments');
                $data['purchase_info'] = $this->purchase_model->check_by(array('purchase_id' => $payments_info->purchase_id), 'tbl_purchases');
            }
            $data['title'] = lang('edit') . ' ' . lang('purchase') . ' ' . lang('payment'); //Page title
            $subview = 'edit_payments';
        } else {
            $data['title'] = lang('all') . ' ' . lang('purchase') . ' ' . lang('payment'); //Page title
            $subview = 'all_payments';
        }
        
        //$data['all_purchase'] = $this->purchase_model->get_permission('tbl_purchases');
        
        // get payment info by id
        if (!empty($id)) {
            $can_edit = $this->purchase_model->can_action('tbl_purchase_payments', 'edit', array('payments_id' => $id));
            if (!empty($can_edit)) {
                $data['payments_info'] = $this->purchase_model->check_by(array('payments_id' => $id), 'tbl_purchase_payments');
            } else {
                set_message('error', lang('no_permission_to_access'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $data['subview'] = $this->load->view('admin/purchase/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    // all payment list
    public function paymentList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_purchase_payments';
            $this->datatables->join_table = array('tbl_purchases', 'tbl_suppliers');
            $this->datatables->join_where = array('tbl_purchases.purchase_id=tbl_purchase_payments.purchase_id', 'tbl_suppliers.supplier_id=tbl_purchases.supplier_id');
            $this->datatables->column_order = array('payment_date', 'purchase_date', 'reference_no', 'tbl_suppliers.name', 'amount', 'payment_method');
            $this->datatables->column_search = array('payment_date', 'purchase_date', 'reference_no', 'tbl_suppliers.name', 'amount', 'payment_method');
            $this->datatables->order = array('payments_id' => 'desc');
            $fetch_data = make_datatables();
            
            $data = array();
            
            $edited = can_action('154', 'edited');
            $deleted = can_action('154', 'deleted');
            foreach ($fetch_data as $_key => $v_payments_info) {
                $action = null;
                $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $v_payments_info->purchase_id));
                $can_delete = $this->purchase_model->can_action('tbl_purchases', 'delete', array('purchase_id' => $v_payments_info->purchase_id));
                $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                if (!empty($v_payments_info->name)) {
                    $c_name = $v_payments_info->name;
                } else {
                    $c_name = '-';
                }
                if (is_numeric($v_payments_info->payment_method)) {
                    $v_payments_info->method_name = get_any_field('tbl_payment_methods', array('payment_methods_id' => $v_payments_info->payment_method), 'method_name');
                } else {
                    $v_payments_info->method_name = $v_payments_info->payment_method;
                }
                $sub_array[] = '<a href="' . base_url() . 'admin/purchase/payments_details/' . $v_payments_info->payments_id . '">' . display_date($v_payments_info->payment_date) . '</a>';
                $sub_array[] = display_date($v_payments_info->purchase_date);
                $sub_array[] = '<a href="' . base_url() . 'admin/purchase/purchase_details/' . $v_payments_info->purchase_id . '">' . display_date($v_payments_info->payment_date) . '</a>';
                $sub_array[] = $c_name;
                $sub_array[] = display_money($v_payments_info->amount, $currency->symbol);
                $sub_array[] = !empty($v_payments_info->method_name) ? $v_payments_info->method_name : '-';
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= btn_edit('admin/purchase/all_payments/' . $v_payments_info->payments_id) . ' ';
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/purchase/delete_payment/" . $v_payments_info->payments_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }
    
    
    public function payments_details($id)
    {
        $data['all_purchases'] = $this->purchase_model->get_permission('tbl_purchases');
        $data['title'] = lang('purchase') . ' ' . lang('payment') . ' ' . lang('details'); //Page title
        $subview = 'payments_details';
        // get payment info by id
        $data['payments_info'] = $this->purchase_model->check_by(array('payments_id' => $id), 'tbl_purchase_payments');
        $data['subview'] = $this->load->view('admin/purchase/' . $subview, $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    
    public function delete_payment($id)
    {
        $payments_info = $this->purchase_model->check_by(array('payments_id' => $id), 'tbl_purchase_payments');
        if (!empty($payments_info)) {
            $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $payments_info->purchase_id), 'tbl_purchases');
            
            if (!empty($purchase_info->reference_no)) {
                $val = $purchase_info->reference_no;
            } else {
                $val = NULL;
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'purchase',
                'module_field_id' => $payments_info->purchase_id,
                'activity' => ('activity_delete_purchase_payment'),
                'icon' => 'fa-shopping-cart',
                'value1' => $val,
            
            );
            $this->purchase_model->_table_name = 'tbl_activities';
            $this->purchase_model->_primary_key = 'activities_id';
            $this->purchase_model->save($activity);
            
            $this->purchase_model->_table_name = 'tbl_purchase_payments';
            $this->purchase_model->_primary_key = 'payments_id';
            $this->purchase_model->delete($id);
            
            $type = 'success';
            $text = lang('activity_delete_purchase_payment');
        } else {
            $type = 'error';
            $text = lang('there_in_no_value');
        }
        echo json_encode(array("status" => $type, 'message' => $text));
        exit();
    }
    
    public
    function update_payemnt($payments_id)
    {
        $data = array(
            'amount' => $this->input->post('amount', TRUE),
            'payment_method' => $this->input->post('payment_methods_id', TRUE),
            'payment_date' => date('Y-m-d', strtotime($this->input->post('payment_date', TRUE))),
            'notes' => $this->input->post('notes', TRUE),
            'month_paid' => date("m", strtotime($this->input->post('payment_date', TRUE))),
            'year_paid' => date("Y", strtotime($this->input->post('payment_date', TRUE))),
        );
        
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'purchase',
            'module_field_id' => $payments_id,
            'activity' => ('activity_update_payment'),
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/purchase/purchase_details/' . $payments_id,
            'value1' => $data['amount'],
            'value2' => $data['payment_date'],
        );
        $this->purchase_model->_table_name = 'tbl_activities';
        $this->purchase_model->_primary_key = 'activities_id';
        $this->purchase_model->save($activity);
        
        $this->purchase_model->_table_name = 'tbl_purchase_payments';
        $this->purchase_model->_primary_key = 'payments_id';
        $this->purchase_model->save($data, $payments_id);
        
        
        // messages for user
        $type = "success";
        $message = lang('generate_payment');
        set_message($type, $message);
        redirect('admin/purchase/all_payments');
    }
    
    public
    function send_payment($purchase_id, $paid_amount)
    {
        $this->send_payment_email($purchase_id, $paid_amount); //send email
        
        $type = "success";
        $message = lang('payment_information_send');
        set_message($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    function send_payment_email($purchase_id, $paid_amount)
    {
        $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        $email_template = email_templates(array('email_group' => 'payment_email'));
        $message = $email_template->template_body;
        $subject = $email_template->subject;
        
        $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');
        $currency = $currency->symbol;
        $reference = $purchase_info->reference_no;
        
        $invoice_currency = str_replace("{
        INVOICE_CURRENCY}", $currency, $message);
        $reference = str_replace("{
        INVOICE_REF}", $reference, $invoice_currency);
        $amount = str_replace("{
        PAID_AMOUNT}", $paid_amount, $reference);
        $message = str_replace("{
        SITE_NAME}", config_item('company_name'), $amount);
        
        $data['message'] = $message;
        $message = $this->load->view('email_template', $data, TRUE);
        $supplier_info = $this->purchase_model->check_by(array('supplier_id' => $purchase_info->supplier_id), 'tbl_suppliers');
        $address = $supplier_info->email;
        $params['recipient'] = $address;
        
        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;
        $params['resourceed_file'] = '';
        
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'purchase',
            'module_field_id' => $purchase_id,
            'activity' => ('activity_send_payment'),
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/purchase/purchase_details/' . $purchase_id,
            'value1' => $reference,
            'value2' => $currency . ' ' . $amount,
        );
        $this->purchase_model->_table_name = 'tbl_activities';
        $this->purchase_model->_primary_key = 'activities_id';
        $this->purchase_model->save($activity);
        
        $this->purchase_model->send_email($params);
    }
    
    public
    function change_status($action, $id)
    {
        $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
        $purchase_items = $this->db->where('purchase_id', $id)->get('tbl_purchase_items')->result();
        if ($action == 'mark_as_sent') {
            $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d:s", time()));
        } elseif ($action == 'mark_as_cancelled') {
            if ($purchase_info->update_stock == 'Yes') {
                if (!empty($purchase_items)) {
                    foreach ($purchase_items as $new_item) {
                        if (!empty($new_item->saved_items_id) && $new_item->saved_items_id != 'undefined') {
                            $this->purchase_model->reduce_items($new_item->saved_items_id, $new_item->quantity, $purchase_info->warehouse_id);
                        }
                    }
                }
            }
            $data = array('status' => 'Cancelled');
        } elseif ($action == 'unmark_as_cancelled') {
            if ($purchase_info->update_stock == 'Yes') {
                if (!empty($purchase_items)) {
                    foreach ($purchase_items as $new_item) {
                        if (!empty($new_item->saved_items_id) && $new_item->saved_items_id != 'undefined') {
                            $this->purchase_model->return_items($new_item->saved_items_id, $new_item->quantity, $purchase_info->warehouse_id);
                        }
                    }
                }
            }
            $payment_status = $this->purchase_model->get_payment_status($purchase_info->purchase_id, true);
            $data = array('status' => lang($payment_status));
        } else {
            $data = array('status' => $action);
        }
        $this->purchase_model->_table_name = 'tbl_purchases';
        $this->purchase_model->_primary_key = 'purchase_id';
        $this->purchase_model->save($data, $id);
        
        // messages for user
        $type = "success";
        $imessage = lang('purchase_update');
        set_message($type, $imessage);
        redirect('admin/purchase/purchase_details/' . $id);
    }
    
    function send_purchase_email($purchase_id)
    {
        $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $purchase_id), 'tbl_purchases');
        $supplier_info = $this->purchase_model->check_by(array('supplier_id' => $purchase_info->supplier_id), 'tbl_suppliers');
        $currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        $message = " < p>Hello $supplier_info->name </p >
<p >&nbsp;</p >

<p > This is a purchase details of " . display_money($this->purchase_model->calculate_to('total', $purchase_info->purchase_id), $currency->symbol) . " < br />
Please check the attachment bellow:<br />
<br />
Best Regards,<br />
The " . config_item('company_name') . " Team </p > ";
        $params = array(
            'recipient' => $supplier_info->email,
            'subject' => '[ ' . config_item('company_name') . ' ]' . ' Purchase' . ' ' . $purchase_info->reference_no,
            'message' => $message
        );
        $params['resourceed_file'] = 'uploads/' . slug_it(lang('purchase') . '_' . $purchase_info->reference_no) . '.pdf';
        $params['resourcement_url'] = base_url() . 'uploads/' . slug_it(lang('purchase') . '_' . $purchase_info->reference_no) . '.pdf';
        $this->attach_pdf($purchase_id);
        $this->purchase_model->send_email($params);
        //Delete invoice in tmp folder
        if (is_file('uploads/' . slug_it(lang('purchase') . '_' . $purchase_info->reference_no) . '.pdf')) {
            unlink('uploads/' . slug_it(lang('purchase') . '_' . $purchase_info->reference_no) . '.pdf');
        }
        
        $data = array('emailed' => 'Yes', 'date_sent' => date("Y-m-d H:i:s", time()));
        
        $this->purchase_model->_table_name = 'tbl_purchases';
        $this->purchase_model->_primary_key = 'purchase_id';
        $this->purchase_model->save($data, $purchase_info->purchase_id);
        
        // Log Activity
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'purchase',
            'module_field_id' => $purchase_info->purchase_id,
            'activity' => ('activity_purchase_sent'),
            'icon' => 'fa-shopping-cart',
            'link' => 'admin/purchase/purchase_details/' . $purchase_info->purchase_id,
            'value1' => $purchase_info->reference_no,
            'value2' => display_money($this->purchase_model->calculate_to('total', $purchase_info->purchase_id), $currency->symbol),
        );
        $this->purchase_model->_table_name = 'tbl_activities';
        $this->purchase_model->_primary_key = 'activities_id';
        $this->purchase_model->save($activity);
        // messages for user
        $type = "success";
        $imessage = lang('invoice_sent');
        set_message($type, $imessage);
        redirect('admin/purchase/purchase_details/' . $purchase_info->purchase_id);
    }
    
    public
    function attach_pdf($id)
    {
        $this->purchase_details($id, 'attach');
    }
    
    
    public function payments_pdf($id)
    {
        $data['title'] = "Payments PDF"; //Page title
        // get payment info by id
        $this->purchase_model->_table_name = 'tbl_purchase_payments';
        $this->purchase_model->_order_by = 'payments_id';
        $data['payments_info'] = $this->purchase_model->check_by(array('payments_id' => $id), 'tbl_purchase_payments');
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/purchase/payments_pdf', $data, TRUE);
        pdf_create($viewfile, lang('purchase') . ' ' . lang('payment') . '# ' . $data['payments_info']->trans_id);
    }
    
    public
    function pdf_purchase($id)
    {
        $this->purchase_details($id, true);
    }
    
    public function delete_purchase($id)
    {
        $deleted = can_action('152', 'deleted');
        $can_delete = $this->purchase_model->can_action('tbl_purchases', 'delete', array('purchase_id' => $id));
        if (!empty($can_delete) && !empty($deleted)) {
            $purchase_info = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchases');
            $purchase_items_info = $this->purchase_model->check_by(array('purchase_id' => $id), 'tbl_purchase_items');
            if ($purchase_info->update_stock == 'Yes') {
                if (!empty($purchase_items_info)) {
                    foreach ($purchase_items_info as $v_items) {
                        if (!empty($v_items->saved_items_id) && $v_items->saved_items_id != 0) {
                            $this->purchase_model->reduce_items($v_items->saved_items_id, $v_items->quantity);
                        }
                    }
                }
            }
            $this->purchase_model->_table_name = 'tbl_purchase_items';
            $this->purchase_model->delete_multiple(array('purchase_id' => $id));
            
            $this->purchase_model->_table_name = 'tbl_purchase_payments';
            $this->purchase_model->delete_multiple(array('purchase_id' => $id));
            
            $this->purchase_model->_table_name = 'tbl_purchases';
            $this->purchase_model->_primary_key = 'purchase_id';
            $this->purchase_model->delete($id);
            
            $type = "success";
            if (!empty($purchase_info->reference_no)) {
                $val = $purchase_info->reference_no;
            } else {
                $val = NULL;
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'purchase',
                'module_field_id' => $id,
                'activity' => ('activity_delete_purchase'),
                'icon' => 'fa fa-truck',
                'value1' => $val,
            
            );
            $this->purchase_model->_table_name = 'tbl_activities';
            $this->purchase_model->_primary_key = 'activities_id';
            $this->purchase_model->save($activity);
            
            echo json_encode(array("status" => $type, 'message' => lang('activity_delete_purchase')));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }
}
