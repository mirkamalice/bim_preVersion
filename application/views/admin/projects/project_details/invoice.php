<?php
$edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('invoice') ?></h3>
    </div>
    <div class="panel-body">

        <div class="table-responsive">
            <table id="table-invoice" class="table table-striped ">
                <thead>
                    <tr>
                        <th><?= lang('invoice') ?></th>
                        <th class="col-date"><?= lang('due_date') ?></th>
                        <th class="col-currency"><?= lang('amount') ?></th>
                        <th class="col-currency"><?= lang('due_amount') ?></th>
                        <th><?= lang('status') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $all_invoice_info = get_result('tbl_invoices', array('project_id' => $project_details->project_id));
                    foreach ($all_invoice_info as $v_invoices) {
                        if ($this->invoice_model->get_payment_status($v_invoices->invoices_id) == lang('fully_paid')) {
                            $invoice_status = lang('fully_paid');
                            $label = "success";
                        } elseif ($v_invoices->emailed == 'Yes') {
                            $invoice_status = lang('sent');
                            $label = "info";
                        } else {
                            $invoice_status = lang('draft');
                            $label = "default";
                        }
                    ?>
                        <tr>
                            <td><a class="text-info" href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= $v_invoices->reference_no ?></a>
                            </td>
                            <td><?= strftime(config_item('date_format'), strtotime($v_invoices->due_date)) ?>
                                <?php
                                $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
                                if (strtotime($v_invoices->due_date) < strtotime(date('Y-m-d')) && $payment_status != lang('fully_paid')) { ?>
                                    <span class="label label-danger "><?= lang('overdue') ?></span>
                                <?php
                                }
                                ?>
                            </td>
                            <td><?= display_money($this->invoice_model->calculate_to('invoice_cost', $v_invoices->invoices_id), $currency->symbol) ?>
                            </td>
                            <td><?= display_money($this->invoice_model->calculate_to('invoice_due', $v_invoices->invoices_id), $currency->symbol) ?>
                            </td>
                            <td><span class="label label-<?= $label ?>"><?= $invoice_status ?></span>
                                <?php if ($v_invoices->recurring == 'Yes') { ?>
                                    <span data-toggle="tooltip" data-placement="top" title="<?= lang('recurring') ?>" class="label label-primary"><i class="fa fa-retweet"></i></span>
                                <?php } ?>

                            </td>

                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>