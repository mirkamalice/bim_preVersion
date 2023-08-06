<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('152', 'edited');
$deleted = can_action('152', 'deleted');
$paid_amount = $this->return_stock_model->calculate_to('paid_amount', $sales_info->return_stock_id);
$payment_status = $this->return_stock_model->get_payment_status($sales_info->return_stock_id);
$currency = $this->return_stock_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
?>

<div class="row mb">
   
    <div class="col-sm-10">
        <?php $can_edit = $this->return_stock_model->can_action('tbl_return_stock', 'edit', array('return_stock_id' => $sales_info->return_stock_id));
        if (!empty($can_edit) && !empty($edited)) { ?>
            <?php if ($this->return_stock_model->get_return_stock_cost($sales_info->return_stock_id) > 0) {
            ?>
                <?php if ($sales_info->status == 'cancelled') {
                    $disable = 'disabled';
                    $p_url = '';
                } else {
                    $disable = false;
                    $p_url = base_url() . 'admin/return_stock/payment/' . $sales_info->return_stock_id;
                } ?>
                <a class="btn btn-xs btn-danger <?= $disable ?>" data-toggle="tooltip" data-placement="top" href="<?= $p_url ?>" title="<?= lang('received') . ' ' . lang('return_stock') ?>"><i class="fa fa-credit-card"></i>
                    <?= lang('received') . ' ' . lang('return_stock') ?>
                </a>
            <?php
            }
            ?>
        <?php
        }
        ?>
        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <span data-toggle="tooltip" data-placement="top" title="<?= lang('clone') . ' ' . lang('return_stock') ?>">
                <a data-toggle="modal" data-target="#myModal" title="<?= lang('clone') . ' ' . lang('return_stock') ?>" href="<?= base_url() ?>admin/return_stock/clone_return_stock/<?= $sales_info->return_stock_id ?>" class="btn btn-xs btn-purple">
                    <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
            </span>
        <?php
        }
        ?>
        
        <?php if (!empty($sales_info->return_stock_id)) { ?>
            <a class="btn btn-xs btn-success" href="<?= base_url() ?>admin/return_stock/payment/<?= $sales_info->return_stock_id ?> "> <?= lang('pay') ?> </a>
        <?php  } ?>

        <div class="btn-group">
            <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                <?= lang('more_actions') ?>
                <span class="caret"></span></button>
            <ul class="dropdown-menu animated zoomIn">
                <?php if ($this->return_stock_model->get_return_stock_cost($sales_info->return_stock_id) > 0) { ?>
                    <li>
                        <a href="<?= base_url() ?>admin/return_stock/send_return_stock_email/<?= $sales_info->return_stock_id ?>" title="<?= lang('send') . ' ' . lang('return_stock') . ' ' . lang('email') ?>"><?= lang('send') . ' ' . lang('return_stock') . ' ' . lang('email') ?></a>
                    </li>
                    <?php if ($sales_info->emailed != 'Yes') { ?>
                        <li>
                            <a href="<?= base_url() ?>admin/return_stock/change_status/mark_as_sent/<?= $sales_info->return_stock_id ?>" title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($sales_info->status == 'cancelled' || $sales_info->status == 'draft') { ?>
                        <li>
                            <a href="<?= base_url() ?>admin/return_stock/change_status/pending/<?= $sales_info->return_stock_id ?>" title="<?= lang('mark_as_pending') ?>"><?= lang('mark_as_pending') ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($sales_info->update_stock != 'Yes') { ?>
                        <li>
                            <a href="<?= base_url() ?>admin/return_stock/change_status/draft/<?= $sales_info->return_stock_id ?>" title="<?= lang('mark_as_draft') ?>"><?= lang('mark_as_draft') ?></a>
                        </li>
                    <?php } ?>
                    <?php if ($paid_amount <= 0) {
                    ?>
                        <?php if ($sales_info->status != 'cancelled') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/return_stock/change_status/cancelled/<?= $sales_info->return_stock_id ?>" title="<?= lang('mark_as_cancelled') ?>"><?= lang('mark_as_cancelled') ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($sales_info->status == 'cancelled') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/return_stock/change_status/unmark_as_cancelled/<?= $sales_info->return_stock_id ?>" title="<?= lang('unmark_as_cancelled') ?>"><?= lang('unmark_as_cancelled') ?></a>
                            </li>
                    <?php }
                    }
                    ?>
                    <?php
                    if ($payment_status != 'fully_paid') { ?>
                        <li>
                            <a href="<?= base_url() ?>admin/return_stock/change_status/accepted/<?= $sales_info->return_stock_id ?>" title="<?= lang('mark_as') . ' ' . lang('accepted') ?>"><?= lang('mark_as') . ' ' . lang('accepted') ?></a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>admin/return_stock/change_status/declined/<?= $sales_info->return_stock_id ?>" title="<?= lang('mark_as') . ' ' . lang('declined') ?>"><?= lang('mark_as') . ' ' . lang('declined') ?></a>
                        </li>
                    <?php } ?>
                <?php } ?>

                <?php
                if (!empty($can_edit) && !empty($edited)) { ?>
                    <li class="divider"></li>
                    <li>
                        <a href="<?= base_url() ?>admin/return_stock/create_returnstock/<?= $sales_info->return_stock_id ?>"><?= lang('edit') . ' ' . lang('return_stock') ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="col-sm-2 pull-right">
        <a href="<?= base_url() ?>admin/return_stock/send_return_stock_email/<?= $sales_info->return_stock_id ?>" data-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>" class="btn btn-xs btn-primary pull-right">
            <i class="fa fa-envelope-o"></i>
        </a>
        <a onclick="print_sales_details('sales_details')" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" class="mr-sm btn btn-xs btn-danger pull-right">
            <i class="fa fa-print"></i>
        </a>
        <a href="<?= base_url() ?>admin/return_stock/pdf_return_stock/<?= $sales_info->return_stock_id ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" class="btn btn-xs btn-success pull-right mr-sm">
            <i class="fa fa-file-pdf-o"></i>
        </a>
        <a href="<?= base_url() ?>admin/return_stock/create_returnstock/<?= $sales_info->return_stock_id ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= lang('edit') ?>" class="btn btn-xs btn-primary pull-right mr-sm">
            <i class="fa fa-pencil-square-o"></i>
        </a>
    </div>
</div>
<?php
$this->view('admin/common/sales_details', $sales_info);
?>

<?php $all_payment_info = $this->db->where('return_stock_id', $sales_info->return_stock_id)->get('tbl_return_stock_payments')->result();
if (!empty($all_payment_info)) { ?>
    <div class="panel panel-custom">
        <div class="panel-heading">
            <div class="panel-title"> <?= lang('payment') . ' ' . lang('details') ?></div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th><?= lang('transaction_id') ?></th>
                        <th><?= lang('payment_date') ?></th>
                        <th><?= lang('amount') ?></th>
                        <th><?= lang('payment_mode') ?></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($all_payment_info as $v_payments_info) {
                        if (is_numeric($v_payments_info->payment_method)) {
                            $v_payments_info->method_name = get_any_field('tbl_payment_methods', array('payment_methods_id' => $v_payments_info->payment_method), 'method_name');
                        } else {
                            $v_payments_info->method_name = $v_payments_info->payment_method;
                        }
                    ?>
                        <tr>
                            <td>
                                <a href="<?= base_url() ?>admin/return_stock/payments_details/<?= $v_payments_info->payments_id ?>">
                                    <?= $v_payments_info->trans_id; ?></a>
                            </td>
                            <td>
                                <a href="<?= base_url() ?>admin/return_stock/payments_details/<?= $v_payments_info->payments_id ?>"><?= display_date($v_payments_info->payment_date); ?></a>
                            </td>
                            <td><?= display_money($v_payments_info->amount, $currency->symbol) ?></td>
                            <td><?= !empty($v_payments_info->method_name) ? $v_payments_info->method_name : '-'; ?></td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                        <?= btn_edit('admin/return_stock/all_payments/' . $v_payments_info->payments_id) ?>
                                    <?php }
                                    if (!empty($can_delete) && !empty($deleted)) {
                                    ?>
                                        <?= btn_delete('admin/return_stock/delete_payment/' . $v_payments_info->payments_id) ?>
                                    <?php } ?>
                                    <a data-toggle="tooltip" data-placement="top" href="<?= base_url() ?>admin/return_stock/send_payment/<?= $v_payments_info->payments_id . '/' . $v_payments_info->amount ?>" title="<?= lang('send_email') ?>" class="btn btn-xs btn-success">
                                        <i class="fa fa-envelope"></i> </a>
                                    <a data-toggle="tooltip" data-placement="top" href="<?= base_url() ?>admin/return_stock/payments_pdf/<?= $v_payments_info->payments_id ?>" title="<?= lang('pdf') ?>" class="btn btn-xs btn-warning">
                                        <i class="fa fa-file-pdf-o"></i></a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>