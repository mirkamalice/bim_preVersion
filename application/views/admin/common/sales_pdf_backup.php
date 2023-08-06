<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= lang($title) ?></title>
    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
    <style type="text/css">
        /*@page {*/
        /*    margin: 1in 0in 0in 0in;*/
        /*}*/
        @font-face {
            font-family: "Source Sans Pro", sans-serif;
        }

        .h4 {
            font-size: 14px;
        }

        .h3 {
            font-size: 15px;
        }

        h2 {
            font-size: 19px;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            color: #555555;
            background: #ffffff;
            font-size: 12px;
            font-family: "Source Sans Pro", sans-serif;
            width: 100%;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        header {
            padding: 10px 0;
            margin-bottom: 15px;
            border-bottom: 1px solid #aaaaaa;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        #logo {
        <?php if(!empty($RTL)){?> text-align: right !important;
            padding-right: 55px;
        <?php }?>
        }

        #company {
        <?php if(!empty($RTL)){?> text-align: left;
        <?php }else{?> text-align: right;
        <?php }?>
        }

        #details {
            margin-bottom: 10px;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        #client {
            padding-left: 6px;
            /*border-left: 6px solid #0087C3;*/
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1em;
            font-weight: normal;
            margin: 0;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        #invoice {
        <?php if(!empty($RTL)){?> text-align: left;
        <?php }else{?> text-align: right;
        <?php }?>
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 1.5em;
            line-height: 1em;
            font-weight: normal;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        table {
            width: 100%;
            border-spacing: 0;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            /*margin-bottom: 10px;*/
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        table.items th,
        table.items td {
            padding: 5px;
            /*background: #EEEEEE;*/
            border-bottom: 1px solid #FFFFFF;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }else{?> text-align: left;
        <?php }?>

        }

        table.items th {
            white-space: nowrap;
            font-weight: normal;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        table.items td {
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }else{?> text-align: left;
        <?php }?>
        }

        table.items td h3 {
            color: #57B223;
            font-size: 1em;
            font-weight: normal;
            margin-top: 2px;
            margin-bottom: 2px;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        table.items .no {
            background: #dddddd;
        }

        table.items .desc {
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }else{?> text-align: left;
        <?php }?>
        }

        table.items .unit {
            background: #F3F3F3;
            padding: 5px 10px 5px 5px;
            word-wrap: break-word;
        }

        table.items .qty {
        }

        table.items td.unit,
        table.items td.qty,
        table.items td.total {
            font-size: 1em;
        }

        table.items tbody tr:last-child td {
            border: none;

        }

        table.items tfoot td {
            padding: 5px 10px;
            background: #ffffff;
            border-bottom: none;
            font-size: 14px;
            white-space: nowrap;
            border-top: 1px solid #aaaaaa;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        table.items tfoot tr:first-child td {
            border-top: none;
        }

        table.items tfoot tr:last-child td {
            color: #57B223;
            font-size: 1.4em;
            border-top: 1px solid #57B223;

        }

        table.items tfoot tr td:first-child {
            border: none;
        <?php if(!empty($RTL)){?> text-align: left;
        <?php }else{?> text-align: right;
        <?php }?>
        }

        #thanks {
            font-size: 16px;
            margin-bottom: 10px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #0087C3;

        }

        #notices .notice {
            font-size: 1em;
            color: #777;
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

        tr.total td, tr th.total, tr td.total {
        <?php if(!empty($RTL)){?> text-align: left;
        <?php }else{?> text-align: right;
        <?php }?>
        }

        .bg-items {
            background: #303252 !important;
            color: #ffffff
        }

        .p-md {
            padding: 9px !important;
        }

        .left {
        <?php if(!empty($RTL)){?> float: right;
        <?php }else{?> float: left;
        <?php }?>
        }

        .right {
        <?php if(!empty($RTL)){?> float: left;
            padding-right: 10px;
        <?php }else{?> float: right;
            padding-left: 10px;
        <?php }?>
        }

        .num_word {
            margin-top: 5px;
            margin-bottom: 5px;
        }
    
    </style>
</head>
<body>

