<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Best_selling extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('bestseller_model');
        $this->load->library('gst');
    }

    public function index()
    {

        $data['title'] = lang('best_selling');
        $data['bestselling'] = $this->bestseller_model->get_bestselling();
        $data['subview'] = $this->load->view('admin/bestselling/best_selling', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load


    }

    public function bestsellinglist()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('datatables');

            $main_column = array('tbl_saved_items.item_name', 'tbl_saved_items.code');


            $this->datatables->select = $main_column;
            $this->datatables->table = 'tbl_items';
            $this->datatables->join_table = array('tbl_saved_items');
            $this->datatables->join_where = array('tbl_saved_items.saved_items_id=tbl_items.saved_items_id');
            $action_array = array('items_id');
            // $main_column = array('tbl_saved_items.item_name', 'tbl_saved_items.code');
            $result = array_merge($main_column, $action_array);
            $this->datatables->column_order = array('tbl_items.quantity' => 'DESC');
            $this->datatables->column_search = $result;
            $this->datatables->selectSum = 'tbl_items.quantity';
            $this->datatables->groupBy = 'tbl_items.saved_items_id';
            $this->datatables->order = array('sum(tbl_items.quantity)' => 'desc');
            $where = array('tbl_items.saved_items_id !=' => 0);
            $fetch_data = make_datatables($where);
            $data = array();
            foreach ($fetch_data as $_key => $v_rule) {
                $sub_array = array();
                $sub_array[] = $v_rule->item_name;
                $sub_array[] = $v_rule->code;
                $sub_array[] = $v_rule->quantity;
                $data[] = $sub_array;
            }

            render_table($data, $where);
        } else {
            redirect('admin/dashboard');
        }
    }
}
