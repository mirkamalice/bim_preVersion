<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends Admin_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
    }
    
    public function items_list($id = NULL, $opt = null)
    {
        $data['title'] = lang('all_items');
        if (!empty($id)) {
            if (is_numeric($id)) {
                $data['active'] = 2;
                $data['items_info'] = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
            } else {
                if ($id == 'manufacturer') {
                    $data['active'] = 4;
                    $data['manufacturer_info'] = $this->items_model->check_by(array('manufacturer_id' => $opt), 'tbl_manufacturer');
                } else {
                    $data['active'] = 3;
                    $data['group_info'] = $this->items_model->check_by(array('customer_group_id' => $opt), 'tbl_customer_group');
                }
            }
        } else {
            $data['active'] = 1;
        }
        $data['warehouseList'] = $this->items_model->select_data('tbl_warehouse', 'warehouse_id', 'warehouse_name', array('status' => 'published'));
        $data['all_customer_group'] = $this->items_model->select_data('tbl_customer_group', 'customer_group_id', 'customer_group', array('type' => 'items'));
        $data['all_manufacturer'] = $this->items_model->select_data('tbl_manufacturer', 'manufacturer_id', 'manufacturer');
        $data['dropzone'] = 1;
        $data['subview'] = $this->load->view('admin/items/items_list', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    public function new_items($id = NULL, $opt = null)
    {
        $data['title'] = lang('all_items');
        $data['items_info'] = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
        if (!empty($id)) {
            if (is_numeric($id)) {
                
                $data['items_info'] = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
            }
        }
        $data['warehouseList'] = $this->items_model->select_data('tbl_warehouse', 'warehouse_id', 'warehouse_name', array('status' => 'published'));
        $data['all_customer_group'] = $this->items_model->select_data('tbl_customer_group', 'customer_group_id', 'customer_group', array('type' => 'items'));
        $data['all_manufacturer'] = $this->items_model->select_data('tbl_manufacturer', 'manufacturer_id', 'manufacturer');
        $data['dropzone'] = 1;
        $data['subview'] = $this->load->view('admin/items/createitems', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    public function newitems_group($id = NULL, $opt = null)
    {
        $data['title'] = lang('all_items');
        if (!empty($id)) {
            if ($id == 'group') {
                $data['active'] = 3;
                $data['group_info'] = $this->items_model->check_by(array('customer_group_id' => $opt), 'tbl_customer_group');
            }
        }
        
        $data['subview'] = $this->load->view('admin/items/grouplist', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    public function itemsList($group_id = null, $type = null)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_saved_items';
            $this->datatables->join_table = array('tbl_customer_group', 'tbl_manufacturer');
            $this->datatables->join_where = array('tbl_customer_group.customer_group_id=tbl_saved_items.customer_group_id', 'tbl_manufacturer.manufacturer_id=tbl_saved_items.manufacturer_id');
            
            $custom_field = custom_form_table_search(18);
            $action_array = array('saved_items_id');
            $main_column = array('item_name', 'code', 'hsn_code', 'quantity', 'unit_cost', 'unit_type', 'tbl_customer_group.customer_group', 'tbl_manufacturer.manufacturer');
            $result = array_merge($main_column, $custom_field, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->datatables->order = array('saved_items_id' => 'desc');
            
            // get all invoice
            if (!empty($type) && $type == 'by_group') {
                $where = array('tbl_saved_items.customer_group_id' => $group_id);
            } else if (!empty($type) && $type == 'by_manufacturer') {
                $where = array('tbl_saved_items.manufacturer_id' => $group_id);
            } else if (!empty($type) && $type == 'by_warehouse') {
                $where = array('tbl_saved_items.warehouse_id' => $group_id);
            } else {
                $where = null;
            }
            $fetch_data = make_datatables($where);
            $data = array();
            $edited = can_action('39', 'edited');
            $deleted = can_action('39', 'deleted');
            foreach ($fetch_data as $_key => $v_items) {
                $action = null;
                $item_name = !empty($v_items->item_name) ? $v_items->item_name : $v_items->item_name;
                
                $sub_array = array();
                if (!empty($deleted)) {
                    $sub_array[] = '<div class="checkbox c-checkbox" ><label class="needsclick"> <input value="' . $v_items->saved_items_id . '" type="checkbox"><span class="fa fa-check"></span></label></div>';
                }
                $sub_array[] = '<a data-toggle="modal" data-target="#myModal_extra_lg" href="' . base_url('admin/items/items_details/' . $v_items->saved_items_id) . '"><strong class="block">' . $item_name . '</strong></a>' . ' ' . lang('code') . ': <span class="tags">' . (!empty($v_items->code) ? $v_items->code : '-') . '</span><br/>' . lang('manufacturer') . ': <span class="tags">' . (!empty($v_items->manufacturer) ? $v_items->manufacturer : '-') . '</span>';
                
                $invoice_view = config_item('invoice_view');
                if (!empty($invoice_view) && $invoice_view == '2') {
                    $sub_array[] = $v_items->hsn_code;
                }
                if (!empty(admin())) {
                    $sub_array[] = display_money($v_items->cost_price, default_currency());
                }
                $sub_array[] = display_money($v_items->unit_cost, default_currency());
                $sub_array[] = $v_items->unit_type;
                if (!is_numeric($v_items->tax_rates_id)) {
                    $tax_rates = json_decode($v_items->tax_rates_id);
                } else {
                    $tax_rates = null;
                }
                $rates = null;
                if (!empty($tax_rates)) {
                    if (is_array($tax_rates)) {
                        foreach ($tax_rates as $key => $tax_id) {
                            $taxes_info = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                            if (!empty($taxes_info)) {
                                $rates .= $key + 1 . '. ' . $taxes_info->tax_rate_name . '&nbsp;&nbsp; (' . $taxes_info->tax_rate_percent . '% ) <br>';
                            }
                        }
                    } else {
                        $rates = $this->db->where('tax_rates_id', $tax_rates)->get('tbl_tax_rates')->row()->tax_rate_name;
                    }
                }
                $sub_array[] = $rates;
                
                $sub_array[] = (!empty($v_items->customer_group) ? '<span class="tags">' . $v_items->customer_group . '</span>' : ' ');
                //                $sub_array[] = (!empty($v_items->warehouse_name) ? '<span class="tags">' . $v_items->warehouse_name . '</span>' : ' ');
                $custom_form_table = custom_form_table(18, $v_items->saved_items_id);
                
                if (!empty($custom_form_table)) {
                    foreach ($custom_form_table as $c_label => $v_fields) {
                        $sub_array[] = $v_fields;
                    }
                }
                
                if (!empty($edited)) {
                    $action .= btn_edit('admin/items/new_items/' . $v_items->saved_items_id) . ' ';
                }
                if (!empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/items/delete_items/$v_items->saved_items_id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }
                if (!empty($edited)) {
                    $action .= '<a class="btn btn-inverse btn-xs" data-toggle="tooltip" data-placement="top" title="' . lang('print_barcode') . '"  target="_blank" href="' . base_url('admin/items/single_barcode/' . $v_items->saved_items_id) . '"><i class="fa fa-barcode" ></i></a>' . ' ';
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }
    
    public function saved_items($id = NULL)
    {
        $data = $this->items_model->array_from_post(array('item_name', 'manufacturer_id', 'code', 'barcode_symbology', 'item_desc', 'hsn_code', 'cost_price', 'unit_cost', 'unit_type', 'customer_group_id', 'quantity'));
        $tax_rates = $this->input->post('tax_rates_id', true);
        $total_tax = 0;
        if (!empty($tax_rates)) {
            foreach ($tax_rates as $tax_id) {
                $tax_info = $this->db->where('tax_rates_id', $tax_id)->get('tbl_tax_rates')->row();
                $total_tax += $tax_info->tax_rate_percent;
            }
        }
        if (!empty($tax_rates)) {
            $data['tax_rates_id'] = json_encode($tax_rates);
        } else {
            $data['tax_rates_id'] = '-';
        }
        $warehouse_id = $this->input->post("warehouse_id", true);
        
        // update root category
        $where = array('item_name' => $data['item_name']);
        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $saved_items_id = array('saved_items_id !=' => $id);
        } else { // if id is not exist then set id as null
            $saved_items_id = null;
        }
        save_custom_field(18, $id);
        // check whether this input data already exist or not
        $check_items = $this->items_model->check_update('tbl_saved_items', $where, $saved_items_id);
        if (!empty($check_items)) { // if input data already exist show error alert
            // massage for user
            $type = 'error';
            $msg = "<strong style='color:#000'>" . $data['item_name'] . '</strong>  ' . lang('already_exist');
        } else { // save and update query          
            
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
                        if (!empty($new_file_name)) {
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
                $data['upload_file'] = json_encode($upload_file);
            } else {
                $data['upload_file'] = null;
            }
            
            $sub_total = $data['unit_cost'] * $data['quantity'];
            $data['item_tax_total'] = ($total_tax / 100) * $sub_total;
            $data['total_cost'] = $sub_total + $data['item_tax_total'];
            
            $this->items_model->_table_name = 'tbl_saved_items';
            $this->items_model->_primary_key = 'saved_items_id';
            $return_id = $this->items_model->save($data, $id);
            $_data['warehouse_id'] = $warehouse_id;
            $_data['quantity'] = $data['quantity'];
            $_data['product_id'] = $return_id;
            $check = get_row('tbl_warehouses_products', array('warehouse_id ' => $_data['warehouse_id'], 'product_id' => $_data['product_id']));
            $this->items_model->_table_name = 'tbl_warehouses_products';
            $this->items_model->_primary_key = 'id';
            if (!empty($check)) {
                $_data['quantity'] = $_data['quantity'] + $check->quantity;
                $_data['product_id'] = $return_id;
                $_data_id = $check->id;
                $this->items_model->save($_data, $_data_id);
            } else {
                
                $this->items_model->save($_data);
            }
            //
            if (!empty($id)) {
                $id = $id;
                $action = 'activity_update_items';
                $msg = lang('update_items');
            } else {
                $id = $return_id;
                $action = 'activity_save_items';
                $msg = lang('save_items');
            }
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'items',
                'module_field_id' => $id,
                'activity' => $action,
                'icon' => 'fa-circle-o',
                'value1' => $data['item_name']
            );
            $this->items_model->_table_name = 'tbl_activities';
            $this->items_model->_primary_key = 'activities_id';
            $this->items_model->save($activity);
            // messages for user
            
            $type = "success";
        }
        $message = $msg;
        set_message($type, $message);
        redirect('admin/items/items_list');
    }
    
    public function bulk_delete()
    {
        $selected_id = $this->input->post('ids', true);
        if (!empty($selected_id)) {
            foreach ($selected_id as $id) {
                $result[] = $this->delete_items($id, true);
            }
            echo json_encode($result);
            exit();
        } else {
            $type = "error";
            $message = lang('you_need_select_to_delete');
            echo json_encode(array("status" => $type, 'message' => $message));
            exit();
        }
    }
    
    public function delete_items($id, $bulk = null)
    {
        $deleted = can_action('39', 'deleted');
        if (!empty($deleted)) {
            $items_info = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'items',
                'module_field_id' => $id,
                'activity' => 'activity_items_deleted',
                'icon' => 'fa-circle-o',
                'value1' => $items_info->item_name
            );
            $this->items_model->_table_name = 'tbl_activities';
            $this->items_model->_primary_key = 'activities_id';
            $this->items_model->save($activity);
            
            $this->items_model->_table_name = 'tbl_saved_items';
            $this->items_model->_primary_key = 'saved_items_id';
            $this->items_model->delete($id);
            $type = 'success';
            $message = lang('items_deleted');
        } else {
            $type = "error";
            $message = lang('no_permission');
        }
        if (!empty($bulk)) {
            return (array("status" => $type, 'message' => $message));
        }
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
    
    public function items_group()
    {
        $data['title'] = lang('lead_source');
        $data['subview'] = $this->load->view('admin/items/items_group', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }
    
    public function update_group($id = null)
    {
        $this->items_model->_table_name = 'tbl_customer_group';
        $this->items_model->_primary_key = 'customer_group_id';
        
        $cate_data['customer_group'] = $this->input->post('customer_group', TRUE);
        $cate_data['description'] = $this->input->post('description', TRUE);
        $cate_data['type'] = 'items';
        $id = $this->items_model->save($cate_data, $id);
        
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('customer_group_added'),
            'value1' => $cate_data['customer_group']
        );
        $this->items_model->_table_name = 'tbl_activities';
        $this->items_model->_primary_key = 'activities_id';
        $this->items_model->save($activity);
        
        // messages for user
        $type = "success";
        $msg = lang('customer_group_added');
        if (!empty($id)) {
            $result = array(
                'id' => $id,
                'group' => $cate_data['customer_group'],
                'status' => $type,
                'message' => $msg,
            );
        } else {
            $result = array(
                'status' => $type,
                'message' => $msg,
            );
        }
        echo json_encode($result);
        exit();
    }
    
    public function saved_group($id = null)
    {
        $this->items_model->_table_name = 'tbl_customer_group';
        $this->items_model->_primary_key = 'customer_group_id';
        
        $cate_data['customer_group'] = $this->input->post('customer_group', TRUE);
        $cate_data['description'] = $this->input->post('description', TRUE);
        $cate_data['type'] = 'items';
        
        $id = $this->items_model->save($cate_data, $id);
        
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('customer_group_added'),
            'value1' => $cate_data['customer_group']
        );
        $this->items_model->_table_name = 'tbl_activities';
        $this->items_model->_primary_key = 'activities_id';
        $this->items_model->save($activity);
        
        // messages for user
        $type = "success";
        $msg = lang('customer_group_added');
        $message = $msg;
        set_message($type, $message);
        redirect('admin/items/newitems_group');
    }
    
    public function items_manufacturerlist($id = null, $opt = null)
    {
        $data['title'] = lang('all_items');
        if (!empty($id)) {
            if ($id == 'manufacturer') {
                $data['active'] = 4;
                $data['manufacturer_info'] = $this->items_model->check_by(array('manufacturer_id' => $opt), 'tbl_manufacturer');
            }
        } else {
            $data['active'] = 1;
        }
        $data['subview'] = $this->load->view('admin/items/manufactureerlist', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    public function delete_group($id)
    {
        $customer_group = $this->items_model->check_by(array('customer_group_id' => $id), 'tbl_customer_group');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('activity_delete_a_customer_group'),
            'value1' => $customer_group->customer_group,
        );
        $this->items_model->_table_name = 'tbl_activities';
        $this->items_model->_primary_key = 'activities_id';
        $this->items_model->save($activity);
        
        $this->items_model->_table_name = 'tbl_customer_group';
        $this->items_model->_primary_key = 'customer_group_id';
        $this->items_model->delete($id);
        // messages for user
        $type = "success";
        $message = lang('category_deleted');
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
    
    public function import()
    {
        $header = lang('items');
        $data['title'] = lang('import') . ' ' . $header;
        $data['permission_user'] = $this->items_model->all_permission_user('30');
        $data['type'] = 'items';
        $data['subview'] = $this->load->view('admin/items/import', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    
    public function save_imported()
    {
        $this->load->library('excel');
        ob_start();
        $file = $_FILES["upload_file"]["tmp_name"];
        if (!empty($file)) {
            $valid = false;
            $types = array('Excel2007', 'Excel5', 'CSV');
            foreach ($types as $type) {
                $reader = PHPExcel_IOFactory::createReader($type);
                if ($reader->canRead($file)) {
                    $valid = true;
                }
            }
            if (!empty($valid)) {
                try {
                    $objPHPExcel = PHPExcel_IOFactory::load($file);
                } catch (Exception $e) {
                    die("Error loading file :" . $e->getMessage());
                }
                //All data from excel
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                
                $all_data = array();
                for ($x = 2; $x <= count(array($sheetData)); $x++) {
                    $data['item_name'] = trim($sheetData[$x]["A"]);
                    $data['item_desc'] = trim($sheetData[$x]["B"]);
                    $data['quantity'] = trim($sheetData[$x]["C"]);
                    $unit_cost = str_replace(',', '.', trim($sheetData[$x]["D"]));
                    $data['unit_cost'] = preg_replace("/[^0-9,.]/", "", $unit_cost);
                    $data['unit_type'] = trim($sheetData[$x]["E"]);
                    $data['barcode_symbology'] = 'code128';
                    $taxtname = $this->remove_numbers(trim($sheetData[$x]["F"]));
                    $taxtname = explode("_", $taxtname);
                    $taxtname = array_map('trim', $taxtname);
                    $taxtname = array_filter($taxtname);
                    $tax_id = array();
                    if (is_array($taxtname)) {
                        if (!empty($taxtname)) {
                            foreach ($taxtname as $val) {
                                array_push($tax_id, $this->db->where('tax_rate_name', $val)->get('tbl_tax_rates')->row('tax_rates_id'));
                            }
                        }
                    } else {
                        $tax_id = $this->db->where('tax_rate_name', $taxtname)->get('tbl_tax_rates')->row('tax_rates_id');
                    }
                    if (!empty($tax_id)) {
                        $data['tax_rates_id'] = json_encode($tax_id);
                    } else {
                        $data['tax_rates_id'] = '-';
                    }
                    $category_code = trim($sheetData[$x]["G"]);
                    $category_code_info = $this->items_model->check_by(array('type' => 'items', 'customer_group' => $category_code), 'tbl_customer_group');
                    if (!empty($category_code_info)) {
                        $data['customer_group_id'] = $category_code_info->customer_group_id;
                    } else {
                        $category['type'] = 'items';
                        $category['customer_group'] = $category_code;
                        $this->items_model->_table_name = "tbl_customer_group"; //table name
                        $this->items_model->_primary_key = "customer_group_id";
                        $data['customer_group_id'] = $this->items_model->save($category);
                    }
                    $all_data[] = $data;
                }
                if (!empty($all_data)) {
                    $this->db->insert_batch('tbl_saved_items', $all_data);
                }
                $type = 'success';
                $message = lang('save_new_items');
                $redirect = 'items';
            } else {
                $type = 'error';
                $message = "Sorry your uploaded file type not allowed ! please upload XLS/CSV File ";
            }
        } else {
            $type = 'error';
            $message = "You did not Select File! please upload XLS/CSV File ";
        }
        set_message($type, $message);
        redirect('admin/items/items_list');
    }
    
    function remove_numbers($string)
    {
        $string = preg_replace("/\([^)]+\)/", "", $string);
        $num = array('0.', '1.', '2.', '3.', '4.', '5.', '6.', '7.', '8.', '9.');
        return str_replace($num, '_', $string);
    }
    
    public function items_details($id = NULL)
    {
        $data['title'] = lang('items_details');
        $staffwarehouse = $this->session->userdata('warehouse_id');
        if (!empty($id)) {
            $data['items_info'] = $this->items_model->check_by(array('saved_items_id' => $id), 'tbl_saved_items');
            $data['warehouse_info'] = $this->items_model->warehouse_porduct($id);
            if (!empty($staffwarehouse)) {
                $data['staff_warehouse_info'] = $this->items_model->warehouse_porduct($id, $staffwarehouse);
            }
            $data['barcode'] = $this->product_barcode($data['items_info']->code, $data['items_info']->barcode_symbology, 60);
        }
        
        
        $data['subview'] = $this->load->view('admin/items/items_details', $data, false);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }
    
    public function single_barcode($id)
    {
        $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
        $product = get_row('tbl_saved_items', array('saved_items_id' => $id));
        $total = $product->quantity - 1;
        $html = "";
        $html .= '<table class="table table-bordered table-centered mb0">
        <tbody><tr>';
        if ($product->quantity > 0) {
            for ($r = 0; $r <= $total; $r++) {
                if ($r % 4 == 0) {
                    $html .= '</tr><tr>';
                }
                //                $rw = (bool)($r & 1);
                //                    $html .= $rw ? '</tr><tr>' : '';
                $html .= '<td class="text-center"><h4 class="m-sm">' . config_item('website_name') . '</h4><strong>' . $product->item_name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 60) . ' <br><span class="price">' . lang('price') . ': ' . display_money($product->unit_cost, $currency->symbol) . '</span></td>';
            }
        } else {
            for ($r = 0; $r <= 9; $r++) {
                if ($r != 1) {
                    $rw = (bool)($r & 1);
                    $html .= $rw ? '</tr><tr>' : '';
                }
                $html .= '<td><h4>' . config_item('website_name') . '</h4><strong class="text-center">' . $product->item_name . '</strong><br>' . $this->product_barcode($product->code, $product->barcode_symbology, 60) . ' <br><span class="price">' . lang('price') . ': ' . display_money($product->unit_cost, $currency->symbol) . '</span></td>';
            }
        }
        $html .= '</tr></tbody>
        </table>';
        $data['html'] = $html;
        $data['title'] = lang("print_barcodes") . ' (' . $product->item_name . ')';
        $data['subview'] = $this->load->view('admin/items/single_barcode', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    function product_barcode($product_code = NULL, $bcs = 'code128', $height = 60)
    {
        return "<img src='" . site_url('admin/items/gen_barcode/' . $product_code . '/' . $bcs . '/' . $height) . "' alt='{$product_code}' class='bcimg' />";
    }
    
    function gen_barcode($product_code = NULL, $bcs = 'code128', $height = 60, $text = 1)
    {
        $drawText = ($text != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $product_code, 'barHeight' => $height, 'drawText' => $drawText, 'factor' => 1);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        echo Zend_Barcode::render($bcs, 'image', $barcodeOptions, $rendererOptions);
        exit();
    }
    
    public function transferItem()
    {
        $data['title'] = lang("transferItem");
        $data['subview'] = $this->load->view('admin/items/transferItem/manage', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    
    public function transferItemList()
    {
        if (!empty($this->input->is_ajax_request())) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_transfer_itemlist';
            $this->datatables->join_table = array('tbl_transfer_item', 'tbl_saved_items');
            $this->datatables->join_where = array('tbl_transfer_item.transfer_item_id=tbl_transfer_itemlist.transfer_item_id', 'tbl_saved_items.saved_items_id=tbl_transfer_itemlist.saved_items_id');
            
            $custom_field = custom_form_table_search(18);
            $action_array = array('transfer_itemList_id');
            $main_column = array('tbl_transfer_item.reference_no', 'tbl_transfer_item.status', 'tbl_transfer_item.from_warehouse_id', 'tbl_transfer_item.to_warehouse_id', 'tbl_transfer_item.status');
            $result = array_merge($main_column, $custom_field, $action_array);
            $this->datatables->column_order = $result;
            $this->datatables->column_search = $result;
            $this->db->group_by('reference_no');
            $this->datatables->order = array('transfer_itemList_id' => 'desc');
            $fetch_data = make_datatables();
            $data = array();
            $edited = can_action('187', 'edited');
            $deleted = can_action('187', 'deleted');
            foreach ($fetch_data as $_key => $v_items) {
                $action = null;
                $can_edit = $this->items_model->can_action('tbl_transfer_itemlist', 'edit', array('transfer_itemList_id' => $v_items->transfer_itemList_id));
                $can_delete = $this->items_model->can_action('tbl_transfer_itemlist', 'delete', array('transfer_itemList_id' => $v_items->transfer_itemList_id));
                
                
                $item_name = !empty($v_items->reference_no) ? $v_items->reference_no : $v_items->reference_no;
                $formmane = get_row('tbl_warehouse', array('warehouse_id' => $v_items->from_warehouse_id));
                $tomane = get_row('tbl_warehouse', array('warehouse_id' => $v_items->to_warehouse_id));
                
                $sub_array = array();
                if (!empty($deleted)) {
                    $sub_array[] = '<div class="checkbox c-checkbox" ><label class="needsclick"> <input value="' . $v_items->transfer_itemList_id . '" type="checkbox"><span class="fa fa-check"></span></label></div>';
                }
                //                $sub_array[] = '<a data-toggle="modal" data-target="#myModal_extra_lg" href="' . base_url('admin/items/transfer_details/' . $v_items->transfer_itemList_id) . '"><strong class="block">' . $item_name .'</strong>';
                $sub_array[] = '<a href="' . base_url() . 'admin/items/transfer_details/' . $v_items->transfer_itemList_id . '"><strong class="block">' . $item_name . '</strong></a>';
                $sub_array[] = strftime(config_item('date_format'), strtotime($v_items->date));
                $sub_array[] = $formmane->warehouse_name;
                $sub_array[] = $tomane->warehouse_name;
                $invoice_view = config_item('invoice_view');
                if (!empty($invoice_view) && $invoice_view == '2') {
                    $sub_array[] = $v_items->hsn_code;
                }
                
                $sub_array[] = display_money($this->items_model->transfer_calculate_to('transfer_cost', $v_items->transfer_item_id));
                //                $sub_array[] = $v_items->status;
                
                $statusss = null;
                if (!empty($v_items->status)) {
                    if ($v_items->status == 'complete') {
                        $statusss = "<span class='label label-success'>" . lang($v_items->status) . "</span>";
                    } elseif ($v_items->status == 'approved') {
                        $statusss = "<span class='label label-primary'>" . lang($v_items->status) . "</span>";
                    } elseif ($v_items->status == 'rejected') {
                        $statusss = "<span class='label label-danger'>" . lang($v_items->status) . "</span>";
                    } else {
                        $statusss = "<span class='label label-warning'>" . lang($v_items->status) . "</span>";
                    }
                }
                $change_status = null;
                if (!empty($can_edit) || !empty($edited)) {
                    $ch_url = base_url() . 'admin/items/change_status/';
                    $change_status = '<div class="btn-group">
        <button class="btn btn-xs btn-default dropdown-toggle"
                data-toggle="dropdown">
            <span class="caret"></span></button>
        <ul class="dropdown-menu animated zoomIn">
            <li>
                <a href="' . $ch_url . $v_items->transfer_item_id . '/pending' . '">' . lang('pending') . '</a>
                </li>
            
            <li>
                <a href="' . $ch_url . $v_items->transfer_item_id . '/complete' . '">' . lang('completed') . '</a>
            </li>
            <li>
                <a href="' . $ch_url . $v_items->transfer_item_id . '/approved' . '">' . lang('approved') . '</a>
                </li>
            
            <li>
                <a href="' . $ch_url . $v_items->transfer_item_id . '/rejected' . '">' . lang('rejected') . '</a>
            </li>
        </ul>
    </div>';
                }
                $sub_array[] = $statusss . ' ' . $change_status;
                
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= btn_edit('admin/items/createTransferItem/' . $v_items->transfer_item_id) . ' ';
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/items/delete_transfer/" . $v_items->transfer_item_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
                }
                if (!empty($can_edit) && !empty($edited)) {
                    $action .= btn_view('admin/items/transfer_details/' . $v_items->transfer_itemList_id) . ' ';
                }
                $sub_array[] = $action;
                
                
                if (!is_numeric($v_items->tax_rates_id)) {
                    $tax_rates = json_decode($v_items->tax_rates_id);
                } else {
                    $tax_rates = null;
                }
                
                
                $custom_form_table = custom_form_table(18, $v_items->saved_items_id);
                
                if (!empty($custom_form_table)) {
                    foreach ($custom_form_table as $c_label => $v_fields) {
                        $sub_array[] = $v_fields;
                    }
                }
                
                
                $data[] = $sub_array;
            }
            
            //
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }
    
    public function transfer_details($id = NULL)
    {
        $data['title'] = 'Transfer Details';
        if (!empty($id)) {
            
            $data['item_info'] = $this->db->query("SELECT tbl_transfer_itemlist.*, tbl_transfer_item.*,
            (select  tbl_warehouse.warehouse_name from tbl_warehouse where tbl_transfer_item. from_warehouse_id = tbl_warehouse.warehouse_id) as from_warehouse_name,
            (select  tbl_warehouse.warehouse_name from tbl_warehouse where tbl_transfer_item. to_warehouse_id = tbl_warehouse.warehouse_id) as to_warehouse_name,
            (select  tbl_warehouse.warehouse_code from tbl_warehouse where tbl_transfer_itemlist. warehouse_id = tbl_warehouse.warehouse_id) as warehouse_code
            FROM tbl_transfer_itemlist
            JOIN tbl_transfer_item ON tbl_transfer_itemlist.transfer_item_id = tbl_transfer_item.transfer_item_id
                WHERE transfer_itemList_id= ? ", array($id))->row();
            
            //exit;
            
            // print_r($data['item_info']); exit;
            
        }
        
        
        $data['subview'] = $this->load->view('admin/items/transferItem/transfer_details', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    
    public function createTransferItem($id = null)
    {
        
        
        $data['title'] = lang("transferItem");
        $data['dropzone'] = true;
        if (!empty($id)) {
            $data['items_info'] = get_row('tbl_transfer_item', array('transfer_item_id' => $id));
        }
        $data['items_info'] = get_row('tbl_transfer_item', array('transfer_item_id' => $id));
        
        $data['warehouseList'] = $this->items_model->select_data('tbl_warehouse', 'warehouse_id', 'warehouse_name', array('status' => 'published'));
        $data['subview'] = $this->load->view('admin/items/transferItem/create', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }
    
    public function saveTransferItem($id = null)
    {
        $created = can_action_by_label('transferItem', 'created');
        $edited = can_action_by_label('transferItem', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $this->load->model('invoice_model');
            $data = $this->items_model->array_from_post(array('reference_no', 'date', 'to_warehouse_id', 'status', 'notes', 'show_quantity_as'));
            
            
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
            
            $data['shipping_cost'] = $this->input->post('adjustment', true);
            $data['from_warehouse_id'] = $this->input->post('warehouse_id', true);
            
            
            if ($data['from_warehouse_id'] == $data['to_warehouse_id']) {
                set_message('error', lang('please_select_different_warehouse'));
                redirect('admin/items/createTransferItem');
            }
            if (!empty($data['to_warehouse_id'])) {
                $data['user_id'] = my_id();
                
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
                        $assigned_to = $this->items_model->array_from_post(array('assigned_to'));
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
                        redirect('admin/items/createTransferItem');
                    } else {
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
                
                $this->invoice_model->_table_name = 'tbl_transfer_item';
                $this->invoice_model->_primary_key = 'transfer_item_id';
                
                if (!empty($id)) {
                    $invoice_id = $id;
                    
                    $can_edit = $this->invoice_model->can_action('tbl_invoices', 'edit', array('invoices_id' => $id));
                    if (!empty($can_edit)) {
                        $this->invoice_model->save($data, $id);
                    } else {
                        set_message('error', lang('there_in_no_value'));
                        redirect('admin/items/createTransferItem');
                    }
                    $action = ('activity_invoice_updated');
                    $description = 'not_invoice_updated';
                    $msg = lang('invoice_updated');
                } else {
                    $this->invoice_model->_table_name = 'tbl_transfer_item';
                    $this->invoice_model->_primary_key = 'transfer_item_id';
                    $invoice_id = $this->invoice_model->save($data);
                    
                    $action = ('activity_invoice_created');
                    $description = 'not_invoice_created';
                    $msg = lang('invoice_created');
                }
                $qty_calculation = config_item('qty_calculation_from_items');
                $removed_items = $this->input->post('removed_items', TRUE);
                if (!empty($removed_items)) {
                    foreach ($removed_items as $r_id) {
                        if ($r_id != 'undefined') {
                            if (!empty($qty_calculation) && $qty_calculation == 'Yes') {
                                $itemInfo = get_row('tbl_items', array('items_id' => $r_id));
                                $this->invoice_model->return_items($itemInfo->saved_items_id, $itemInfo->quantity, $data['warehouse_id']);
                            }
                            $this->db->where('items_id', $r_id);
                            $this->db->delete('tbl_items');
                        }
                    }
                }
                
                $itemsid = $this->input->post('items_id', TRUE);
                $items_data = $this->input->post('items', true);
                
                if (!empty($items_data)) {
                    $index = 0;
                    foreach ($items_data as $items) {
                        unset($items['invoice_items_id']);
                        unset($items['total_qty']);
                        $items['transfer_item_id'] = $invoice_id;
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
                        if (empty($items['saved_items_id'])) {
                            $items['saved_items_id'] = 0;
                        }
                        
                        $price = $items['quantity'] * $items['unit_cost'];
                        $items['item_tax_total'] = ($price / 100 * $tax);
                        $items['total_cost'] = $price;
                        $items['warehouse_id'] = $data['to_warehouse_id'];
                        // get all client
                        
                        if (!empty($data['to_warehouse_id']) && !empty($data['from_warehouse_id']) && $data['status'] == 'approved') {
                            $this->items_model->_table_name = 'tbl_warehouses_products';
                            $this->items_model->_primary_key = 'id';
                            $check = get_row('tbl_warehouses_products', array('warehouse_id ' => $data['to_warehouse_id'], 'product_id' => $items['saved_items_id']));
                            $check2 = get_row('tbl_warehouses_products', array('warehouse_id ' => $data['from_warehouse_id'], 'product_id' => $items['saved_items_id']));
                            
                            if (!empty($check)) {
                                $_data['quantity'] = $items['quantity'] + $check->quantity;
                                $_data_id = $check->id;
                                $this->items_model->save($_data, $_data_id);
                                $_data['quantity'] = $items['quantity'] - $check->quantity;
                                $_data_id = $check2->id;
                                $this->items_model->save($_data, $_data_id);
                            } else {
                                $_data['quantity'] = $items['quantity'];
                                $_data['warehouse_id'] = $data['to_warehouse_id'];
                                $_data['product_id'] = $items['saved_items_id'];
                                $this->items_model->save($_data);
                                $_data['quantity'] = $items['quantity'] - $check2->quantity;
                                $_data_id = $check2->id;
                                $this->items_model->save($_data, $_data_id);
                            }
                        }
                        
                        
                        $this->invoice_model->_table_name = 'tbl_transfer_itemlist';
                        $this->invoice_model->_primary_key = 'transfer_itemList_id';
                        
                        if (!empty($items['items_id'])) {
                            $items_id = $items['items_id'];
                            unset($items['items_id']);
                            if (!empty($qty_calculation) && $qty_calculation == 'Yes') {
                                // $this->check_existing_qty($items_id, $items['quantity']);
                            }
                            $this->invoice_model->save($items, $items_id);
                        } else {
                            if (!empty($items['saved_items_id']) && $items['saved_items_id'] != 'undefined') {
                                // $this->invoice_model->reduce_items($items['saved_items_id'], $items['quantity']);
                            }
                            
                            $transfer_itemList_id = $this->invoice_model->save($items);
                        }
                        $index++;
                    }
                }
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'Items',
                    'module_field_id' => $invoice_id,
                    'activity' => $action,
                    'icon' => 'fa-shopping-cart',
                    'link' => 'admin/items/transfer_details/' . $transfer_itemList_id,
                    'value1' => $data['reference_no']
                );
                $this->invoice_model->_table_name = 'tbl_activities';
                $this->invoice_model->_primary_key = 'activities_id';
                $this->invoice_model->save($activity);
                
                // messages for user
                $type = "success";
                $message = $msg;
                set_message($type, $message);
                redirect('admin/items/transferItem');
            } else {
                redirect('admin/items/createTransferItem');
            }
        } else {
            
            set_message('error', lang('please_select_warehouse'));
            
            
            redirect('admin/items/createTransferItem');
        }
    }
    
    
    function check_existing_qty($items_id, $qty)
    {
        $items_info = $this->db->where('items_id', $items_id)->get('tbl_items')->row();
        if ($items_info->quantity != $qty) {
            if ($qty > $items_info->quantity) {
                $reduce_qty = $qty - $items_info->quantity;
                if (!empty($items_info->saved_items_id)) {
                    $this->invoice_model->reduce_items($items_info->saved_items_id, $reduce_qty);
                }
            }
            if ($qty < $items_info->quantity) {
                $return_qty = $items_info->quantity - $qty;
                if (!empty($items_info->saved_items_id)) {
                    $this->invoice_model->return_items($items_info->saved_items_id, $return_qty);
                }
            }
        }
        return true;
    }
    
    function getItemBywarehouse($warehouse_id)
    {
        $saved_items = $this->items_model->select_group_data('tbl_saved_items', 'customer_group', 'saved_items_id', 'saved_items_id,item_name,item_desc,unit_cost', ['tbl_customer_group.type' => 'items', 'tbl_saved_items.warehouse_id' => $warehouse_id], ['tbl_customer_group' => 'tbl_customer_group.customer_group_id = tbl_saved_items.customer_group_id'], true);
        $html = '<option value=""></option>';
        if (!empty($saved_items)) {
            foreach ($saved_items as $group => $v_saved_items) {
                $html .= '<optgroup label="' . $group . '">';
                foreach ($v_saved_items as $v_saved_item) {
                    $html .= '<option  data-subtext="' . strip_html_tags(mb_substr($v_saved_item->item_desc, 0, 200)) . '...' . '" value="' . $v_saved_item->saved_items_id . '" >' . '(' . display_money($v_saved_item->unit_cost, default_currency()) . ')' . $v_saved_item->item_name . '</option>';
                }
                $html .= '</optgroup>';
            }
        }
        echo json_encode($html);
        exit();
    }
    
    public function manuallyItems()
    {
        $data['title'] = lang('added') . ' ' . lang('manually');
        $data['subview'] = $this->load->view('admin/items/manually_items', $data, false);
        $this->load->view('admin/_layout_modal', $data);
    }
    
    public function saved_manufacturer($id = null)
    {
        $data = $this->items_model->array_from_post(array('manufacturer', 'description'));
        $this->items_model->_table_name = "tbl_manufacturer"; //table name
        $this->items_model->_primary_key = "manufacturer_id";
        $id = $this->items_model->save($data, $id);
        // messages for user
        $type = "success";
        if (!empty($id)) {
            $message = lang('manufacturer_added');
        } else {
            $message = lang('manufacturer_updated');
        }
        set_message($type, $message);
        redirect('admin/items/items_manufacturerlist');
    }
    
    // delete manufacturer
    public function delete_manufacturer($id)
    {
        $this->items_model->_table_name = "tbl_manufacturer"; //table name
        $this->items_model->_primary_key = "manufacturer_id";
        $this->items_model->delete($id);
        
        $type = "success";
        $message = lang('manufacturer_deleted');
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
    
    public function TransferItemDetails($id = null)
    {
        $data['title'] = lang("transferItem");
        $data['dropzone'] = true;
        $data['warehouseList'] = $this->items_model->select_data('tbl_warehouse', 'warehouse_id', 'warehouse_name', array('status' => 'published'));
        $data['subview'] = $this->load->view('admin/items/transferItem/TransferItemDetails', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
        
    }
    
    public
    function clone_transfer_item($item_id)
    {
        
        $edited = can_action('13', 'edited');
        
        $can_edit = $this->items_model->can_action('tbl_transfer_itemlist', 'edit', array('transfer_itemList_id' => $item_id));
        
        if (!empty($can_edit) && !empty($edited)) {
            
            $data['item_info'] = $this->items_model->check_by(array('transfer_itemList_id' => $item_id), 'tbl_transfer_itemlist');
            $data['all_warehouse'] = $this->items_model->get_permission('tbl_warehouse');
            
            
            $data['modal_subview'] = $this->load->view('admin/items/transferItem/_modal_clone_transferitem', $data, FALSE);
        } else {
            set_message('error', lang('there_in_no_value'));
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    
    public function manufacturer()
    {
        $data['title'] = lang('manufacturer');
        $data['subview'] = $this->load->view('admin/items/manufacturer', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }
    
    public function update_manufacturer($id = null)
    {
        $this->items_model->_table_name = 'tbl_manufacturer';
        $this->items_model->_primary_key = 'manufacturer_id';
        
        $cate_data['manufacturer'] = $this->input->post('manufacturer', TRUE);
        // update root category
        $where = array('manufacturer' => $cate_data['manufacturer']);
        // duplicate value check in DB
        if (!empty($id)) { // if id exist in db update data
            $manufacturer_id = array('manufacturer_id !=' => $id);
        } else { // if id is not exist then set id as null
            $manufacturer_id = null;
        }
        // check whether this input data already exist or not
        $check_category = $this->items_model->check_update('tbl_manufacturer', $where, $manufacturer_id);
        if (!empty($check_category)) { // if input data already exist show error alert
            // massage for user
            $type = 'error';
            $msg = "<strong style='color:#000'>" . $cate_data['manufacturer'] . '</strong>  ' . lang('already_exist');
        } else { // save and update query
            $id = $this->items_model->save($cate_data, $id);
            
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('manufacturer_added'),
                'value1' => $cate_data['manufacturer']
            );
            $this->items_model->_table_name = 'tbl_activities';
            $this->items_model->_primary_key = 'activities_id';
            $this->items_model->save($activity);
            
            // messages for user
            $type = "success";
            $msg = lang('manufacturer_added');
        }
        
        if (!empty($id)) {
            $result = array(
                'id' => $id,
                'manufacturer' => $cate_data['manufacturer'],
                'status' => $type,
                'message' => $msg,
            );
        } else {
            $result = array(
                'status' => $type,
                'message' => $msg,
            );
        }
        echo json_encode($result);
        exit();
    }
    
    public function pdf_transferitem($id)
    {
        $data['item_info'] = $this->db->query("SELECT tbl_transfer_itemlist.*, tbl_transfer_item.*,
        (select  tbl_warehouse.warehouse_name from tbl_warehouse where tbl_transfer_item. from_warehouse_id = tbl_warehouse.warehouse_id) as from_warehouse_name,
        (select  tbl_warehouse.warehouse_name from tbl_warehouse where tbl_transfer_item. to_warehouse_id = tbl_warehouse.warehouse_id) as to_warehouse_name,
        (select  tbl_warehouse.warehouse_code from tbl_warehouse where tbl_transfer_itemlist. warehouse_id = tbl_warehouse.warehouse_id) as warehouse_code
        FROM tbl_transfer_itemlist
        JOIN tbl_transfer_item ON tbl_transfer_itemlist.transfer_item_id = tbl_transfer_item.transfer_item_id
            WHERE transfer_itemList_id= ? ", array($id))->row();
        
        $data['title'] = lang('transfer') . ' ' . "PDF"; //Page title
        $this->load->helper('dompdf');
        $viewfile = $this->load->view('admin/items/transferItem/transfer_pdf', $data, TRUE);
        
        pdf_create($viewfile, lang('transfer') . '# ' . $data['item_info']->reference_no);
    }
    
    public function change_status($id, $status)
    {
        $edited = can_action('39', 'edited');
        $can_edit = $this->invoice_model->can_action('tbl_transfer_item', 'edit', array('transfer_item_id' => $id));
        if (!empty($can_edit) && !empty($edited)) {
            $item_info = $this->items_model->check_by(array('transfer_item_id' => $id), 'tbl_transfer_item');
            $now_status = $item_info->status;
            $to_warehouse_id = $item_info->to_warehouse_id;
            $from_warehouse_id = $item_info->from_warehouse_id;
            $product_list = get_result('tbl_transfer_itemlist', array('transfer_item_id' => $item_info->transfer_item_id));
            
            if ($now_status == 'approved' && $status != 'approved') {
                if ($status != "complete") {
                    
                    $this->UpdateProductQty($product_list, $from_warehouse_id, $to_warehouse_id);
                }
            } elseif ($now_status == 'complete' && $status != 'complete') {
                $this->UpdateProductQty($product_list, $from_warehouse_id, $to_warehouse_id);
            } elseif ($status == 'approved' || $status == 'complete') {
                $this->UpdateProductQty($product_list, $to_warehouse_id, $from_warehouse_id);
            }
            
            $data['status'] = $status;
            $this->items_model->_table_name = 'tbl_transfer_item';
            $this->items_model->_primary_key = 'transfer_item_id';
            $lastid = $this->items_model->save($data, $id);
            // messages for user
            $type = "success";
            $message = lang('change_status');
            set_message($type, $message);
        }
        if (empty($_SERVER['HTTP_REFERER'])) {
            redirect('admin/projects');
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
    // to_warehouse_id is increase productQty in warehouse
    // from_warehouse_id is decrease productQty in warehouse
    public function UpdateProductQty($product_list, $to_warehouse_id, $from_warehouse_id)
    {
        $this->items_model->_table_name = 'tbl_warehouses_products';
        $this->items_model->_primary_key = 'id';
        
        foreach ($product_list as $key) {
            
            $check_already_exist_by_to_id = get_row('tbl_warehouses_products', array('warehouse_id ' => $to_warehouse_id, 'product_id' => $key->saved_items_id));
            $check_already_exist_by_from_id = get_row('tbl_warehouses_products', array('warehouse_id ' => $from_warehouse_id, 'product_id' => $key->saved_items_id));
            
            if (!empty($check_already_exist_by_to_id)) {
                $_data['quantity'] = $key->quantity + $check_already_exist_by_to_id->quantity;
                $_data_id = $check_already_exist_by_to_id->id;
                $this->items_model->save($_data, $_data_id);
                $_data2['quantity'] = $check_already_exist_by_from_id->quantity - $key->quantity;
                $data2_id = $check_already_exist_by_from_id->id;
                $this->items_model->save($_data2, $data2_id);
            } else {
                
                $_data['quantity'] = $key->quantity;
                $_data['warehouse_id'] = $to_warehouse_id;
                $_data['product_id'] = $key->saved_items_id;
                $this->items_model->save($_data);
                
                $data['quantity'] = $check_already_exist_by_from_id->quantity - $key->quantity;
                $_data_id = $check_already_exist_by_from_id->id;
                $this->items_model->save($data, $_data_id);
            }
        }
    }
    
    public function delete_transfer($id)
    {
        $deleted = can_action('39', 'deleted');
        if (!empty($deleted)) {
            $items_info = $this->items_model->check_by(array('transfer_item_id' => $id), 'tbl_transfer_item');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'items',
                'module_field_id' => $id,
                'activity' => 'activity_transfer_items_deleted',
                'icon' => 'fa-circle-o',
                'value1' => $items_info->reference_no
            );
            $this->items_model->_table_name = 'tbl_activities';
            $this->items_model->_primary_key = 'activities_id';
            //$this->items_model->save($activity);
            
            $this->items_model->_table_name = 'tbl_transfer_item';
            $this->items_model->_primary_key = 'transfer_item_id';
            $this->items_model->delete($id);
            $to_warehouse_id = $items_info->to_warehouse_id;
            $from_warehouse_id = $items_info->from_warehouse_id;
            $transfer_info = get_result('tbl_transfer_itemlist', array('transfer_item_id' => $items_info->transfer_item_id));
            if (!empty($transfer_info)) {
                $this->UpdateProductQty($transfer_info, $from_warehouse_id, $to_warehouse_id);
                
                foreach ($transfer_info as $t_v_files) {
                    $this->items_model->_table_name = "tbl_transfer_itemlist"; //table name
                    $this->items_model->delete_multiple(array('transfer_item_id' => $t_v_files->transfer_item_id));
                }
            }
            
            
            $type = 'success';
            $message = lang('items_deleted');
        } else {
            $type = "error";
            $message = lang('no_permission');
        }
        if (!empty($bulk)) {
            return (array("status" => $type, 'message' => $message));
        }
        echo json_encode(array("status" => $type, 'message' => $message));
        exit();
    }
}
