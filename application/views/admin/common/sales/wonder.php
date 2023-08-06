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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');

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

    .n-table thead th {
        border-bottom-width: 1px;
        text-transform: uppercase;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        letter-spacing: 0.5px;
        border-color: #f3f3f3;
    }

    .text-muted {
        color: #74788d !important;
    }

    .border-0 {
        border: 0 !important;
    }

    .s-table th,
    .s-table td {
        padding: 4px 20px;
        text-align: right;
        color: #010F1C;
        /* font-size: 13px; */
        /* color: #495057;
        border-bottom: 1px solid #e9e9ef;
        font-size: 12px; */

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

    .text-uppercase {
        text-transform: uppercase;
    }

    .j-table .thead-primary th {
        background-color: #007D88;
        font-weight: 500;
    }

    .j-table .thead-primary th {
        color: #fff;
    }

    .j-table td,
    .j-table th {
        vertical-align: middle;
        padding: .75rem 1.25rem;
    }

    .table-bordered,
    .table-bordered>tbody>tr>td,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>td,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>thead>tr>th {
        border: 1px solid #eee;
        border-collapse: collapse;
    }

    .font-11 {
        color: #f05050 !important;
    }

    .invoice-table th,
    .invoice-table td {
        padding: 11px 20px;
        /* border: none; */
    }

    .invoice-table tr {
        /* border-bottom: 1px solid #CFD3DC; */
        position: relative;
    }

    .invoice-table td {
        padding: 11px 20px;
        border-bottom: 1px solid #CFD3DC;
    }

    .invoice-table thead th,
    .invoice-table thead td {
        background-color: #DDE2EE;
    }

    .invoice-table thead th:first-child,
    .invoice-table thead td:first-child {
        border-radius: 99px 0 0 99px;
    }

    .invoice-table th {
        color: #010F1C;
    }

    .invoice-table th:last-child,
    .invoice-table td:last-child {
        text-align: right;
    }

    .invoice-table thead th:last-child,
    .invoice-table thead td:last-child {
        border-radius: 0 99px 99px 0;
    }

    .invoice-table thead tr {
        /* border-bottom: none; */
    }
</style>


<div class="invoice-preview-inner clearfix panel panel-custom" style="font-family: 'Inter', sans-serif;">
    <div class="preview-main client-preview clearfix" style="padding: 30px;">

        <div class="d-header clearfix">
            <table class="clearfix" style="margin-bottom: 15px;">
                <tr>
                    <td>
                        <div><img style="width: 150px;" src=<?= $img ?> alt=""></div>
                    </td>

                    <td style="vertical-align: middle;">
                        <h4 class="m-0" style="text-align: right; font-size: 30px; color:#010F1C"><?= $title ?></h4>

                    </td>
                </tr>
            </table>
            <table class="clearfix" style="margin-bottom: 15px;">
                <tr>
                    <td style="width: 40%;">
                        <div>
                            <b style="color : #010F1C"><?= lang('our_info') ?>:</b>
                            <div>
                            <?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?>
                                <?= config_item('company_email') ?>
                                <?= (!empty(config_item('company_address')) ? ' <br>' . config_item('company_address') : '') ?>
                                <?= (!empty(config_item('company_city')) ? ' ,' . config_item('company_city') : '') ?>
                                <?= (!empty(config_item('company_zip_code')) ? ' ,' . config_item('company_zip_code') : '') ?>
                                <?= (!empty(config_item('company_country')) ? ' <br>' . config_item('company_country') : '') ?>
                                <?= (!empty(config_item('vat_number')) ? ' <br> <br>' . config_item('vat_number') : '') ?>
                                <?= (!empty(config_item('company_phone')) ? '<br><abbr title="Phone">' . lang('phone') . '</abbr> : ' . config_item('company_phone') : '') ?>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <?php $ref = explode(':', $sales_info->ref_no); ?>
                        <div class="invoice-number"><b style="color : #010F1C"><?= $ref[0] ?>: </b><?= $ref[1] ?></div>
                        <?php
                        if (!empty($sales_info->start_date)) {
                            $start_date = explode(':', $sales_info->start_date);
                        ?>

                            <div class="invoice-date"><b style="color : #010F1C"><?php echo $start_date[0]; ?>: </b><?php echo $start_date[1]; ?></div>

                        <?php
                        }
                        if (!empty($sales_info->end_date)) {
                            $end_date = explode(':', $sales_info->end_date);
                        ?>


                            <div class="invoice-date"><b style="color : #010F1C"><?php echo $end_date[0]; ?>: </b><?php echo $end_date[1]; ?></div>

                        <?php
                        }
                        if (!empty($sales_info->sales_agent)) {
                            $sales_agent = explode(':', $sales_info->sales_agent);
                        ?>

                            <div class="invoice-date"><b style="color : #010F1C"><?php echo $sales_agent[0]; ?>: </b><?php echo $sales_agent[1]; ?></div>

                        <?php } ?>

                        <?php
                        if (!empty($sales_info->status)) {
                            $status = explode(':', $sales_info->status);
                        ?>

                            <div class="invoice-date"><b style="color : #010F1C"><?php echo $status[0]; ?>: </b><?php echo $status[1]; ?></div>

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
                                        // $details_span += 1;
                                        $custom_field = explode(':', $v_custom_field);


                                        $customFiled .= '<div class="invoice-date"><b style="color : #010F1C">' . $custom_field[0] . ': </b>' . $custom_field[1] . '</div>';
                                    }
                                }
                            }
                        ?>
                        <?php }
                        echo $customFiled;
                        ?>
                    </td>






                </tr>
            </table>
            <table class="clearfix" style="margin-bottom: 15px;">
                <tr>
                    <td>
                        <div>
                            <b style="color : #010F1C"><?= lang('customer') ?>:</b>

                            <div>
                                <?= (!empty($sales_info->name) ? $sales_info->name : '') ?>
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

                    </td>
                    <td style="text-align: right;">
                        <div>
                            <?= (!empty($qrcode) ? $qrcode : '') ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="invoice-table clearfix" style="margin-top: 45px;">
            <thead>
                <tr>
                    <th>No.</th>
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
                            <td>
                                <?= $item_name ?>
                                <b><?= nl2br($v_item->item_desc) ?></b>

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
                <!-- <tr>
                    <td colspan="6">&nbsp;</td>
                </tr> -->
            </tbody>
        </table>

        <div class="clearfix" style="margin-top: 15px;">
            <div class="row">
                <div class="col-xs-7">
                    <?= ($sales_info->notes) ?>
                </div>
                <div class="col-xs-5">
                    <table class="s-table">

                        <tbody>
                            <tr>
                                <td><strong><?= lang('sub_total') ?> :</strong></td>
                                <td><?= $sales_info->sub_total ? display_money($sales_info->sub_total) : '0.00' ?></td>
                            </tr>
                            <?php if ($sales_info->discount > 0) : ?>
                                <tr>
                                    <td><strong><?= lang('discount') ?> :
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
                                            <td><strong><?= $tax[0] . ' (' . $tax[1] . ' %)' ?></strong> :</td>
                                            <td><?= display_money($total_tax[$t_key]); ?></td>


                                        </tr>
                            <?php }
                                }
                            } ?>

                            <?php if ($tax_total > 0) : ?>
                                <tr>
                                    <td><strong><?= lang('total') . ' ' . lang('tax') ?></strong> :</td>
                                    <td><?= display_money($tax_total); ?></td>

                                </tr>
                            <?php endif ?>
                            <?php if ($sales_info->adjustment > 0) : ?>
                                <tr>
                                    <td><strong><?= lang('adjustment') ?> :</strong></td>
                                    <td><?= display_money($sales_info->adjustment); ?></td>
                                </tr>
                            <?php endif;
                            $currency = get_sales_currency($sales_info);
                            ?>

                            <tr>
                                <td style="border-bottom: 1px solid #CFD3DC;"></td>
                                <td style="border-bottom: 1px solid #CFD3DC;"></td>
                            </tr>
                            <tr>

                                <td><strong><?= lang('total') ?></strong> :</td>
                                <td><strong><?= display_money($sales_info->total, $currency->symbol) ?></strong></td>

                            </tr>
                            <?php
                            if (!empty($paid_amount) && $paid_amount > 0) {
                                $total = lang('total_due');
                                if ($paid_amount > 0) {
                                    $text = 'text-danger';
                            ?>
                                    <tr>
                                        <td><strong><?= lang('paid_amount') ?></strong> :</td>
                                        <td>
                                            <strong><?= display_money($paid_amount, $currency->symbol); ?></strong>
                                        </td>


                                    </tr>
                                <?php } else {
                                    $text = '';
                                } ?>
                            <?php } ?>

                            <?php
                            if (!empty($paid_amount) && $paid_amount > 0) { ?>

                                <tr>
                                    <td class=<?= $text ?>><strong><?= $total ?></strong> :</td>
                                    <td class=<?= $text ?>> <strong><?= display_money(($invoice_due), $currency->symbol); ?></strong> </td>
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
    </div>
</div>



<?php include_once 'assets/js/sales.php'; ?>

<script type="text/javascript">
    $(document).ready(function() {
        init_items_sortable(true);
    });

    function print_sales_details(sales_details) {
        var printContents = document.getElementById(sales_details).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>