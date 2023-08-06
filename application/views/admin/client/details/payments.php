<section class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('payments') ?>
            <div class="pull-right">
                <a data-toggle="modal" data-target="#myModal"
                   href="<?= base_url() ?>admin/invoice/zipped/payment/<?= $client_details->client_id ?>"
                   class="btn btn-success btn-xs"><?= lang('zip_payment') ?></a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped " id="datatable_action" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= lang('payment_date') ?></th>
                    <th><?= lang('invoice_date') ?></th>
                    <th><?= lang('invoice') ?></th>
                    <th><?= lang('amount') ?></th>
                    <th><?= lang('payment_method') ?></th>
                    <th class="col-sm-3"><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total_amount = 0;
                $recently_paid = get_order_by('tbl_payments', array('paid_by' => $client_details->client_id), 'created_date');
                if (!empty($recently_paid)) {
                    foreach ($recently_paid as $key => $v_paid) {
                        $invoice_info = $this->db->where(array('invoices_id' => $v_paid->invoices_id))->get('tbl_invoices')->row();
                        if (is_numeric($v_paid->payment_method)) {
                            $payment_methods = get_row('tbl_payment_methods', array('payment_methods_id' => $v_paid->payment_method));
                        } else {
                            $payment_methods = new stdClass();
                            $payment_methods->method_name = $v_paid->payment_method;
                        }
                        
                        if ($v_paid->payment_method == '1') {
                            $label = 'success';
                        } elseif ($v_paid->payment_method == '2') {
                            $label = 'danger';
                        } else {
                            $label = 'dark';
                        }
                        $total_amount += $v_paid->amount;
                        ?>
                        <tr>
                            <td>
                                <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?= $v_paid->payments_id ?>">
                                    <?= strftime(config_item('date_format'), strtotime($v_paid->payment_date)); ?></a>
                            </td>
                            <td><?= strftime(config_item('date_format'), strtotime($invoice_info->date_saved)) ?>
                            </td>
                            <td><a class="text-info"
                                   href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_paid->invoices_id ?>"><?= $invoice_info->reference_no; ?></a>
                            </td>
                            <?php $currency = $this->invoice_model->client_currency_symbol($invoice_info->client_id); ?>
                            <td><?= display_money($v_paid->amount, $currency->symbol) ?></td>
                            <td>
                                <span class="label label-<?= $label ?>"><?= !empty($payment_methods->method_name) ? $payment_methods->method_name : '-'; ?></span>
                            </td>
                            <td>
                                <?= btn_edit('admin/invoice/all_payments/' . $v_paid->payments_id) ?>
                                <?= btn_view('admin/invoice/manage_invoice/payments_details/' . $v_paid->payments_id) ?>
                                <?= btn_delete('admin/invoice/delete/delete_payment/' . $v_paid->payments_id) ?>
                                <a data-toggle="tooltip" data-placement="top"
                                   href="<?= base_url() ?>admin/invoice/send_payment/<?= $v_paid->payments_id . '/' . $v_paid->amount ?>"
                                   title="<?= lang('send_email') ?>" class="btn btn-xs btn-success">
                                    <i class="fa fa-envelope"></i> </a>
                            </td>
                        </tr>
                        
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer">
        <strong><?= lang('paid_amount') ?>:</strong> <strong class="label label-success">
            <?= display_money($total_amount, client_currency($client_details->client_id)); ?>
        </strong>
    </div>
</section>