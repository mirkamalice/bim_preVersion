<?php
/**
 * Description of Project_Model
 *
 * @author NaYeM
 */
class Bestseller_model extends MY_Model{

    public $_table_name;
    public $_order_by;
    public $_primary_key;


function get_bestselling(){
  
    $this->db
    ->select('tbl_saved_items.item_name,tbl_saved_items.code')->select_sum('tbl_items.quantity')
    ->join('tbl_saved_items','tbl_saved_items.saved_items_id=tbl_items.saved_items_id', 'left')
    // ->where('date >=', $start_date)->where('date <=', $end_date)
    ->group_by('tbl_items.saved_items_id')->order_by('sum(tbl_items.quantity)', 'desc')->limit(10);
// if ($warehouse_id) {
//     $this->db->where('sale_items.warehouse_id', $warehouse_id);
// }
$q = $this->db->get('tbl_items');
if ($q->num_rows() > 0) {
    foreach (($q->result()) as $row) {
        $data[] = $row;
    }
    return $data;
    // print('<pre>'.print_r($data,true).'</pre>'); exit;
}
return false;
}


function get_bestselling_byid($id){
    // print('<pre>'.print_r($id,true).'</pre>'); exit;
    $this->db->select_sum('quantity');
    $this->db->from('tbl_items');
    $this->db->where('saved_items_id',$id);
    $query = $this->db->get();
    return $query->row()->quantity;

 }


}