<?php
$uri = $this->uri->segment(3);
if ($uri == 'invoice_email') {
    $img = base_url() . config_item('invoice_logo');
} else {
    $img = ROOTPATH . '/' . config_item('invoice_logo');
    $a = file_exists($img);
    if (empty($a)) {
        $img = base_url() . config_item('invoice_logo');
    }
    if (!file_exists($img)) {
        $img = ROOTPATH . '/' . 'uploads/default_logo.png';
    }
}
$client_lang = 'english';
?>
<table class="clearfix">
    <tr>
        <td style="width: 50%;">
            <div id="logo" class="left">
                <img style="width: 170px;height: 80px;float: left !important;" src="<?= $img ?>">
            </div>
        </td>
        <td style="width: 50%;">
            <div class="right" style="">
                <h3 style="margin-bottom: 0;margin-top: 0"><?= $sales_info->ref_no ?></h3>
                <div class="date"><?= $sales_info->start_date ?></div>
                <div class="date"><?= $sales_info->end_date ?></div>
                <?php if (!empty($sales_info->sales_agent)) { ?>
                    <div class="date"><?= $sales_info->sales_agent ?></div>
                <?php }
                ?>
                <div class="date"><?= $sales_info->status ?></div>
                <div class="date"><?= $sales_info->custom_field ?></div>
            </div>
        
        </td>
    </tr>
</table>

<table id="details" class="clearfix">
    <tr>
        <td style="width: 50%;overflow: hidden">
            <h4 class="p-md bg-items ">
                <?= lang('our_info') ?>
            </h4>
        </td>
        <td style="width: 50%">
            <h4 class="p-md bg-items ">
                <?= lang('customer') ?>
            </h4>
        </td>
    </tr>
    <tr style="margin-top: 0px">
        <td style="width: 50%;overflow: hidden">
            <div style="padding-left: 5px">
                <h3 style="margin: 0px"><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></h3>
                <div><?= (config_item('company_address_' . $client_lang) ? config_item('company_address_' . $client_lang) : config_item('company_address')) ?></div>
                <div><?= (config_item('company_city_' . $client_lang) ? config_item('company_city_' . $client_lang) : config_item('company_city')) ?>
                    , <?= config_item('company_zip_code') ?></div>
                <div><?= (config_item('company_country_' . $client_lang) ? config_item('company_country_' . $client_lang) : config_item('company_country')) ?></div>
                <div> <?= config_item('company_phone') ?></div>
                <div><a href="mailto:<?= config_item('company_email') ?>"><?= config_item('company_email') ?></a></div>
                <div><?= config_item('company_vat') ?></div>
            </div>
        </td>
        <td style="width: 50%">
            <div style="padding-left: 5px">
                
                <h3 style="margin: 0px"><?= (!empty($sales_info->name) ? $sales_info->name : '') ?></h3>
                <div class="address"><?= (!empty($sales_info->address) ? $sales_info->address : '') ?></div>
                <div class="address"><?= (!empty($sales_info->city) ? $sales_info->city : '') ?>
                    , <?= (!empty($sales_info->zipcode) ? $sales_info->zipcode : '') ?>
                    ,<?= (!empty($sales_info->country) ? $sales_info->country : '') ?></div>
                <div class="address"> <?= (!empty($sales_info->phone) ? $sales_info->phone : '') ?></div>
                <div class="email"><a
                            href="mailto:<?= (!empty($sales_info->email) ? $sales_info->email : '') ?>"><?= (!empty($sales_info->email) ? $sales_info->email : '') ?></a>
                </div>
                <?php if (!empty($sales_info->vat)) { ?>
                    <div class="email"><?= lang('vat_number') ?>: <?= $sales_info->vat ?></div>
                <?php } ?>
            </div>
        </td>
    </tr>
</table>

