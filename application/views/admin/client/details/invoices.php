<section class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <strong>
                <?= lang('invoices') ?>
            </strong>
            <div class="pull-right">
                <?php
                $in_created = can_action('13', 'created');
                $in_edited = can_action('13', 'edited');
                if (!empty($in_created) || !empty($in_edited)) {
                    ?>
                    <a href="<?= base_url() ?>admin/invoice/createinvoice/create_invoice/c_<?= $client_details->client_id ?>"
                       class="btn btn-purple btn-xs"><?= lang('new_invoice') ?></a>
                <?php } ?>
                <a data-toggle="modal" data-target="#myModal"
                   href="<?= base_url() ?>admin/invoice/zipped/invoice/<?= $client_details->client_id ?>"
                   class="btn btn-success btn-xs"><?= lang('zip_invoice') ?></a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped" id="datatable_action" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?= lang('reference_no') ?></th>
                <th><?= lang('date_issued') ?></th>
                <th><?= lang('due_date') ?> </th>
                <th class="col-currency"><?= lang('amount') ?> </th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total_invoice = 0;
            $client_invoices = get_result('tbl_invoices', array('client_id' => $client_details->client_id));
            if (!empty($client_invoices)) {
                foreach ($client_invoices as $key => $invoice) {
                    $total_invoice += $this->invoice_model->invoice_payable($invoice->invoices_id);
                    ?>
                    <tr>
                        <td><a class="text-info"
                               href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $invoice->invoices_id ?>"><?= $invoice->reference_no ?></a>
                        </td>
                        <td><?= strftime(config_item('date_format'), strtotime($invoice->date_saved)); ?>
                        </td>
                        <td><?= strftime(config_item('date_format'), strtotime($invoice->due_date)); ?>
                        </td>
                        <td>
                            <?= display_money($this->invoice_model->invoice_payable($invoice->invoices_id),client_currency($client_details->client_id)); ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <strong><?= lang('invoice') . ' ' . lang('amount') ?>:</strong> <strong class="label label-success">
            <?php
            echo display_money($total_invoice, client_currency($client_details->client_id));
            ?>
        </strong>
    </div>
</section>