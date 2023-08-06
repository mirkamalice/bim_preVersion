<?php
if (is_file(config_item('invoice_logo'))) {
    $img = base_url() . config_item('invoice_logo');
} else {
    $img = base_url() . 'uploads/default_logo.png';
}
$client_lang = 'english';
?>
<?php if (!empty($sales_info->overdue_days)) { ?>
    <div class="alert bg-danger-light hidden-print">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <i class="fa fa-warning"></i>
        <?= $sales_info->overdue_days ?>
    </div>
<?php
} ?>

<style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap');

    .row {
        margin-left: -15px;
        margin-right: -15px;
    }

    .row:before {
        content: " ";
        display: table;
    }

    .col-xs-1,
    .col-xs-2,
    .col-xs-3,
    .col-xs-4,
    .col-xs-5,
    .col-xs-6,
    .col-xs-7,
    .col-xs-8,
    .col-xs-9,
    .col-xs-10,
    .col-xs-11,
    .col-xs-12 {
        float: left;
        padding-left: 15px;
        padding-right: 15px;
        position: relative;
    }

    .col-xs-1 {
        width: 8.33333333%;
    }

    .col-xs-2 {
        width: 16.66666667%;
    }

    .col-xs-3 {
        width: 25%;
    }

    .col-xs-4 {
        width: 33.33333333%;
    }

    .col-xs-5 {
        width: 41.66666667%;
    }

    .col-xs-6 {
        width: 50%;
    }

    .col-xs-7 {
        width: 58.33333333%;
    }

    .col-xs-8 {
        width: 66.66666667%;
    }

    .col-xs-9 {
        width: 75%;
    }

    .col-xs-10 {
        width: 83.33333333%;
    }

    .col-xs-11 {
        width: 91.66666667%;
    }

    .col-xs-12 {
        width: 100%;
    }

    .f-right {
        float: right;
    }

    .m-0 {
        margin: 0;
    }

    .t-right {
        text-align: right;
    }

    .t-center {
        text-align: center;
    }

    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    table {
        width: 100%;
        border-spacing: 0;

    }

    .d-table-tr .d-table-th {
        float: left;
    }

    .d-table-td,
    .d-table-th {
        padding: 10px 0;
    }

    .d-table-th {
        font-weight: 700;
    }

    table.items td.tax {
        line-height: 2;
        padding-top: 15px;
    }

    .d-table-summary tbody td {
        text-align: right;
        padding: 9px 0;
    }

    .font-size-16 {
        font-size: 16px !important;
    }

    .mb-5 {
        margin-bottom: 10px;
    }

    .mb-25 {
        margin-bottom: 25px;
    }

    .font-size-15 {
        font-size: 15px !important;
    }

    .font-size-14 {
        font-size: 14px !important;
    }

    /* table.items tbody tr:nth-child(odd) td {
        border-color: red;
        border-width: 0.5px;
    } */
    .mt-0 {
        margin-top: 0;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    .t-border {
        border: 1px solid #e9e9ef !important;
    }

    .text-end {
        text-align: right !important;
    }

    .align-middle {
        vertical-align: middle !important;
    }

    /* .n-table td, .n-table th {
        white-space: nowrap;
    } */
    .n-table th {
        font-weight: 700;
    }

    .n-table th,
    .n-table td {

        padding: 8px 8px;
        color: #495057;
        border-bottom: 1px solid #e9e9ef;

    }

    .n-table tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
        ;
    }

    .font-size-13 {
        font-size: 13px !important;
    }

    table.n-table thead {
        background-color: #01A3FF;
    }

    .n-table thead th {
        border-bottom-width: 1px;
        text-transform: uppercase;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        letter-spacing: 0.5px;
        border-color: #f3f3f3;
        color: #fff;
    }

    .text-muted {
        color: #74788d !important;
    }

    .border-0 {
        border: 0 !important;
    }

    .b-table th,
    .b-table td {
        padding: 8px 8px;
        color: #495057;
        border-bottom: 1px solid #e9e9ef;
        font-size: 13px;
    }

    .label {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        color: #fff;
    }

    .label-warning {
        background-color: #ff902b;
    }

    .text-danger {
        color: #f05050 !important;
    }
