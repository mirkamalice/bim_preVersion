<?php 
$all_invoice_info = $this->db->where(array('status !=' => 'draft', 'project_id' => $project_details->project_id))->get('tbl_invoices')->result();

?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('invoice') ?></h3>
    </div>
    <div class="panel-body">

        <div class="table-responsive">
            <table id="table-milestones" class="table table-striped ">
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
                    foreach ($all_invoice_info as $v_invoices) {
                        $payment_status = $this->invoice_model->get_payment_status($v_invoices->invoices_id);
                        if ($payment_status == lang('fully_paid')) {
                            $label = "success";
                        } elseif ($payment_status == lang('draft')) {
                            $label = "default";
                            $text = lang('status_as_draft');
                        } elseif ($payment_status == lang('cancelled')) {
                            $label = "danger";
                        } elseif ($payment_status == lang('partially_paid')) {
                            $label = "warning";
                        } elseif ($v_invoices->emailed == 'Yes') {
                            $label = "info";
                            $payment_status = lang('sent');
                        } else {
                            $label = "danger";
                        }

                    ?>
                        <tr>
                            <td><a class="text-info" href="<?= base_url() ?>client/invoice/manage_invoice/invoice_details/<?= $v_invoices->invoices_id ?>"><?= $v_invoices->reference_no ?></a>
                            </td>
                            <td><?= strftime(config_item('date_format'), strtotime($v_invoices->due_date)) ?>
                                <?php
                                if (strtotime($v_invoices->due_date) < strtotime(date('Y-m-d')) && $payment_status != lang('fully_paid')) { ?>
                                    <span class="label label-danger "><?= lang('overdue') ?></span>
                                <?php
                                }
                                ?>
                            </td>


                            <td><?= display_money($this->invoice_model->calculate_to('invoice_cost', $v_invoices->invoices_id), $currency->symbol) ?></td>
                            <td><?= display_money($this->invoice_model->calculate_to('invoice_due', $v_invoices->invoices_id), $currency->symbol) ?></td>
                            <td><span class="label label-<?= $label ?>"><?= $payment_status ?></span>
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