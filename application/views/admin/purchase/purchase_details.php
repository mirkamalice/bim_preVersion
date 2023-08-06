<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('152', 'edited');
$deleted = can_action('152', 'deleted');
$paid_amount = $this->purchase_model->calculate_to('paid_amount', $sales_info->purchase_id);
$payment_status = $this->purchase_model->get_payment_status($sales_info->purchase_id);
$currency = $this->purchase_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
?>

<div class="row mb">
    
    <div class="col-sm-10">
        <?php $can_edit = $this->purchase_model->can_action('tbl_purchases', 'edit', array('purchase_id' => $sales_info->purchase_id));
        if (!empty($can_edit) && !empty($edited)) { ?>
            <?php if ($this->purchase_model->get_purchase_cost($sales_info->purchase_id) > 0) {
            ?>
                <?php if ($sales_info->status == 'cancelled') {
                    $disable = 'disabled';
                    $p_url = '';
                } else {
                    $disable = false;
                    $p_url = base_url() . 'admin/purchase/payment/' . $sales_info->purchase_id;
                } ?>
                <a class="btn btn-xs btn-danger <?= $disable ?>" data-toggle="tooltip" data-placement="top" href="<?= $p_url ?>" title="<?= lang('add_payment') ?>"><i class="fa fa-credit-card"></i>
                    <?= lang('pay') . ' ' . lang('purchase') ?>
                </a>
            <?php
            }
            ?>
        <?php
        }
        ?>
        <?php
        if (!empty($can_edit) && !empty($edited)) { ?>
            <span data-toggle="tooltip" data-placement="top" title="<?= lang('clone') . ' ' . lang('purchase') ?>">
                <a data-toggle="modal" data-target="#myModal" title="<?= lang('clone') . ' ' . lang('purchase') ?>" href="<?= base_url() ?>admin/purchase/clone_purchase/<?= $sales_info->purchase_id ?>" class="btn btn-xs btn-purple">
                    <i class="fa fa-copy"></i> <?= lang('clone') ?></a>
            </span>
        <?php
        }
        ?>
        <?php if (!empty($sales_info->purchase_id)) { ?>
            <a class="btn btn-xs btn-success" href="<?= base_url() ?>admin/purchase/payment/<?= $sales_info->purchase_id ?> "> <?= lang('pay') ?> </a>
        <?php  } ?>

        <div class="btn-group">
            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
                    <?= lang('more_actions') ?>
                    <span class="caret"></span></button>
                <ul class="dropdown-menu animated zoomIn">
                    <?php if ($this->purchase_model->get_purchase_cost($sales_info->purchase_id) > 0) { ?>
                        <li>
                            <a href="<?= base_url() ?>admin/purchase/send_purchase_email/<?= $sales_info->purchase_id ?>" title="<?= lang('send') . ' ' . lang('purchase') . ' ' . lang('email') ?>"><?= lang('send') . ' ' . lang('purchase') . ' ' . lang('email') ?></a>
                        </li>
                        <?php if ($sales_info->emailed != 'Yes') { ?>
                            <li>
                                <a href="<?= base_url() ?>admin/purchase/change_status/mark_as_sent/<?= $sales_info->purchase_id ?>" title="<?= lang('mark_as_sent') ?>"><?= lang('mark_as_sent') ?></a>
                            </li>
                        <?php } ?>
                        <?php if ($paid_amount <= 0) {
                        ?>
                            <?php if ($sales_info->status != 'Cancelled') { ?>
                                <li>
                                    <a href="<?= base_url() ?>admin/purchase/change_status/mark_as_cancelled/<?= $sales_info->purchase_id ?>" title="<?= lang('mark_as_cancelled') ?>"><?= lang('mark_as_cancelled') ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($sales_info->status == 'Cancelled') { ?>
                                <li>
                                    <a href="<?= base_url() ?>admin/purchase/change_status/unmark_as_cancelled/<?= $sales_info->purchase_id ?>" title="<?= lang('unmark_as_cancelled') ?>"><?= lang('unmark_as_cancelled') ?></a>
                                </li>
                        <?php }
                        }
                        ?>
                    <?php } ?>

                    <?php
                    if (!empty($can_edit) && !empty($edited)) { ?>
                        <li class="divider"></li>
                        <li>
                            <a href="<?= base_url() ?>admin/purchase/new_purchase/<?= $sales_info->purchase_id ?>"><?= lang('edit') . ' ' . lang('purchase') ?></a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
    </div>
    <div class="col-sm-2 pull-right">
        <a href="<?= base_url() ?>admin/purchase/send_purchase_email/<?= $sales_info->purchase_id ?>" data-toggle="tooltip" data-placement="top" title="<?= lang('send_email') ?>" class="btn btn-xs btn-primary pull-right">
            <i class="fa fa-envelope-o"></i>
        </a>
        <a onclick="print_sales_details('sales_details')" href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print" class="mr-sm btn btn-xs btn-danger pull-right">
            <i class="fa fa-print"></i>
        </a>
        <a href="<?= base_url() ?>admin/purchase/pdf_purchase/<?= $sales_info->purchase_id ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF" class="btn btn-xs btn-success pull-right mr-sm">
            <i class="fa fa-file-pdf-o"></i>
        </a>
        <?php if (!empty($can_edit) && !empty($edited)) { ?>
            <a href="<?= base_url() ?>admin/purchase/new_purchase/<?= $sales_info->purchase_id ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= lang('edit') ?>" class="btn btn-xs btn-primary pull-right mr-sm">
                <i class="fa fa-pencil-square-o"></i>
            </a>
        <?php } ?>
    </div>
</div>


<?php
$this->view('admin/common/sales_details', $sales_info);
?>

<?php $all_payment_info = $this->db->where('purchase_id', $sales_info->purchase_id)->get('tbl_purchase_payments')->result();
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
                        $payment_methods = $this->purchase_model->check_by(array('payment_methods_id' => $v_payments_info->payment_method), 'tbl_payment_methods');
                    ?>
                        <tr>
                            <td>
                                <a href="<?= base_url() ?>admin/purchase/payments_details/<?= $v_payments_info->payments_id ?>"> <?= $v_payments_info->trans_id; ?></a>
                            </td>
                            <td>
                                <a href="<?= base_url() ?>admin/purchase/payments_details/<?= $v_payments_info->payments_id ?>"><?= display_datetime($v_payments_info->payment_date); ?></a>
                            </td>
                            <td><?= display_money($v_payments_info->amount, $currency->symbol) ?></td>
                            <td><?= !empty($payment_methods->method_name) ? $payment_methods->method_name : '-'; ?></td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                        <?= btn_edit('admin/purchase/all_payments/' . $v_payments_info->payments_id) ?>
                                    <?php }
                                    if (!empty($can_delete) && !empty($deleted)) {
                                    ?>
                                        <?= btn_delete('admin/purchase/delete_payment/' . $v_payments_info->payments_id) ?>
                                    <?php } ?>
                                    <a data-toggle="tooltip" data-placement="top" href="<?= base_url() ?>admin/purchase/send_payment/<?= $v_payments_info->payments_id . '/' . $v_payments_info->amount ?>" title="<?= lang('send_email') ?>" class="btn btn-xs btn-success">
                                        <i class="fa fa-envelope"></i> </a>
                                    <a data-toggle="tooltip" data-placement="top" href="<?= base_url() ?>admin/purchase/payments_pdf/<?= $v_payments_info->payments_id ?>" title="<?= lang('pdf') ?>" class="btn btn-xs btn-warning">
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