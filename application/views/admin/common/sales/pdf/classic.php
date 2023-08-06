<html>

<head>
    <title><?= lang($title) ?></title>
    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font: 13px/1.4 dejavusanscondensed;
            width: 100%;
        }


        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        #page-wrap {
            width: 95%;
            margin: 0 auto;
        }

        table {
            border-collapse: collapse;
        }

        table td,
        table th {
            border: 1px solid #dde6e9;
            padding: 5px;
        }

        #customer {
            clear: both;
            width: 100%;
        }

        .top_header {
            border-bottom: 1px solid #dde6e9;
            margin-bottom: 15px;
            clear: both;
            width: 100%;
        }

        #logo {
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 15px;
        }

        #meta {
            clear: both;
            margin-top: 1px;
            width: 100%;
            float: right;
        }

        #meta td {
            text-align: right;
        }

        #meta td.meta-head {
            text-align: left;
            background: #eee;
        }

        #meta td textarea {
            width: 100%;
            height: 20px;
            text-align: right;
        }

        #items {
            clear: both;
            width: 100%;
            margin: 30px 0 0 0;
        }

        #items th {
            background: #eee;
        }

        #items textarea {
            width: 80px;
            height: 50px;
        }

        #items tr.item-row td {
            vertical-align: top;
        }

        #items td.description {
            width: 300px;
        }

        #items td.item-name {
            width: 175px;
        }

        #items td.description textarea,
        #items td.item-name textarea {
            width: 100%;
        }

        #items td.total-line {
            border-right: 0;
            text-align: right;
        }

        #items td.total-value {
            border-left: 0;
            padding: 10px;
            text-align: right;
            border-left: 1px solid #dde6e9;
        }

        #items td.total-value textarea {
            height: 20px;
            background: none;
        }

        #items td.balance {
            background: #eee;
        }

        #items td.blank {
            border: 0;
        }

        #terms {
            text-align: left;
            margin: 20px 0 0 0;
        }

        #terms h5 {
            text-transform: uppercase;
            font: 13px dejavusanscondensed;
            letter-spacing: 10px;
            border-bottom: 1px solid #dde6e9;
            padding: 0 0 8px 0;
            margin: 0 0 15px 0 !important;
        }

        #terms textarea {
            width: 100%;
            text-align: center;
        }

        #items td.blank {
            border: 0;
        }

        .price {
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <?php
    $uri = $this->uri->segment(3);
    if ($uri == 'invoice_email') {
        $img = base_url() . config_item('invoice_logo');
    } else {
        //    $img = ROOTPATH . '/' . config_item('invoice_logo');
        //    $a = file_exists($img);
        //    if (empty($a)) {
        //        $img = base_url() . config_item('invoice_logo');
        //    }
        //    if (!file_exists($img)) {
        //        $img = ROOTPATH . '/' . 'uploads/default_logo.png';
        //    }
        if (is_file(config_item('invoice_logo'))) {
            $img = base_url() . config_item('invoice_logo');
        } else {
            $img = base_url() . 'uploads/default_logo.png';
        }
    }
    //$img = base_url() . config_item('invoice_logo');
    $client_lang = 'english';
    $details_span = 5;
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
                    $customFiled .= '<tr><td class="meta-head">' . $custom_field[0] . '</td><td>' . $custom_field[1] . '</td></tr>';
                }
            }
        }
    ?>
    <?php } ?>
    <div id="page-wrap">

        <table class="clearfix top_header">
            <tr>
                <td style="border: 0;  text-align: left;width: 62%;">
                    <span style="font-size: 18px; color: #2f4f4f"><strong><?= $sales_info->ref_no ?></strong>
                        <br>
                        <?php
                        if (!empty($qrcode)) { ?>
                            <div class="pull-right">
                                <?= (!empty($qrcode) ? $qrcode : '') ?>
                            </div>
                        <?php }
                        ?>
                    </span>
                </td>
                <td style="border: 0;  text-align: right" width="62%">
                    <div id="logo">
                        <img id="image" style="width: 170px;
            height: 80px;" src="<?php echo $img; ?>" alt="logo" />
                        <br>
                        <?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?>
                        <br>
                        <div><?= (config_item('company_address_' . $client_lang) ? config_item('company_address_' . $client_lang) : config_item('company_address')) ?></div>
                        <div><?= (config_item('company_city_' . $client_lang) ? config_item('company_city_' . $client_lang) : config_item('company_city')) ?>
                            , <?= config_item('company_zip_code') ?></div>
                        <div><?= (config_item('company_country_' . $client_lang) ? config_item('company_country_' . $client_lang) : config_item('company_country')) ?></div>
                        <div> <?= config_item('company_phone') ?></div>
                        <div><a href="mailto:<?= config_item('company_email') ?>"><?= config_item('company_email') ?></a>
                        </div>
                        <div><?= config_item('company_vat') ?></div>
                    </div>
                </td>
            </tr>


        </table>
        <div class="clearfix" style="clear:both"></div>

        <div class="clearfix" id="customer">

            <table class="clearfix" id="meta">
                <tr>
                    <td rowspan="<?= $details_span ?>" style="border: 1px solid white; border-right: 1px solid #dde6e9; text-align: left" width="62%">
                        <?php
                        echo '<h4>' . lang('bill_to') . '</h4>' .
                            '<br>';
                        ?>
                        <h3 style="margin: 0px"><?= (!empty($sales_info->name) ? $sales_info->name : '') ?></h3>
                        <div class="address"><?= (!empty($sales_info->address) ? $sales_info->address : '') ?></div>
                        <div class="address"><?= (!empty($sales_info->city) ? $sales_info->city : '') ?>
                            , <?= (!empty($sales_info->zipcode) ? $sales_info->zipcode : '') ?>
                            ,<?= (!empty($sales_info->country) ? $sales_info->country : '') ?></div>
                        <div class="address"> <?= (!empty($sales_info->phone) ? $sales_info->phone : '') ?></div>
                        <div class="email"><a href="mailto:<?= (!empty($sales_info->email) ? $sales_info->email : '') ?>"><?= (!empty($sales_info->email) ? $sales_info->email : '') ?></a>
                        </div>
                        <?php if (!empty($sales_info->vat)) { ?>
                            <div class="email"><?= lang('vat_number') ?>: <?= $sales_info->vat ?></div>
                        <?php } ?>
                    </td>
                    <?php
                    $ref = explode(':', $sales_info->ref_no);
                    ?>
                    <td class="meta-head"><?= $ref[0] ?></td>
                    <td><?php echo $ref[1]; ?></td>
                </tr>
                <tr>
                    <?php
                    $status = explode(':', $sales_info->status);
                    ?>
                    <td class="meta-head"><?php echo $status[0] ?></td>
                    <td><?php echo $status[1]; ?></td>
                </tr>
                <tr>
                    <?php
                    $start_date = explode(':', $sales_info->start_date);
                    ?>
                    <td class="meta-head"><?php echo $start_date[0]; ?></td>
                    <td><?php echo $start_date[1]; ?></td>
                </tr>
                <tr>
                    <?php
                    $end_date = explode(':', $sales_info->end_date);
                    ?>
                    <td class="meta-head"><?php echo $end_date[0]; ?></td>
                    <td><?php echo $end_date[1]; ?></td>
                </tr>
                <?php if (!empty($sales_info->sales_agent)) {
                    $sales_agent = explode(':', $sales_info->sales_agent);
                ?>
                    <tr>
                        <td class="meta-head"><?php echo $sales_agent[0]; ?></td>
                        <td>
                            <?php echo $sales_agent[1]; ?>
                        </td>
                    </tr>
                <?php }
                echo $customFiled;
                ?>


            </table>

        </div>


        <table id="items" class="clearfix">

            <tr>
                <th align="right"><?= lang('description') ?></th>
                <?php
                $col_span = 3;
                $invoice_view = config_item('invoice_view');
                if (!empty($invoice_view) && $invoice_view == '2') {
                    $col_span += 1;
                ?>
                    <th align="right"><?= lang('hsn_code') ?></th>
                <?php } ?>
                <th align="right"><?= lang('price') ?></th>
                <th align="right"><?= lang('qty') ?></th>
                <th align="right"><?= lang('tax') ?></th>
                <th align="right"><?= lang('total') ?></th>
            </tr>
            <?php
            if (!empty($all_items)) :
                foreach ($all_items as $key => $v_item) :
                    $item_name = $v_item->item_name ? $v_item->item_name : $v_item->item_desc;
                    $item_tax_name = json_decode($v_item->item_tax_name);
            ?>
                    <tr>
                        <td class="description"><strong><?= $item_name ?></strong>
                            <?php
                            if (!empty($v_item->item_desc)) {
                                echo '<small>' . nl2br($v_item->item_desc) . '</small>';
                            }
                            ?>
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
            <tr>
                <td class="blank"></td>
                <td colspan="<?php echo $col_span; ?>" class="total-line"><?php echo 'Sub Total'; ?></td>
                <td class="total-value" align="right">
                    <div id="subtotal"><?= $sales_info->sub_total ? $sales_info->sub_total : '0.00' ?></div>
                </td>
            </tr>
            <?php if ($sales_info->discount_total > 0) { ?>
                <tr>
                    <td class="blank"></td>
                    <td colspan="<?php echo $col_span; ?>" class="total-line"><?= lang('discount') ?>
                        (<?php echo $sales_info->discount_percent; ?>%)
                    </td>
                    <td class="total-value">
                        <div id="subtotal"><?= $sales_info->discount ? $sales_info->discount : '0.00' ?></div>
                    </td>
                </tr>
                <?php }
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
                            <td class="blank"></td>
                            <td colspan="<?php echo $col_span; ?>" class="total-line"><?= $tax[0] . ' (' . $tax[1] . ' %)' ?>
                            </td>
                            <td class="total-value">
                                <div id="subtotal"><?= display_money($total_tax[$t_key]); ?></div>
                            </td>
                        </tr>
                <?php }
                }
            }
            if ($tax_total > 0) { ?>
                <tr>
                    <td class="blank"></td>
                    <td colspan="<?php echo $col_span; ?>" class="total-line"><?= lang('total') . ' ' . lang('tax') ?>
                    </td>
                    <td class="total-value">
                        <div id="subtotal"><?= display_money($tax_total) ?></div>
                    </td>
                </tr>
            <?php }
            if ($sales_info->adjustment > 0) { ?>
                <tr>
                    <td class="blank"></td>
                    <td colspan="<?php echo $col_span; ?>" class="total-line"><?= lang('adjustment') ?>
                    </td>
                    <td class="total-value">
                        <div id="subtotal"><?= display_money($sales_info->adjustment) ?></div>
                    </td>
                </tr>
            <?php }
            $currency = get_sales_currency($sales_info); ?>
            <?php do_action('multicurrency_in_invoice_pdf', $sales_info); ?>
            <tr>
                <td class="blank"></td>
                <td colspan="<?php echo $col_span; ?>" class="total-line"><?php echo lang('total'); ?></td>
                <td class="total-value">
                    <div id="total"><?= display_money($sales_info->total, $currency->symbol) ?></div>
                </td>
            </tr>
            <?php
            if (!empty($paid_amount) && $paid_amount > 0) {
                $total = lang('total_due');
                $text = '';
                if ($paid_amount > 0) {
                    $text = 'style="color:red"';
            ?>
                    <tr>
                        <td class="blank"></td>
                        <td colspan="<?php echo $col_span; ?>" class="total-line"><?php echo lang('paid_amount'); ?></td>
                        <td class="total-value">
                            <div id="total"><?= display_money($paid_amount, $currency->symbol) ?></div>
                        </td>
                    </tr>
            <?php }
            }
            ?>

            <?php
            if (!empty($paid_amount) && $paid_amount > 0) { ?>
                <tr>
                    <td class="blank"></td>
                    <td colspan="<?php echo $col_span; ?>" class="total-line"><?php echo $total; ?></td>
                    <td class="total-value">
                        <div id="total"><?= display_money($invoice_due, $currency->symbol) ?></div>
                    </td>
                </tr>
            <?php } ?>
            <?php
            if (!empty($invoice_due) && $invoice_due > 0 && config_item('amount_to_words') == 'Yes') { ?>
                <tr>
                    <td class="blank"></td>
                    <td colspan="<?php echo $col_span + 1; ?>" class="total-line"><?= lang('num_word') ?>
                        : </strong> <?= number_to_word($currency->code, $invoice_due); ?></td>
                </tr>

            <?php } ?>
        </table>


        <!--    related transactions -->
        <br>
        <br>
        <?php
        if (!empty($all_payment_info)) { ?>
            <div style="margin-top:20px">
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

        <!--    end related transactions -->


        <div id="terms">
            <h5><?php echo lang('notes'); ?></h5>
            <p><?= ($sales_info->notes) ?></p>
        </div>


    </div>

</body>

</html>