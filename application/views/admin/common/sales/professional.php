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
    @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap');


    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }



    table {
        width: 100%;
        border-spacing: 0;

    }


    .p-md {
        padding: 9px !important;
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
        font-family: 'Lato', sans-serif;
        color: #000;
        font-size: 14px;
    }

    .preview-main.client-preview {
        width: 710px;
        margin-left: auto;
        margin-right: auto;
    }

    .text-right {
        text-align: right;
    }

    .p-50 {
        padding: 50px;
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
        font-family: 'Lato', sans-serif;
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
        /* border: none; */
    }

    .mr-100 {
        margin-right: 100px;
    }

    /* 
        table.items tbody tr:nth-child(odd) td{
            border-color: red;
            border-width: 0.5px;

        } */
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

    .mb-0 {
        margin-bottom: 0;
    }

    .sub-title-2 {
        margin-top: 12px;
        margin-bottom: 15px;
        font-size: 17px;
    }
</style>


<div class="invoice-preview-inner clearfix">
    <div class="preview-main client-preview clearfix">
        <div class="d-inner p-50" style="border-top: 15px solid #003580; border-bottom: 15px solid #003580;">
            <div class="d-header clearfix">
                <div class="d-header-inner clearfix">
                    <table class="clearfix">
                        <tr>
                            <td>
                                <div class="d-header-brand"><img style="width: 200px;height: 80px;" src=<?= $img ?> alt=""></div>
                                <strong class="sub-title sub-title-2"><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></strong>
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
                                <h1 class="fancy-title font-700 mt-0 mb-0" style="text-align: center;"><?= $title ?></h1>
                                <div class="mb-5">
                                    <div style=" text-align : right ;margin-left:auto ;font-size:0;position:relative;width:114px;height:114px;">
                                        <?= (!empty($qrcode) ? $qrcode : '') ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="break-50"></div>
                            </td>
                        </tr>
                        <tr>

                            <td>
                                <div class="bill_to">
                                    <strong>Bill To:</strong>
                                    <p style="margin : 0 ; margin-bottom : 10px">

                                        <strong><?= (!empty($sales_info->name) ?   $sales_info->name : '') ?></strong>


                                        <?= (!empty($sales_info->address) ? ' <br>' .  $sales_info->address : '') ?>
                                        <?= (!empty($sales_info->city) ? ' <br>' .  $sales_info->city . ',' : '') ?>
                                        <?= (!empty($sales_info->zipcode) ? ' <br>' .  $sales_info->zipcode : '') ?>
                                        <?= (!empty($sales_info->country)  ? ' <br>' .   $sales_info->country : '') ?>
                                        <br><abbr title="Phone"><?= lang('phone') ?></abbr>
                                        : <?= (!empty($sales_info->phone) ? $sales_info->phone : '') ?>
                                        <?php if (!empty($sales_info->vat)) { ?>
                                            <br><?= lang('vat_number') ?>: <?= $sales_info->vat ?>
                                        <?php } ?>

                                    </p>
                                </div>
                            </td>

                            <td style="vertical-align: top;">


                                <table class="summary-table mb-5">
                                    <tbody>
                                    <?php $ref = explode(':', $sales_info->ref_no);
                                        ?>
                                        <tr>
                                            <td class=" font-700"><?= $ref[0] ?>:</td>
                                            <td><?= $ref[1] ?></td>
                                        </tr>
                                        <tr>
                                            <td class=" font-700">Purchase Date:</td>
                                            <td>Jan 1, 1970</td>
                                        </tr>
                                    </tbody>
                                </table>


                            </td>

                        </tr>
                        <tr>
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
                    <thead class="p-md" style="background: #003580;color:#fff;">
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
                            <th>Price <br><small class="font-11 text-danger"><?= lang('total') ?></small></th>
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
                                <tr class="sortable item" data-item-id="<?= $v_item->$itemId ?>">
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
                            <td style="width: 60%; vertical-align: top;">

                            </td>
                            <td style="width: 40%; vertical-align: top;">
                                <div class="mr">
                                    <p class="text-right m0">
                                        <b><?= lang('sub_total') ?> : </b> <?= $sales_info->sub_total ? display_money($sales_info->sub_total) : '0.00' ?>
                                    </p>
                                    <?php if ($sales_info->discount > 0) : ?>

                                        <p class="text-right m0"><?= lang('discount') ?>
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
                                                <p class="text-right mb0"><?= $tax[0] . ' <small>(' . $tax[1] . ' %)</small>' ?>
                                                    : <?= display_money($total_tax[$t_key]); ?></p>
                                    <?php }
                                        }
                                    } ?>
                                    <?php if ($tax_total > 0) : ?>
                                        <hr class="m0">
                                        <p class="text-right m0"><?= lang('total') . ' ' . lang('tax') ?>
                                            : <?= display_money($tax_total); ?></p>
                                    <?php endif ?>

                                    <?php if ($sales_info->adjustment > 0) : ?>
                                        <p class="text-right m0"><?= lang('adjustment') ?>
                                            : <?= display_money($sales_info->adjustment); ?></p>
                                    <?php endif;
                                    $currency = get_sales_currency($sales_info);
                                    ?>
                                    <hr class="m0">

                                    <h4 class="text-right m0 mt"><?= lang('Total') ?> :
                                        <?= display_money($sales_info->total, $currency->symbol) ?></h4>
                                    <?php
                                    if (!empty($paid_amount) && $paid_amount > 0) {
                                        $total = lang('due');
                                        if ($paid_amount > 0) {
                                            $text = 'font-11';
                                    ?>
                                            <h4 class="text-right m0 mt"><?= lang('paid') ?> :
                                                <?= display_money($paid_amount, $currency->symbol); ?></h4>
                                        <?php } else {
                                            $text = '';
                                        } ?>
                                    <?php } ?>
                                    <?php
                                    if (!empty($paid_amount) && $paid_amount > 0) { ?>
                                        <h4 class="text-right m0 mt <?= $text ?>"><?= $total ?> :
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
                    <div class="clearfix">
                        <p class=""><strong class="h3"><?= lang('num_word') ?>
                                : </strong> <?= number_to_word($currency->code, $due_amount); ?></p>
                    </div>
                <?php } ?>

                <div class="d-header-50 mt-5">
                    
                    <p>Thanks! </p>
                </div>

                


            </div>
        </div>
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