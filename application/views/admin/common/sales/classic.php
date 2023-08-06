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

<div class="panel panel-custom" id="sales_details">
    <div class="panel-body">
        <div class="clearfix">
            <div class="pull-left">
                <img class="pl-lg" style="width: 200px;height: 80px;" src="<?= $img ?>">
            </div>
            <div class="pull-right">
                <h4 class="mb0"><?= (config_item('company_legal_name_' . $client_lang) ? config_item('company_legal_name_' . $client_lang) : config_item('company_legal_name')) ?></h4>
                <?= (config_item('company_address_' . $client_lang) ? config_item('company_address_' . $client_lang) : config_item('company_address')) ?>
                ,<?= (config_item('company_city_' . $client_lang) ? config_item('company_city_' . $client_lang) : config_item('company_city')) ?>
                ,<?= config_item('company_zip_code') ?>
                ,<?= (config_item('company_country_' . $client_lang) ? config_item('company_country_' . $client_lang) : config_item('company_country')) ?>
                <br/><?= lang('phone') ?> : <?= config_item('company_phone') ?>
                <br/><?= lang('vat_number') ?> : <?= config_item('company_vat') ?>
            </div>
            <?php
            if (!empty($qrcode)) { ?>
                <div class="pull-right pr-lg mt">
                    <?= (!empty($qrcode) ? $qrcode : '') ?>
                </div>
            <?php }
            ?>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">

                <div class="pull-left m-t-30">
                    <address>
                        <strong><?= (!empty($sales_info->name) ? $sales_info->name : '') ?></strong><br>
                        <?= (!empty($sales_info->address) ? $sales_info->address : '') ?>
                        <br> <?= (!empty($sales_info->city) ? $sales_info->city : '') ?>
                        , <?= (!empty($sales_info->zipcode) ? $sales_info->zipcode : '') ?>
                        <br><?= (!empty($sales_info->country) ? $sales_info->country : '') ?>
                        <br><abbr title="Phone"><?= lang('phone') ?></abbr>
                        : <?= (!empty($sales_info->phone) ? $sales_info->phone : '') ?>
                        <?php if (!empty($sales_info->vat)) { ?>
                            <br><?= lang('vat_number') ?>: <?= $sales_info->vat ?>
                        <?php } ?>
                    </address>
                </div>
                <div class="pull-right m-t-30">
                    <h4 class="mb0 mt0"><?= $sales_info->ref_no ?></h4>
                    <?= $sales_info->start_date ?>
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

            </div>
        </div>
        <div class="m-h-50"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table m-t-30">
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
                            <th class="col-sm-1"><?= lang('price') ?></th>
                            <th class="col-sm-2"><?= lang('tax') ?></th>
                            <th class="col-sm-1 text-right"><?= lang('total') ?></th>
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
                </div>
            </div>
        </div>
        <hr class="m0 mb"/>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="clearfix mt">
                    <small>
                        <p class="well well-sm mt">
                            <?= $sales_info->notes ?>
                        </p>
                    </small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-md-offset-3 col-xs-6">
                <div class="mr">
                    <p class="text-right m0">
                        <b><?= lang('sub_total') ?></b> <?= $sales_info->sub_total ? display_money($sales_info->sub_total) : '0.00' ?>
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
                            $text = 'text-danger';
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
            </div>
            <?php
            if (!empty($invoice_due) && $invoice_due > 0) {
                $due_amount = $invoice_due;
            } else {
                $due_amount = $sales_info->total;
            }
            if (config_item('amount_to_words') == 'Yes' && !empty($due_amount) && $due_amount > 0) { ?>
                <div class="clearfix">
                    <p class="pull-right h4 m0 mr-lg"><strong class="h3"><?= lang('num_word') ?>
                            : </strong> <?= number_to_word($currency->code, $due_amount); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<?php include_once 'assets/js/sales.php'; ?>

<script type="text/javascript">
    $(document).ready(function () {
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