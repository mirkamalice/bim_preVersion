<?php
$account_info = $this->transactions_model->check_by(array('account_id' => $expense_info->account_id), 'tbl_accounts');
$currency = $this->transactions_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

$category = lang('undefined_category');

if ($expense_info->type == 'Income') {
    $category_info = $this->db->where('income_category_id', $expense_info->category_id)->get('tbl_income_category')->row();
    if (!empty($category_info)) {
        $category = $category_info->income_category;
    }
} else {
    $category_info = $this->db->where('expense_category_id', $expense_info->category_id)->get('tbl_expense_category')->row();
    if (!empty($category_info)) {
        $category = $category_info->expense_category;
    }
}
$client_name = $this->db->where('client_id', $expense_info->paid_by)->get('tbl_client')->row();
$active = 1;
$all_task_info = $this->db->where('transactions_id', $expense_info->transactions_id)->order_by('transactions_id', 'DESC')->get('tbl_task')->result();
$notified_reminder = count(array($this->db->where(array('module' => 'expense', 'module_id' => $expense_info->transactions_id, 'notified' => 'No'))->get('tbl_reminders')->result()));
// $can_edit = $this->transactions_model->can_action('tbl_transactions', 'edit', array('transactions_id' => $expense_info->transactions_id));
// $edited = can_action('30', 'edited');
?>

<?php
$this->load->view('admin/common/tabs');
?>
<div class="row mt-lg">
    <div class="col-sm-2">

    </div>
    <div class="col-sm-10">
        <div class="tab-content" style="border: 0;padding:0;">



        </div>
    </div>
</div>