<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Warehouse extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('warehouse_model');
    }

    public function manage()
    {
        $data['title'] = lang('manage_warehouse');
        $data['subview'] = $this->load->view('admin/warehouse/manage', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function create($id = NULL, $inline = NULL)
    {
        $data['title'] = lang('create') . ' ' . lang('warehouse');
        if (!empty($id)) {
            $can_edit = $this->warehouse_model->can_action('tbl_warehouse', 'edit', array('warehouse_id' => $id));
            $edited = can_action('186', 'edited');
            if (!empty($can_edit) && !empty($edited)) {
                $data['warehouse_info'] = $this->warehouse_model->check_by(array('warehouse_id' => $id), 'tbl_warehouse');
            }
        }
        $data['permission_user'] = $this->warehouse_model->all_permission_user('186');
        $data['inline'] = $inline;
        if (!empty($inline)) {
            $data['subview'] = $this->load->view('admin/warehouse/create', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data); //page load
        } else {
            $data['subview'] = $this->load->view('admin/warehouse/create', $data, TRUE);
            $this->load->view('admin/_layout_main', $data); //page load
        }
    }

    public function save_warehouse($id = NULL, $inline = NULL)
    {
        if (!empty($inline)) {
            $id = NULL;
        }
        $created = can_action('186', 'created');
        $edited = can_action('186', 'edited');
        if (!empty($created) || !empty($edited) && !empty($id)) {
            $this->warehouse_model->_table_name = 'tbl_warehouse';
            $this->warehouse_model->_primary_key = 'warehouse_id';
            $data = $this->warehouse_model->array_from_post(array('warehouse_code', 'warehouse_name', 'phone', 'mobile', 'email', 'address', 'status'));
            if (empty($data['status'])) {
                $data['status'] = 'published';
            }
            // update root category
            $where = array('warehouse_code' => $data['warehouse_code']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $warehouse_id = array('warehouse_id !=' => $id);
            } else { // if id is not exist then set id as null
                $warehouse_id = null;
            }

            // check whether this input data already exist or not
            $check_warehouse = $this->warehouse_model->check_update('tbl_warehouse', $where, $warehouse_id);
            if (!empty($check_warehouse)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = "<strong style='color:#000'>" . $data['warehouse_name'] . '</strong>  ' . lang('already_exist');
            } else { // save and update query
                if (!empty($_FILES['image']['name'])) {
                    $val = $this->warehouse_model->uploadImage('image');
                    $val == TRUE || redirect('admin/warehouse/create');
                    $data['image'] = $val['path'];
                }

                $permission = $this->input->post('permission', true);
                if (!empty($permission)) {
                    if ($permission == 'everyone') {
                        $assigned = 'all';
                    } else {
                        $assigned_to = $this->warehouse_model->array_from_post(array('assigned_to'));
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
                        redirect('admin/warehouse/create');
                    } else {
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
                if (!empty($id)) {
                    $can_edit = $this->warehouse_model->can_action('tbl_warehouse', 'edit', array('warehouse_id' => $id));
                    if (!empty($can_edit)) {
                        $return_id = $this->warehouse_model->save($data, $id);
                    } else {
                        set_message('error', lang('there_in_no_value'));
                        redirect('admin/warehouse/create');
                    }
                } else {
                    $return_id = $this->warehouse_model->save($data);
                }
                if (!empty($id)) {
                    $id = $id;
                    $action = 'activity_update_warehouse';
                    $msg = lang('update_warehouse');
                } else {
                    $id = $return_id;
                    $action = 'activity_save_warehouse';
                    $msg = lang('save_warehouse');
                }
                save_custom_field(21, $id);
                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'warehouse',
                    'module_field_id' => $id,
                    'activity' => $action,
                    'icon' => 'fa-circle-o',
                    'value1' => $data['warehouse_code'] . ' ' . $data['warehouse_name']
                );
                $this->warehouse_model->_table_name = 'tbl_activities';
                $this->warehouse_model->_primary_key = 'activities_id';
                $this->warehouse_model->save($activity);
                $type = "success";
            }
            $message = $msg;
            set_message($type, $message);
        }
        if (!empty($inline)) {
            if (!empty($id)) {
                $result = array(
                    'fieldName' => 'warehouse_id',
                    'fieldId' => $id,
                    'fieldValue' => $data['warehouse_name'],
                    'status' => $type,
                    'message' => $msg,
                    'modal' => 'myModal_lg',
                );
            } else {
                $result = array(
                    'modal' => 'myModal_lg',
                    'status' => $type,
                    'message' => $msg,
                );
            }
            echo json_encode($result);
            exit();
        } else {
            redirect('admin/warehouse/manage');
        }
    }


    public function warehouseList()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');
            $this->datatables->table = 'tbl_warehouse';
            $this->datatables->column_order = array('warehouse_code', 'warehouse_name', 'phone', 'mobile', 'email', 'address', 'status');
            $this->datatables->column_search = array('warehouse_code', 'warehouse_name', 'phone', 'mobile', 'email', 'address', 'status');
            $this->datatables->order = array('warehouse_id' => 'desc');

            $fetch_data = $this->datatables->get_datatable_permission();

            $data = array();

            $edited = can_action('186', 'edited');
            $deleted = can_action('186', 'deleted');
            foreach ($fetch_data as $key => $row) {
                $action = null;
                $can_edit = $this->warehouse_model->can_action('tbl_warehouse', 'edit', array('warehouse_id' => $row->warehouse_id));
                $can_delete = $this->warehouse_model->can_action('tbl_warehouse', 'delete', array('warehouse_id' => $row->warehouse_id));
                $sub_array = array();
                $sub_array[] = $row->warehouse_code;
                $sub_array[] = $row->warehouse_name;
                $sub_array[] = $row->phone;
                $sub_array[] = $row->mobile;
                $sub_array[] = $row->email;
                $sub_array[] = $row->address;

                if (!empty($can_edit) && !empty($edited)) {
                    if ($row->status == 'unpublished') {
                        // $action .= '<a href="' . base_url('admin/warehouse/change_status/published/' . $row->warehouse_id) . '" data-toggle="tooltip" title="' . lang('click_to_published') . '" class="btn btn-xs btn-danger mr">' . lang('sink') . ' </a> ' . ' ';
                    }
                    $action .= '<a data-id="' . $row->warehouse_id . '" href="' . base_url('admin/warehouse/make_default/' . $row->warehouse_id) . '" data-toggle="tooltip" title="' . lang('default') . '" class="btn btn-xs btn-warning "> ' . lang('default') . ' </a> ' . ' ';


                    if ($row->status == 'unpublished') {
                        $action .= '<a href="' . base_url('admin/warehouse/change_status/published/' . $row->warehouse_id) . '" data-toggle="tooltip" title="' . lang('click_to_published') . '" class="btn btn-xs btn-danger mr">' . lang('unpublished') . ' </a> ' . ' ';
                    } else {
                        $action .= '<a href="' . base_url('admin/warehouse/change_status/unpublished/' . $row->warehouse_id) . '" data-toggle="tooltip" title="' . lang('click_to_unpublished') . '" class="btn btn-xs btn-success"> ' . lang('published') . ' </a> ' . ' ';
                    }


                    $action .= btn_edit('admin/warehouse/create/' . $row->warehouse_id) . ' ';
                }
                if (!empty($can_delete) && !empty($deleted)) {
                    $action .= ajax_anchor(base_url("admin/warehouse/delete_warehouse/$row->warehouse_id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $key));
                }
                $sub_array[] = $action;
                $data[] = $sub_array;
            }
            render_table($data);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function delete_warehouse($id)
    {
        $deleted = can_action('122', 'deleted');
        if (!empty($deleted)) {
            $warehouse = $this->warehouse_model->check_by(array('warehouse_id' => $id), 'tbl_warehouse');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_category'),
                'value1' => $warehouse->warehouse_name,
            );
            $this->warehouse_model->_table_name = 'tbl_activities';
            $this->warehouse_model->_primary_key = 'activities_id';
            $this->warehouse_model->save($activity);

            $this->warehouse_model->_table_name = 'tbl_warehouse';
            $this->warehouse_model->_primary_key = 'warehouse_id';
            $this->warehouse_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('warehouse_deleted');
            // messages for user
            echo json_encode(array("status" => $type, 'message' => $message));
            exit();
        } else {
            echo json_encode(array("status" => 'error', 'message' => lang('there_in_no_value')));
            exit();
        }
    }

    public function change_status($type, $id)
    {
        $can_edit = $this->warehouse_model->can_action('tbl_warehouse', 'edit', array('warehouse_id' => $id));
        $edited = can_action('186', 'edited');
        if (!empty($can_edit) && !empty($edited)) {

            $data['status'] = $type;
            $this->warehouse_model->_table_name = 'tbl_warehouse';
            $this->warehouse_model->_primary_key = 'warehouse_id';
            $this->warehouse_model->save($data, $id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_change_warehouse_status'),
                'value1' => $type,
            );
            $this->warehouse_model->_table_name = 'tbl_activities';
            $this->warehouse_model->_primary_key = 'activities_id';
            $this->warehouse_model->save($activity);

            // messages for user
            $type = "success";
            $message = lang('warehouse_status_changed');
        } else {
            $type = "error";
            $message = lang('there_in_no_value');
        }
        set_message($type, $message);
        redirect('admin/warehouse/manage');
    }

    public function make_default($id, $type = null)
    {
        $mm = get_result('tbl_saved_items');
        $this->items_model->_table_name = 'tbl_warehouses_products';
        $this->items_model->_primary_key = 'id';

        foreach ($mm as $key) {
            $docheck = get_row('tbl_warehouses_products', array('product_id' => $key->saved_items_id,));
            if (empty($docheck)) {
                $params['warehouse_id'] = $id;
                $params['product_id'] = $key->saved_items_id;
                $params['quantity'] = $key->quantity;
                $this->items_model->save($params);
            } elseif ($docheck->warehouse_id == 0) {
                $params['warehouse_id'] = $id;
                $this->items_model->save($params, $docheck->id);
            }
        }

        // assign warehouse_id to all staff if not exist
        $all_staff = get_staff_details(null, null, array('tbl_account_details.warehouse_id' => null));
        $this->items_model->_table_name = 'tbl_account_details';
        $this->items_model->_primary_key = 'account_details_id';
        foreach ($all_staff as $key) {
            $wdata['warehouse_id'] = $id;
            $this->items_model->save($wdata, $key->account_details_id);
        }

        if (!empty($id)) {
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_change_warehouse_status'),
                'value1' => $type,
            );
            $this->warehouse_model->_table_name = 'tbl_activities';
            $this->warehouse_model->_primary_key = 'activities_id';
            $this->warehouse_model->save($activity);

            // messages for user
            $type = "success";
            $message = lang('warehouse_status_changed');
        } else {
            $type = "error";
            $message = lang('there_in_no_value');
        }
        set_message($type, $message);
        //        echo json_encode(array("status" => $type, 'message' => $message));
        //        exit();

        redirect('admin/warehouse/manage');
    }
}