<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= lang('invoice') ?></title>
    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }

    ?>
    <style type="text/css">
        @font-face {
            font-family: latha;
            font-style: normal;
            font-weight: 400;
            src: url('<?= ROOTPATH ?>/assets/latha.ttf') format('truetype');
            /*src: url(http://eclecticgeek.com/dompdf/fonts/latha.ttf) format('true-type');*/
        }

        * {
            font-family: 'tamil-latha', sans-serif;
        }

        .h4 {
            font-size: 18px;
        }

        .h3 {
            font-size: 24px;
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
            font-size: 14px;
            font-family: 'tamil-latha', sans-serif;
            width: 100%;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #aaaaaa;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        #logo {
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        #company {
            <?php if (!empty($RTL)) { ?>text-align: left;
            <?php } else { ?>text-align: right;
            <?php } ?>
        }

        #details {
            margin-bottom: 20px;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        #client {
            padding-left: 6px;
            /*border-left: 6px solid #0087C3;*/
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1em;
            font-weight: normal;
            margin: 0;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        #invoice {
            <?php if (!empty($RTL)) { ?>text-align: left;
            <?php } else { ?>text-align: right;
            <?php } ?>
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 1.5em;
            line-height: 1em;
            font-weight: normal;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        table {
            width: 100%;
            border-spacing: 0;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        table.items th,
        table.items td {
            padding: 8px;
            /*background: #EEEEEE;*/
            border-bottom: 1px solid #FFFFFF;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } else { ?>text-align: left;
            <?php } ?>
        }

        table.items th {
            white-space: nowrap;
            font-weight: normal;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        table.items td {
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } else { ?>text-align: left;
            <?php } ?>
        }

        table.items td h3 {
            color: #57B223;
            font-size: 1em;
            font-weight: normal;
            margin-top: 5px;
            margin-bottom: 5px;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        table.items .no {
            background: #dddddd;
        }

        table.items .desc {
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } else { ?>text-align: left;
            <?php } ?>
        }

        table.items .unit {
            background: #dddddd;
        }

        table.items .qty {}

        table.items td.unit,
        table.items td.qty,
        table.items td.total {
            font-size: 1em;
        }

        table.items tbody tr:last-child td {
            border: none;

        }

        table.items tfoot td {
            padding: 10px 20px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
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
            <?php if (!empty($RTL)) { ?>text-align: left;
            <?php } else { ?>text-align: right;
            <?php } ?>
        }

        #thanks {
            font-size: 1.5em;
            margin-bottom: 20px;
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

        tr.total td,
        tr th.total,
        tr td.total {
            <?php if (!empty($RTL)) { ?>text-align: left;
            <?php } else { ?>text-align: right;
            <?php } ?>
        }

        .bg-items {
            background: #303252 !important;
            color: #FFFFFF
        }

        .p-md {
            padding: 12px !important;
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
    }
    ?>

    <table class="clearfix">
        <tr>
            <td>
                <div id="logo" class="left">
                    <img style=" width: 233px;height: 120px" src="<?= $img ?>">
                </div>
            </td>
            <td class="">

                <div class="right" style="float: right;padding-right: 20px">

                    <h2 style="margin-bottom: 0"><?= config_item('company_legal_name') ?></h2>
                    <div class="date"><?= config_item('company_address') ?>
                    </div>
                    <div class="date"><?= config_item('company_city') ?>
                    </div>
                    <div class="date"><?= config_item('company_zip_code') ?>
                    </div>
                    <div class="date"><?= config_item('company_country') ?>
                    </div>
                    <div class="date"><?= lang('phone') ?> : <?= config_item('company_phone') ?>
                    </div>
                    <div class="date"><?= lang('vat_number') ?> : <?= config_item('company_vat') ?>
                    </div>



                </div>

            </td>
        </tr>
    </table>

    <table id="details" class="clearfix">
        <tr>
            <td style="width: 50%;overflow: hidden">
                <h4 class="p-md bg-items ">
                    <?= lang('Form Warehouse') ?>:
                </h4>
            </td>
            <td style="width: 50%">
                <h4 class="p-md bg-items ">
                    <?= lang('warehouse') . ' ' . lang('info') ?>
                </h4>
            </td>
        </tr>
        <tr style="margin-top: 0px">
            <?php
            $get_warehouse = get_row('tbl_warehouse', array('warehouse_id' => $item_info->from_warehouse_id));
            if (!empty($get_warehouse)) {
                $warehouse_code = $get_warehouse->warehouse_code;
                $client_name = $get_warehouse->warehouse_name;
                $address = $get_warehouse->address;
                $mobile = $get_warehouse->mobile;
                $phone = $get_warehouse->phone;
                $email = $get_warehouse->email;
            } else {
                $warehouse_code = '-';
                $client_name = '-';
                $address = '-';
                $mobile = '-';
                $zipcode = '-';
                $country = '-';
                $phone = '-';
                $email = '-';
            }
            ?>
            <td style="width: 50%;overflow: hidden">
                <div style="padding-left: 5px">
                    <h3 style="margin: 0px"><?= $warehouse_code ?></h3>
                    <div><?= $client_name ?></div>
                    <div> <?= $address ?>
                    </div>
                    <div><?= lang('phone') ?>: <?= $phone ?></div>
                    <div> <?= lang('mobile') ?>: <?= $mobile ?></div>
                    <div><?= lang('email') ?>: <?= $email ?></div>

                </div>
            </td>
            <td style="width: 50%;overflow: hidden">
                <?php

                $get_warehouse = get_row('tbl_warehouse', array('warehouse_id' => $item_info->to_warehouse_id));


                if (!empty($get_warehouse)) {
                    $warehouse_code = $get_warehouse->warehouse_code;
                    $client_name = $get_warehouse->warehouse_name;
                    $address = $get_warehouse->address;
                    $mobile = $get_warehouse->mobile;
                    $phone = $get_warehouse->phone;
                    $email = $get_warehouse->email;
                } else {
                    $warehouse_code = '-';
                    $client_name = '-';
                    $address = '-';
                    $mobile = '-';
                    $zipcode = '-';
                    $country = '-';
                    $phone = '-';
                    $email = '-';
                }
                ?>

                <div style="padding-left: 5px">
                    <h3 style="margin: 0px"><?= $warehouse_code ?></h3>
                    <h3 style="margin: 0px"><?= $client_name ?></h3>
                    <div class="address"><?= $address ?></div>
                    <div class="address"><?= $phone ?></div>
                    <div class="address"><?= $mobile ?></div>
                    <div class="email"><a href="mailto:<?= $email ?>"><?= $email ?></a></div>

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
                <th><?= lang('price') ?></th>
                <th><?= lang('qty') ?></th>
                <th><?= lang('tax') ?></th>
                <th><?= lang('total') ?></th>
            </tr>
        </thead>
        <tbody style="border-bottom: 1px solid #aaaaaa">
            <?php
            $invoice_items = $this->items_model->ordered_items_by_id($item_info->transfer_item_id);

            if (!empty($invoice_items)) :
                foreach ($invoice_items as $key => $v_item) :


                    $item_name = $v_item->item_name ? $v_item->item_name : strip_html_tags($v_item->item_desc);
                    $item_tax_name = json_decode($v_item->item_tax_name);
            ?>

                    <tr>
                        <td class="unit">
                            <h3><?= $item_name ?></h3><?= strip_html_tags($v_item->item_desc) ?>
                        </td>
                        <?php
                        $invoice_view = config_item('invoice_view');
                        if (!empty($invoice_view) && $invoice_view == '2') {
                        ?>
                            <td class="unit"><?= $v_item->hsn_code ?></td>
                        <?php } ?>
                        <td class="unit"><?= display_money($v_item->unit_cost) ?></td>
                        <td class="unit"><?= $v_item->quantity . '   ' . $v_item->unit ?></td>
                        <td class="unit"><?php
                                            if (!empty($item_tax_name)) {
                                                foreach ($item_tax_name as $v_tax_name) {
                                                    $i_tax_name = explode('|', $v_tax_name);
                                                    echo '<small class="pr-sm">' . $i_tax_name[0] . ' (' . $i_tax_name[1] . ' %)' . '</small>' . display_money($v_item->total_cost / 100 * $i_tax_name[1]) . ' <br>';
                                                }
                                            }
                                            ?></td>
                        <td class="unit"><?= display_money($v_item->total_cost) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif ?>


        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="<?= $colspan ?>"></td>
                <td colspan="1"><?= lang('sub_total') ?></td>
                <td><?= display_money($this->items_model->transfer_calculate_to('transfer_cost', $item_info->transfer_item_id)); ?></td>
            </tr>

            <?php
            $tax_info = json_decode($item_info->total_tax);
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
            <?php if ($tax_total > 0) : ?>
                <tr class="total">
                    <td colspan="<?= $colspan ?>"></td>
                    <td colspan="1"><?= lang('total') . ' ' . lang('tax') ?></td>
                    <td><?= display_money($tax_total); ?></td>
                </tr>
            <?php endif;
            if ($item_info->adjustment > 0) : ?>
                <tr class="total">
                    <td colspan="<?= $colspan ?>"></td>
                    <td colspan="1"><?= lang('shipping_cost') ?></td>
                    <td><?= display_money($item_info->adjustment); ?></td>
                </tr>
            <?php endif ?>

            <tr class="total">
                <td colspan="<?= $colspan ?>"></td>
                <td colspan="1"><?= lang('total') ?></td>
                <td><?= display_money($this->items_model->transfer_calculate_to('total', $item_info->transfer_item_id), $currency->symbol); ?></td>
            </tr>

        </tfoot>
    </table>
    <?php if (config_item('amount_to_words') == 'Yes') { ?>
        <div class="clearfix">
            <p class="right h4"><strong class="h3"><?= lang('num_word') ?>
                    : </strong> <?= number_to_word('', $this->items_model->transfer_calculate_to('total', $item_info->transfer_item_id)); ?></p>
        </div>
    <?php } ?>
    <div id="thanks"><?= lang('thanks') ?>!</div>


    <footer>
        <?= config_item('invoice_footer') ?>
    </footer>
</body>

</html>