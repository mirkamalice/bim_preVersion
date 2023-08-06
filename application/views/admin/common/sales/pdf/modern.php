<?php
if (is_file(config_item('invoice_logo'))) {
    $img = base_url() . config_item('invoice_logo');
} else {
    $img = base_url() . 'uploads/default_logo.png';
}
$client_lang = 'english';
?>

<style type="text/css">
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    footer {
        color: #777777;
        width: 100%;
        height: 30px;
        position: absolute;
        bottom: 0;
        border-top: 1px solid #aaaaaa;
        padding: 8px 0;
        text-align: center;
    }

    .custom-bg {
        background: #0168fa !important;
        color: #FFFFff !important;
    }

    table {
        width: 100%;
        border-spacing: 0;

    }

    .m0 {
        margin: 0 !important;
    }

    .mb0 {
        margin-bottom: 0 !important;
    }

    .mr {
        margin-right: 10px !important;
    }

    hr {
        border-bottom: 1px solid #e4eaec !important;
    }

    .p-md {
        padding: 12px !important;
    }

    .items th {
        padding: 5px
    }

    table.items td {
        border-bottom: 1px solid #e4eaec !important;
    }


    .title {
        margin-bottom: 0;
    }

    .d-title {
        font-size: 50px;
        line-height: 50px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .invoice-preview-inner {
        font-size: 14px;
    }

    .text-right {
        text-align: right;
    }

    .p-50 {
        padding: 15px;
    }

    .mt-5 {
        margin-top: 10px;
    }


    .my-15 {
        margin: 15px 0;
    }

    .ship_to {
        text-align: right;
    }

    .font-700,
    strong {
        font-weight: 700;
        /* color: #33475b; */
    }

    .w-3 {
        width: 12.500001%;
    }

    .w-4 {
        width: 16.666668%;
    }

    .w-5 {
        width: 20.833335%;
    }

    .d-table-tr .d-table-th {
        float: left;
    }

    .font-11 {
        font-size: 11px;
        color: #f05050 !important;
    }

    small {
        font-size: 80%;
    }

    .d-table-td,
    .d-table-th {
        padding: 10px 0;
    }

    .d-table-th {
        font-weight: 700;
    }

    pre {
        white-space: pre-wrap;
        white-space: -moz-pre-wrap;
        white-space: -pre-wrap;
        white-space: -o-pre-wrap;
        word-wrap: break-word;
        margin: 0;
        padding: 0;
        border: none;
        background-color: transparent;

        color: #000;
        font-size: 13px;
        word-break: break-all;
        border-radius: 4px;
        /* line-height: initial; */
    }

    table.items td.tax {
        line-height: 2;
        padding-top: 15px;
    }

    .pt-2 {
        padding-top: 5px;
    }

    .break-25 {
        height: 25px;
    }

    .d-table-summary tbody td {
        text-align: right;
        padding: 9px 0;
    }


    table.items tbody tr:last-child td {
        border: none;
    }

    .mr-100 {
        margin-right: 100px;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .font-700,
    strong {
        font-weight: 700;
    }

    .fancy-title {
        font-size: 2em;
        line-height: 1;
    }

    .float-right {
        float: right;
    }

    .break-50 {
        height: 50px;
    }

    .mt-5 {
        margin-top: 10px;
    }

    .mb-5 {
        margin-bottom: 10px;
    }

    .m-0 {
        margin: 0;
    }

    .thank {
        font-size: 45px;
        line-height: 1.2em;
        /* text-align: right; */
        font-style: italic;
        padding-right: 25px;
    }

    .mt-0 {
        margin-top: 0 !important;
    }

    .fancy-title-2 {
        margin-top: 0;
        font-size: 40px;
        line-height: 1.2em;
        font-weight: bold;
        padding: 25px;
        margin-right: 25px;
    }

    small {
        font-size: 80%;
    }

    .sub-title {
        margin: 5px 0 3px 0;
        display: block;
    }

    .pt-5 {
        padding-top: 5px;
    }

    .pb {
        padding-bottom: 10px !important;
    }

    .pl {
        padding-left: 10px !important;
    }

    .mb-5 {
        margin-bottom: 10px;
    }
</style>


<div class="invoice-preview-inner clearfix panel panel-custom">
    <div class="preview-main client-preview clearfix">
        <div class="d-inner p-50">
            <div class="d-header clearfix">
                <div class="d-header-inner clearfix">
                    <table class="clearfix">
                        <tr>
                            <td style="width: 50%;float: left">
                                <h1 class="fancy-title text-uppercase  mb-5 pt-5 custom-bg pb pl"
                                ><?= $title ?></h1>
                            </td>
                            <td style="width: 50%;vertical-align: top;float: right;text-align:right">
                                <div class="d-header-brand " style="text-align: center;">
                                    <img style="width: 200px;height: 80px;float: right;text-align:right"
                                         src=<?= $img ?> alt="<?= $title ?>">
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <div class="break-50"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width : 52%">
                                <strong class="text-uppercase" style="color: #003580;"><?= lang('our_info') ?></strong>
                                <p class="title m-0"><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></p>
                                <p class="m-0">
                                    <?= config_item('company_email') ?>
                                    <?= (!empty(config_item('company_phone')) ? ' <br>' . config_item('company_phone') : '') ?>

                                    <?= (!empty(config_item('company_address')) ? ' <br>' . config_item('company_address') : '') ?>
                                    <?= (!empty(config_item('company_city')) ? ' <br>' . config_item('company_city') : '') ?>

                                    <?= (!empty(config_item('company_zip_code')) ? ' <br>' . config_item('company_zip_code') : '') ?>

                                    <?= (!empty(config_item('company_country')) ? ' <br>' . config_item('company_country') : '') ?>
                                    <?= (!empty(config_item('vat_number')) ? ' <br> <br>' . config_item('vat_number') : '') ?>

                                </p>
                            </td>
                            <td style="vertical-align: top;">
                                <table class="summary-table mb-5" style="text-align: right;">
                                    <tbody>
                                    <?php $ref = explode(':', $sales_info->ref_no);
                                    ?>
                                    <tr>
                                        <td class="text-uppercase font-700" style="color: #003580;"><?= $ref[0] ?>:</td>
                                        <td><?= $ref[1] ?></td>
                                    </tr>
                                    <?php
                                    if (!empty($sales_info->start_date)) {
                                        $start_date = explode(':', $sales_info->start_date);
                                        ?>
                                        <tr>
                                            <td class="text-uppercase font-700"
                                                style="color: #003580;"><?php echo $start_date[0]; ?>:
                                            </td>
                                            <td><?php echo $start_date[1]; ?></td>
                                        </tr>

                                        <?php
                                    }
                                    if (!empty($sales_info->end_date)) {
                                        $end_date = explode(':', $sales_info->end_date);
                                        ?>
                                        <tr>
                                            <td class="text-uppercase font-700"
                                                style="color: #003580;"><?php echo $end_date[0]; ?>:
                                            </td>
                                            <td><?php echo $end_date[1]; ?></td>
                                        </tr>

                                        <?php
                                    }
                                    if (!empty($sales_info->sales_agent)) {
                                        $sales_agent = explode(':', $sales_info->sales_agent);
                                        ?>
                                        <tr>
                                            <td class="text-uppercase font-700"
                                                style="color: #003580;"><?php echo $sales_agent[0]; ?>:
                                            </td>
                                            <td><?php echo $sales_agent[1]; ?></td>
                                        </tr>
                                    <?php } ?>

                                    <?php
                                    if (!empty($sales_info->status)) {
                                        $status = explode(':', $sales_info->status);
                                        ?>
                                        <tr>
                                            <td class="text-uppercase font-700"
                                                style="color: #003580;"><?php echo $status[0]; ?>:
                                            </td>
                                            <td><?php echo $status[1]; ?></td>
                                        </tr>
                                    <?php }
                                        $customFiled = null;
                                        if (!empty($sales_info->custom_field)) {
                                        // split the string as new array
                                        // if the string contains html tags
                                        // or html entities
                                        // or <br> tag
                                        // then split the string
                                        // otherwise return the string as it is
                                        $custom_field = preg_split('/(<.*?>)|(&.*?;)|<br>/', $sales_info->custom_field);
                                        if (is_array($custom_field)) {
                                            foreach ($custom_field as $v_custom_field) {
                                                if (!empty($v_custom_field)) {
                                                    $details_span += 1;
                                                    $custom_field = explode(':', $v_custom_field);
                                                    $customFiled .= '<tr><td class="text-uppercase font-700"
                                                style="color: #003580;">' . $custom_field[0] . ':</td><td>' . $custom_field[1] . '</td></tr>';
                                                }
                                            }
                                        }
                                        ?>
                                    <?php }
                                    echo $customFiled;
                                    ?>

                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="break-25"></div>
                            </td>
                        </tr>
                        <tr>

                            <td style="width:50%;float: left">
                                <div class="bill_to">
                                    <strong style="color: #003580;"><?= lang('customer') ?></strong>
                                    <p style="margin : 0 ; margin-bottom : 10px">

                                        <strong><?= (!empty($sales_info->name) ? $sales_info->name : '') ?></strong>


                                        <?= (!empty($sales_info->address) ? ' <br>' . $sales_info->address : '') ?>
                                        <?= (!empty($sales_info->city) ? ' <br>' . $sales_info->city : '') ?>
                                        , <?= (!empty($sales_info->zipcode) ? ' <br>' . $sales_info->zipcode : '') ?>
                                        <?= (!empty($sales_info->country) ? ' <br>' . $sales_info->country : '') ?>
                                        <br><abbr title="Phone"><?= lang('phone') ?></abbr>
                                        : <?= (!empty($sales_info->phone) ? $sales_info->phone : '') ?>
                                        <?php if (!empty($sales_info->vat)) { ?>
                                            <br><?= lang('vat_number') ?>: <?= $sales_info->vat ?>
                                        <?php } ?>

                                    </p>
                                </div>
                            </td>
                            <td style="width:50%;float: right;text-align: right">
                                <?= (!empty($qrcode) ? $qrcode : '') ?>
                            </td>


                        </tr>
                        <tr>
                            <td>
                                <div class="break-25"></div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="d-body clearfix">
                <table class="items">
                    <thead class="p-md custom-bg">
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
                            <tr class="sortable item" style="border-top: 1px solid #e4eaec;"
                                data-item-id="<?= $v_item->$itemId ?>">
                                <td class="item_no dragger pl-lg"><?= $key + 1 ?></td>
                                <td><strong class="block"><?= $item_name ?></strong>
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
                <table class="clearfix" style="margin-top : 10px">
                    <tbody>
                    <tr>
                        <td style="width: 60%; vertical-align: top;float:left">
                            <?= ($sales_info->notes) ?>
                        </td>
                        <td style="width: 40%; vertical-align: top;float:right;text-align:right">
                            <div class="mr">
                                <p class="m0">
                                    <b><?= lang('sub_total') ?>
                                        : </b> <?= $sales_info->sub_total ? display_money($sales_info->sub_total) : '0.00' ?>
                                </p>
                                <?php if ($sales_info->discount > 0) : ?>

                                    <p class="m0"><?= lang('discount') ?>
                                        <small>(<?php echo $sales_info->discount_percent; ?>
                                            %)</small>
                                        : <?= $sales_info->discount ? display_money($sales_info->discount) : '0.00' ?>
                                    </p>
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
                                            <p class="mb0"><?= $tax[0] . ' <small>(' . $tax[1] . ' %)</small>' ?>
                                                : <?= display_money($total_tax[$t_key]); ?></p>
                                        <?php }
                                    }
                                } ?>
                                <?php if ($tax_total > 0) : ?>
                                    <hr style="border-bottom:1px solid #e4eaec;margin:0 !important" class="m0">
                                    <p class="m0"><?= lang('total') . ' ' . lang('tax') ?>
                                        : <?= display_money($tax_total); ?></p>
                                <?php endif ?>

                                <?php if ($sales_info->adjustment > 0) : ?>
                                    <p class="m0"><?= lang('adjustment') ?>
                                        : <?= display_money($sales_info->adjustment); ?></p>
                                <?php endif;
                                $currency = get_sales_currency($sales_info);
                                ?>
                                <hr class="m0" style="border-bottom:1px solid #e4eaec;margin:0 !important">

                                <h4 class="m0 mt"><?= lang('Total') ?> :
                                    <?= display_money($sales_info->total, $currency->symbol) ?></h4>
                                <?php
                                if (!empty($paid_amount) && $paid_amount > 0) {
                                    $total = lang('due');
                                    if ($paid_amount > 0) {
                                        $text = 'font-11';
                                        ?>
                                        <h4 class="m0 mt"><?= lang('paid') ?> :
                                            <?= display_money($paid_amount, $currency->symbol); ?></h4>
                                    <?php } else {
                                        $text = '';
                                    } ?>
                                <?php } ?>
                                <?php
                                if (!empty($paid_amount) && $paid_amount > 0) { ?>
                                    <h4 class="m0 mt <?= $text ?>"><?= $total ?> :
                                        <?= display_money(($invoice_due), $currency->symbol); ?></h4>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>

                    </tbody>

                </table>


                <?php
                if (!empty($invoice_due) && $invoice_due > 0) {
                    $due_amount = $invoice_due;
                } else {
                    $due_amount = $sales_info->total;
                }
                if (config_item('amount_to_words') == 'Yes' && !empty($due_amount) && $due_amount > 0) { ?>
                    <div class="clearfix " style="float: right">
                        <p class=""><strong class="h3"><?= lang('num_word') ?>
                                : </strong> <?= number_to_word($currency->code, $due_amount); ?></p>
                    </div>
                <?php } ?>


            </div>
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
                                    <td width="30%" class="unit"><h3><?= $v_item->item_name ?></h3></td>
                                    <?php
                                    $invoice_view = config_item('invoice_view');
                                    if (!empty($invoice_view) && $invoice_view == '2') {
                                        ?>
                                        <td width="8%" class="unit"><?= $v_item->hsn_code ?></td>
                                    <?php } ?>
                                    <td width="8%" class="unit"
                                        style="text-align: right"><?= $v_item->quantity . '   ' . $v_item->unit ?></td>
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
                                    <td class="unit"
                                        style="text-align: right"><?= display_money($v_item->total_cost) ?></td>
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
                <div style="margin-top:20px">
                    <div style="width:100%">
                        <div style="width:50%;float:left"><h4><?= lang('payment') . ' ' . lang('details') ?></h4></div>
                        <div style="clear:both;"></div>
                    </div>

                    <table style="width:100%;margin-bottom:35px;table-layout:fixed;" cellpadding="0"
                           cellspacing="0" border="0">
                        <thead>
                        <tr class="payment_header">
                            <td style="padding:5px 10px 5px 10px;word-wrap: break-word;">
                                <?= lang('transaction_id') ?>
                            </td>
                            <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                                align="right">
                                <?= lang('payment_date') ?>
                            </td>
                            <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                                align="right">
                                <?= lang('amount') ?>
                            </td>
                            <td style="padding:5px 10px 5px 5px;word-wrap: break-word;"
                                align="right">
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
                                <td style="padding: 10px 0px 10px 10px;"
                                    valign="top"><?= $v_payments_info->trans_id; ?>
                                </td>
                                <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;"
                                    valign="top"><?= strftime(config_item('date_format'), strtotime($v_payments_info->payment_date)); ?>
                                </td>
                                <td style="padding: 10px 10px 5px 10px;text-align:right;word-wrap: break-word;"
                                    valign="top"><?= display_money($v_payments_info->amount, $currency->symbol) ?>
                                </td>
                                <td style="text-align:right;padding: 10px 10px 10px 5px;word-wrap: break-word;"
                                    valign="top">
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
<footer>
    <?= (!empty($footer) ? $footer : '') ?>
</footer>