</style>


<div class="invoice-preview-inner clearfix panel panel-custom" style="font-family: 'Poppins', sans-serif;">
    <div class="preview-main client-preview clearfix">

        <div class="d-header clearfix" style="padding: 1.5rem 1.875rem 1.25rem; border-bottom:1px solid #f3f3f3;">
            <table class="clearfix">
                <tr>
                    <td style="width: 33.33333333%;">
                        <?= $title ?>
                    </td>
                    <td class="t-center" style="width: 33.33333333%;">
                        <?php
                        if (!empty($sales_info->start_date)) {
                            $start_date = explode(':', $sales_info->start_date);
                        ?>
                            <strong><?= $start_date[1] ?></strong>

                        <?php
                        } ?>

                    </td>
                    <td style="width: 33.33333333%;">
                        <span class="f-right">
                            <?php
                            if (!empty($sales_info->status)) {
                                $status = explode(':', $sales_info->status);
                            ?>

                                <strong><?php echo $status[0]; ?></strong> <span><?php echo $status[1]; ?></span></span>
                    <?php
                            } ?>

                    </td>
                </tr>
            </table>
        </div>

        <div class="summary-body clearfix" style="padding:20px;">
            <div class="row clearfix" style="margin-bottom:40px">
                <div class="col-xs-4">
                    <div>
                        <h6 class="m-0" style="margin-bottom: 10px;"><?= lang('our_info') ?>:</h6>
                        <div><strong><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></strong></div>
                        <div><?= (!empty(config_item('company_city')) ?  config_item('company_city') : '') ?></div>
                        <div><?= (!empty(config_item('company_address')) ? config_item('company_address') : '') ?></div>
                        <div><?= (!empty(config_item('company_email')) ? config_item('company_email') : '') ?></div>

                        <div><?= (!empty(config_item('company_phone')) ? config_item('company_phone') : '') ?></div>

                    </div>
                </div>
                <div class="col-xs-4">
                    <div>
                        <h6 class="m-0" style="margin-bottom: 10px;"><?= lang('customer') ?>:</h6>
                        <div>
                            <strong><?= (!empty($sales_info->name) ? $sales_info->name : '') ?></strong>
                            <?= (!empty($sales_info->address) ? ' <br>' . $sales_info->address : '') ?>
                            <?= (!empty($sales_info->city) ? ' ,' . $sales_info->city : '') ?>
                            , <?= (!empty($sales_info->zipcode) ? ',' . $sales_info->zipcode : '') ?>
                            <?= (!empty($sales_info->country) ? ' <br>' . $sales_info->country : '') ?>
                            <?php if (!empty($sales_info->phone)) { ?>
                                <br><abbr title="Phone"><?= lang('phone') ?></abbr>
                                : <?= (!empty($sales_info->phone) ? $sales_info->phone : '') ?>
                            <?php }
                            if (!empty($sales_info->vat)) { ?>
                                <br><?= lang('vat_number') ?>: <?= $sales_info->vat ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-xs-4">
                    <div class="pull-right">
                        <div class="pull-left pr-lg">
                            <strong><?= $sales_info->ref_no ?></strong>
                            <?php if (!empty($sales_info->start_date)) { ?>
                                <br><?= $sales_info->start_date ?>
                            <?php } ?>
                            <?php if (!empty($sales_info->end_date)) { ?>
                                <br><?= $sales_info->end_date ?>
                            <?php }
                            if (!empty($sales_info->sales_agent)) { ?>
                                <br><?= $sales_info->sales_agent; ?>
                            <?php }
                            ?>
                            <br><?= $sales_info->status ?>
                            <br><?= $sales_info->custom_field ?>
                        </div>
                        <?php
                        if (!empty($qrcode)) { ?>
                            <div class="pull-left pr-lg mt-lg">
                                <?= (!empty($qrcode) ? $qrcode : '') ?>
                            </div>
                        <?php }
                        ?>
                    </div>
                </div>
            </div>
            <table class="n-table align-middle clearfix" style="margin-bottom:20px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= lang('items') ?></th>
                        <?php
                        $invoice_view = config_item('invoice_view');
                        if (!empty($invoice_view) && $invoice_view == '2') {
                        ?>
                            <th><?= lang('hsn_code') ?></th>
                        <?php } ?>
                        <?php
                        $qty_heading = lang('qty');
                        if (isset($sales_info) && $sales_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                            $qty_heading = lang('hours');
                        } else if (isset($sales_info) && $sales_info->show_quantity_as == 'qty_hours') {
                            $qty_heading = lang('qty') . '/' . lang('hours');
                        }
                        ?>
                        <th><?php echo $qty_heading; ?></th>
                        <th><?= lang('price') ?></th>
                        <th><?= lang('tax') ?></th>
                        <th><?= lang('total') ?></th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $itemId = (!empty($items_id)) ? $items_id : 'items_id';
                    if (!empty($all_items)) :
                        foreach ($all_items as $key => $v_item) :
                            $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
                            $item_tax_name = json_decode($v_item->item_tax_name);
                    ?>
                            <tr data-item-id="<?= $v_item->$itemId ?>">
                                <td><?= $key + 1 ?></td>
                                <td><?= $item_name ?>
                                    <?= nl2br($v_item->item_desc) ?>
                                </td>
                                <?php
                                $invoice_view = config_item('invoice_view');
                                if (!empty($invoice_view) && $invoice_view == '2') {
                                ?>
                                    <td><?= $v_item->hsn_code ?></td>
                                <?php } ?>
                                <td><?= $v_item->quantity . '   &nbsp' . $v_item->unit ?></td>
                                <td><?= display_money($v_item->unit_cost) ?></td>
                                <td><?php
                                    if (!empty($item_tax_name)) {
                                        foreach ($item_tax_name as $v_tax_name) {
                                            $i_tax_name = explode('|', $v_tax_name);
                                            echo '<small class="pr-sm">' . $i_tax_name[0] . ' (' . $i_tax_name[1] . ' %)' . '</small>' . display_money($v_item->total_cost / 100 * $i_tax_name[1]) . ' <br>';
                                        }
                                    }
                                    ?></td>
                                <td><?= display_money($v_item->total_cost) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8"><?= lang('nothing_to_display') ?></td>
                        </tr>
                    <?php endif ?>



                </tbody>
            </table>
            <div class="clearfix" style="padding: 0 40px;">
                <div class="row">
                    <div class="col-xs-6"></div>
                    <div class="col-xs-6">
                        <table class="b-table">

                            <tbody>
                                <tr>
                                    <td><strong><?= lang('sub_total') ?></strong></td>
                                    <td><?= $sales_info->sub_total ? display_money($sales_info->sub_total) : '0.00' ?></td>
                                </tr>
                                <?php if ($sales_info->discount > 0) : ?>
                                    <tr>
                                        <td><strong><?= lang('discount') ?>
                                                (<?php echo $sales_info->discount_percent; ?>
                                                %)</strong></td>

                                        <td><?= $sales_info->discount ? display_money($sales_info->discount) : '0.00' ?></td>
                                    </tr>
                                <?php endif ?>

                                <?php
                                $tax_info = json_decode($sales_info->total_tax);
                                $tax_total = 0;
                                if (!empty($tax_info)) {
                                    $tax_name = $tax_info->tax_name;
                                    $total_tax = $tax_info->total_tax;
                                    if (!empty($tax_name)) {
                                        foreach ($tax_name as $t_key => $v_tax_info) {
                                            $tax = explode('|', $v_tax_info);
                                            $tax_total += $total_tax[$t_key];
                                ?>

                                            <tr>
                                                <td><strong><?= $tax[0] . ' (' . $tax[1] . ' %)' ?></strong></td>
                                                <td><?= display_money($total_tax[$t_key]); ?></td>


                                            </tr>
                                <?php }
                                    }
                                } ?>


                                <?php if ($tax_total > 0) : ?>
                                    <tr>
                                        <td><strong><?= lang('total') . ' ' . lang('tax') ?></strong></td>
                                        <td><?= display_money($tax_total); ?></td>

                                    </tr>
                                <?php endif ?>
                                <?php if ($sales_info->adjustment > 0) : ?>
                                    <tr>
                                        <td><strong><?= lang('adjustment') ?></strong></td>
                                        <td><?= display_money($sales_info->adjustment); ?></td>
                                    </tr>
                                <?php endif;
                                $currency = get_sales_currency($sales_info);
                                ?>
                                <tr>

                                    <td><strong><?= lang('total') ?></strong></td>
                                    <td><strong><?= display_money($sales_info->total, $currency->symbol) ?></strong></td>

                                </tr>
                                <?php
                                if (!empty($paid_amount) && $paid_amount > 0) {
                                    $total = lang('total_due');
                                    if ($paid_amount > 0) {
                                        $text = 'text-danger';
                                ?>
                                        <tr>
                                            <td><strong><?= lang('paid_amount') ?></strong></td>
                                            <td><?= display_money($paid_amount, $currency->symbol); ?></td>


                                        </tr>
                                    <?php } else {
                                        $text = '';
                                    } ?>
                                <?php } ?>

                                <?php
                                if (!empty($paid_amount) && $paid_amount > 0) { ?>

                                    <tr>
                                        <td class=<?= $text ?>><strong><?= $total ?></strong></td>
                                        <td class=<?= $text ?>><?= display_money(($invoice_due), $currency->symbol); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>



                        </table>
                    </div>
                </div>
            </div>
            <?php
            if (!empty($invoice_due) && $invoice_due > 0) {
                $due_amount = $invoice_due;
            } else {
                $due_amount = $sales_info->total;
            }
            if (config_item('amount_to_words') == 'Yes' && !empty($due_amount) && $due_amount > 0) { ?>
                <div class="clearfix">
                    <p class=""><strong class="h3"><?= lang('num_word') ?>
                            : </strong> <?= number_to_word($currency->code, $due_amount); ?></p>
                </div>
            <?php } ?>



            <?php
            $colspan = 2;
            $invoice_view = config_item('invoice_view');
            if (!empty($invoice_view) && $invoice_view > 0) {
                $colspan = 2;
            ?>
                <style type="text/css">
                    .panel {
                        margin-bottom: 10px;
                        background-color: #ffffff;
                        border: 1px solid transparent;
                        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
                        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
                    }

                    .panel-custom .panel-heading {
                        border-bottom: 2px solid #2b957a;
                    }

                    .panel .panel-heading {
                        border-bottom: 0;
                        font-size: 12px;
                    }

                    .panel-heading {
                        padding: 5px 10px;
                        border-bottom: 1px solid transparent;
                        border-top-right-radius: 3px;
                        border-top-left-radius: 3px;
                    }

                    .panel-title {
                        margin-top: 0;
                        margin-bottom: 0;
                        font-size: 14px;
                    }

                    small {
                        font-size: 10px;
                    }
                </style>
                <div class="panel panel-custom" style="margin-top: 20px">
                    <div class="panel-heading" style="border:1px solid #dde6e9;border-bottom: 2px solid #57B223;">
                        <div class="panel-title"><?= lang('tax_summary') ?></div>
                    </div>
                    <table class="items">
                        <thead class="p-md">
                            <tr>
                                <th><?= lang('description') ?></th>
                                <?php

                                $invoice_view = config_item('invoice_view');
                                if (!empty($invoice_view) && $invoice_view == '2') {
                                    $colspan = 3;
                                ?>
                                    <th><?= lang('hsn_code') ?></th>
                                <?php } ?>
                                <th style="text-align: right"><?= lang('qty') ?></th>
                                <th style="text-align: right"><?= lang('tax') ?></th>
                                <th class="" style="text-align: right"><?= lang('total_tax') ?></th>
                                <th class="total" style="text-align: right"><?= lang('tax_excl_amt') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_tax = 0;
                            $total_cost = 0;
                            if (!empty($all_items)) :
                                foreach ($all_items as $key => $v_item) :
                                    $item_tax_name = json_decode($v_item->item_tax_name);
                                    $tax_amount = 0;
                            ?>
                                    <tr>
                                        <td width="30%" class="unit">
                                            <h3><?= $v_item->item_name ?></h3>
                                        </td>
                                        <?php
                                        $invoice_view = config_item('invoice_view');
                                        if (!empty($invoice_view) && $invoice_view == '2') {
                                        ?>
                                            <td width="8%" class="unit"><?= $v_item->hsn_code ?></td>
                                        <?php } ?>
                                        <td width="8%" class="unit" style="text-align: right"><?= $v_item->quantity . '   ' . $v_item->unit ?></td>
                                        <td width="20%" class="unit" style="text-align: right"><?php
                                                                                                if (!empty($item_tax_name)) {
                                                                                                    foreach ($item_tax_name as $v_tax_name) {
                                                                                                        $i_tax_name = explode('|', $v_tax_name);
                                                                                                        $tax_amount += $v_item->total_cost / 100 * $i_tax_name[1];
                                                                                                        echo '<small class="pr-sm">' . $i_tax_name[0] . ' (' . $i_tax_name[1] . ' %)' . '</small>' . display_money($v_item->total_cost / 100 * $i_tax_name[1]) . ' <br>';
                                                                                                    }
                                                                                                }
                                                                                                $total_cost += $v_item->total_cost;
                                                                                                $total_tax += $tax_amount;
                                                                                                ?></td>
                                        <td class="unit" style="text-align: right"><?= display_money($tax_amount) ?></td>
                                        <td class="unit" style="text-align: right"><?= display_money($v_item->total_cost) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif ?>

                        </tbody>
                        <tfoot>
                            <tr class="total">
                                <td colspan="<?= $colspan ?>"></td>
                                <td><?= lang('total') ?></td>
                                <td><?= display_money($total_tax) ?></td>
                                <td><?= display_money($total_cost) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php } ?>
            <?php
            if (!empty($all_payment_info)) { ?>
                <div style="margin-top:20px; font-weight : 400">
                    <div style="width:100%">
                        <div style="width:50%;float:left">
                            <h4><?= lang('payment') . ' ' . lang('details') ?></h4>
                        </div>
                        <div style="clear:both;"></div>
                    </div>

                    <table style="width:100%;margin-bottom:35px;table-layout:fixed;" cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr class="payment_header">
                                <td style="padding:5px 10px 5px 10px;word-wrap: break-word;">
                                    <?= lang('transaction_id') ?>
                                </td>
                                <td style="padding:5px 10px 5px 5px;word-wrap: break-word;" align="right">
                                    <?= lang('payment_date') ?>
                                </td>
                                <td style="padding:5px 10px 5px 5px;word-wrap: break-word;" align="right">
                                    <?= lang('amount') ?>
                                </td>
                                <td style="padding:5px 10px 5px 5px;word-wrap: break-word;" align="right">
                                    <?= lang('payment_mode') ?>
                                </td>
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
                                <tr class="cbb">
                                    <td style="padding: 10px 0px 10px 10px;" valign="top"><?= $v_payments_info->trans_id; ?>
                                    </td>
                                    <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;" valign="top"><?= strftime(config_item('date_format'), strtotime($v_payments_info->payment_date)); ?>
                                    </td>
                                    <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;" valign="top"><?= display_money($v_payments_info->amount, $currency->symbol) ?>
                                    </td>
                                    <td style="text-align:right;padding: 10px 10px 10px 5px;word-wrap: break-word;" valign="top">
                                        <?= !empty($v_payments_info->method_name) ? $v_payments_info->method_name : '-'; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>

        </div>


    </div>

</div>