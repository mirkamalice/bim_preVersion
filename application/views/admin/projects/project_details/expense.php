<?php
$all_expense_info = get_result('tbl_transactions', array('project_id' => $project_details->project_id));
$total_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense'))->get('tbl_transactions')->row();
$billable_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense', 'billable' => 'Yes'))->get('tbl_transactions')->row();
$not_billable_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense', 'billable' => 'No'))->get('tbl_transactions')->row();
$edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
$type = $this->uri->segment(6);
$category_id = $this->uri->segment(7);
$expense_category = $this->db->get('tbl_expense_category')->result();
if (!empty($project_details->client_id)) {
    $currency = $this->items_model->client_currency_sambol($project_details->client_id);
} else {
    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
}
?>
<div class="box" style="border: none; " data-collapsed="0">
    <div class="btn-group pull-right btn-with-tooltip-group" data-toggle="tooltip" data-title="<?php echo lang('filter_by'); ?>">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-filter" aria-hidden="true"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-left" style="width:300px;<?php if (!empty($type) && $type == 'category') {
                                                                            echo 'display:block';
                                                                        } ?>">
            <li class="<?php
                        if (empty($type)) {
                            echo 'active';
                        } ?>"><a href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/expense"><?php echo lang('all'); ?></a>
            </li>
            <li class="divider"></li>
            <?php if (count(array($expense_category)) > 0) { ?>
                <?php foreach ($expense_category as $v_category) {
                ?>
                    <li class="<?php if (!empty($category_id)) {
                                    if ($type == 'category') {
                                        if ($category_id == $v_category->expense_category_id) {
                                            echo 'active';
                                        }
                                    }
                                } ?>">
                        <a href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/10/category/<?php echo $v_category->expense_category_id; ?>"><?php echo $v_category->expense_category; ?></a>
                    </li>
                <?php }
                ?>
                <div class="clearfix"></div>
                <li class="divider"></li>
            <?php } ?>
        </ul>
    </div>
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="active"><a href="#manage_expense" data-toggle="tab"><?= lang('expense') ?></a>
            </li>
            <?php if (!empty($edited)) { ?>
                <li class=""><a href="<?= base_url() ?>admin/transactions/create_expense/project_expense/<?= $project_details->project_id ?>"><?= lang('new_expense') ?></a>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->
            <div class="tab-pane active" id="manage_expense">
                <div class="table-responsive">
                    <table id="manage_expense" class="table table-striped ">
                        <thead>
                            <tr>
                                <th class="col-date"><?= lang('name') . '/' . lang('title') ?></th>
                                <th><?= lang('date') ?></th>
                                <th><?= lang('categories') ?></th>
                                <th class="col-currency"><?= lang('amount') ?></th>
                                <th><?= lang('attachment') ?></th>
                                <th class="col-options no-sort"><?= lang('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($type) && $type == 'category') {
                                $cate_expense_info = array();
                                $expense_id = $this->uri->segment(7);
                                if (!empty($all_expense_info)) {
                                    foreach ($all_expense_info as $v_expense) {
                                        if ($v_expense->type == 'Expense' && $v_expense->category_id == $expense_id) {
                                            array_push($cate_expense_info, $v_expense);
                                        }
                                    }
                                }
                                $all_expense_info = $cate_expense_info;
                            }
                            $all_expense_info = array_reverse($all_expense_info);
                            if (!empty($all_expense_info)) :
                                foreach ($all_expense_info as $v_expense) :
                                    if ($v_expense->type == 'Expense') :
                                        $category_info = $this->db->where('expense_category_id', $v_expense->category_id)->get('tbl_expense_category')->row();
                                        if (!empty($category_info)) {
                                            $category = $category_info->expense_category;
                                        } else {
                                            $category = lang('undefined_category');
                                        }

                                        $can_edit = $this->items_model->can_action('tbl_transactions', 'edit', array('transactions_id' => $v_expense->transactions_id));
                                        $can_delete = $this->items_model->can_action('tbl_transactions', 'delete', array('transactions_id' => $v_expense->transactions_id));
                                        $e_edited = can_action('31', 'edited');
                                        $e_deleted = can_action('31', 'deleted');

                                        $account_info = $this->items_model->check_by(array('account_id' => $v_expense->account_id), 'tbl_accounts');
                            ?>
                                        <tr id="table-expense-<?= $v_expense->transactions_id ?>">
                                            <td>
                                                <a href="<?= base_url() ?>admin/transactions/view_expense/<?= $v_expense->transactions_id ?>">
                                                    <?= (!empty($v_expense->name) ? $v_expense->name : '-') ?>
                                                </a>
                                            </td>
                                            <td><?= strftime(config_item('date_format'), strtotime($v_expense->date)); ?>
                                            </td>
                                            <td><?= $category ?></td>
                                            <td><?= display_money($v_expense->amount, $currency->symbol) ?></td>

                                            <td>
                                                <?php
                                                $attachement_info = json_decode($v_expense->attachement);
                                                if (!empty($attachement_info)) { ?>
                                                    <a href="<?= base_url() ?>admin/transactions/download/<?= $v_expense->transactions_id ?>"><?= lang('download') ?></a>
                                                <?php } ?>
                                            </td>

                                            <td class="">
                                                <a class="btn btn-info btn-xs" href="<?= base_url() ?>admin/transactions/view_details/<?= $v_expense->transactions_id ?>/details">
                                                    <span class="fa fa-list-alt"></span>
                                                </a>
                                                <?php if (!empty($can_edit) && !empty($e_edited)) { ?>
                                                    <?= btn_edit('admin/transactions/create_expense/' . $v_expense->transactions_id) ?>
                                                <?php }
                                                if (!empty($can_delete) && !empty($e_deleted)) {
                                                ?>
                                                    <?php echo ajax_anchor(base_url("admin/transactions/delete_expense/" . $v_expense->transactions_id), "<i class='btn btn-danger btn-xs fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table-expense-" . $v_expense->transactions_id)); ?>
                                                <?php } ?>
                                            </td>
                                        </tr>
                            <?php
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Tasks Management-->

        </div>
    </div>
</div>