<table class="items">
    <thead class="p-md bg-items">
    <tr>
        <th><?= lang('description') ?></th>
        <?php
        $colspan = 3;
        $invoice_view = config_item('invoice_view');
        if (!empty($invoice_view) && $invoice_view == '2') {
            $colspan = 4;
            ?>
            <th><?= lang('hsn_code') ?></th>
        <?php } ?>
        <th style="text-align: right"><?= lang('price') ?></th>
        <th style="text-align: right"><?= lang('qty') ?></th>
        <th style="text-align: right"><?= lang('tax') ?></th>
        <th style="text-align: right"><?= lang('total') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (!empty($all_items)) :
        foreach ($all_items as $key => $v_item) :
            $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
            $item_tax_name = json_decode($v_item->item_tax_name);
            ?>
            <tr>
                <td class="unit"><h3><?= $item_name ?></h3>
                    <small><?= nl2br($v_item->item_desc) ?></small>
                </td>
                <?php
                $invoice_view = config_item('invoice_view');
                if (!empty($invoice_view) && $invoice_view == '2') {
                    ?>
                    <td class="unit"><?= $v_item->hsn_code ?></td>
                <?php } ?>
                <td class="unit" style="text-align: right"><?= display_money($v_item->unit_cost) ?></td>
                <td class="unit" style="text-align: right"><?= $v_item->quantity . '   ' . $v_item->unit ?></td>
                <td class="unit" style="text-align: right"><?php
                    if (!empty($item_tax_name)) {
                        foreach ($item_tax_name as $v_tax_name) {
                            $i_tax_name = explode('|', $v_tax_name);
                            echo '<small class="pr-sm">' . $i_tax_name[0] . ' (' . $i_tax_name[1] . ' %)' . '</small>' . display_money($v_item->total_cost / 100 * $i_tax_name[1]) . ' <br>';
                        }
                    }
                    ?></td>
                <td class="unit" style="text-align: right"><?= display_money($v_item->total_cost) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php endif ?>
    
    </tbody>
    <tfoot>
    <tr class="total">
        <td colspan="<?= $colspan ?>"></td>
        <td colspan="1"><?= lang('sub_total') ?></td>
        <td><?= $sales_info->sub_total ? $sales_info->sub_total : '0.00' ?></td>
    </tr>
    <?php if ($sales_info->discount_total > 0): ?>
        <tr class="total">
            <td colspan="<?= $colspan ?>"></td>
            <td colspan="1"><?= lang('discount') ?>(<?php echo $sales_info->discount_percent; ?>%)</td>
            <td> <?= $sales_info->discount ? $sales_info->discount : '0.00' ?></td>
        </tr>
    <?php endif;
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
                <tr class="total">
                    <td colspan="<?= $colspan ?>"></td>
                    <td colspan="1"><?= $tax[0] . ' (' . $tax[1] . ' %)' ?></td>
                    <td> <?= display_money($total_tax[$t_key]); ?></td>
                </tr>
            <?php }
        }
    } ?>
    <?php if ($tax_total > 0): ?>
        <tr class="total">
            <td colspan="<?= $colspan ?>"></td>
            <td colspan="1"><?= lang('total') . ' ' . lang('tax') ?></td>
            <td><?= display_money($tax_total); ?></td>
        </tr>
    <?php endif;
    if ($sales_info->adjustment > 0): ?>
        <tr class="total">
            <td colspan="<?= $colspan ?>"></td>
            <td colspan="1"><?= lang('adjustment') ?></td>
            <td><?= display_money($sales_info->adjustment); ?></td>
        </tr>
    <?php endif;
    $currency = get_sales_currency($sales_info); ?>
    <?php do_action('multicurrency_in_invoice_pdf', $sales_info); ?>
    <tr class="total">
        <td colspan="<?= $colspan ?>"></td>
        <td colspan="1"><?= lang('total') ?></td>
        <td><?= display_money($sales_info->total, $currency->symbol) ?></td>
    </tr>
    <?php
    if (!empty($paid_amount) && $paid_amount > 0) {
        $total = lang('total_due');
        if ($paid_amount > 0) {
            $text = 'style="color:red"';
            ?>
            <tr class="total">
                <td colspan="<?= $colspan ?>"></td>
                <td colspan="1"><?= lang('paid_amount') ?></td>
                <td><?= display_money($paid_amount, $currency->symbol); ?></td>
            </tr>
        <?php } else {
            $text = '';
        }
    }
    ?>
    
    <?php
    if (!empty($paid_amount) && $paid_amount > 0) { ?>
        <tr class="total">
            <td colspan="<?= $colspan ?>"></td>
            <td colspan="1"><span <?= $text ?>><?= $total ?></span></td>
            <td><?= display_money(($invoice_due), $currency->symbol); ?></td>
        </tr>
    <?php } ?>
    </tfoot>
</table>

<?php
if (!empty($invoice_due) && $invoice_due > 0 && config_item('amount_to_words') == 'Yes') { ?>
    <div class="clearfix">
        <p class="right h4 num_word"><strong class="h3"><?= lang('num_word') ?>
                : </strong> <?= number_to_word($currency->code, $invoice_due); ?></p>
    </div>
<?php } ?>
<div id="notices">
    <div class="notice"><?= ($sales_info->notes) ?></div>
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
<footer>
    <?= (!empty($footer) ? $footer : '') ?>
</footer>
</body>
</html>
