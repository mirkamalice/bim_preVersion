<?= message_box('success') ?>
<?= message_box('error');
$edited = can_action('39', 'edited');
$deleted = can_action('39', 'deleted');
$currency = $this->items_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');


?>


<div class="row mb">
    <div class="col-sm-10">





    </div>
    <div class="col-sm-2 pull-right">


        <a onclick="print_purchase('print_transfer')" href="#" data-toggle="tooltip" data-placement="top" title=""
            data-original-title="Print" class="mr-sm btn btn-xs btn-danger pull-right">
            <i class="fa fa-print"></i>
        </a>
        <a href="<?= base_url() ?>admin/items/pdf_transferitem/<?= $item_info->transfer_itemList_id ?>"
            data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"
            class="btn btn-xs btn-success pull-right mr-sm">
            <i class="fa fa-file-pdf-o"></i>
        </a>
        <?php if (!empty($edited)) { ?>
        <a href="<?= base_url() ?>admin/items/createTransferItem/<?= $item_info->transfer_itemList_id ?>"
            data-toggle="tooltip" data-placement="top" title="" data-original-title="PDF"
            class="btn btn-xs btn-primary pull-right mr-sm">
            <i class="fa fa-pencil-square-o"></i>
        </a>
        <?php } ?>

    </div>
</div>


<div class="panel" id="print_transfer">

    <div class="panel-body mt-lg">
        <div class="row">
            <div class="col-lg-6 hidden-xs">
                <img class="pl-lg" style="width: 233px;height: 120px;"
                    src="<?= base_url() . config_item('invoice_logo') ?>">
            </div>

            <div class="col-lg-6 col-xs-12 ">
                <div class="pull-right pr-lg">
                    <?php
                    $statusss = null;
                    if (!empty($item_info->status)) {
                        if ($item_info->status == 'complete') {
                            $statusss = "<span class='label label-success'>" . lang($item_info->status) . "</span>";
                        } elseif ($item_info->status == 'approved') {
                            $statusss = "<span class='label label-primary'>" . lang($item_info->status) . "</span>";
                        } elseif ($item_info->status == 'rejected') {
                            $statusss = "<span class='label label-danger'>" . lang($item_info->status) . "</span>";
                        } else {
                            $statusss = "<span class='label label-warning'>" . lang($item_info->status) . "</span>";
                        }
                    }
                    ?>
                    <div class="pl-sm">
                        <h4 class="mb0"><?= lang('Transfer Info') ?></h4>
                        Reference No: <?= $item_info->reference_no ?>
                        <br /><?= lang('date') ?> :
                        <?= strftime(config_item('date_format'), strtotime($item_info->date)); ?>
                        <br>Item Name: <?= $item_info->item_name ?>
                        <br><?= lang('quantity') ?>: <?= $item_info->quantity ?>
                        <br><?= lang('warehouse_name') ?>: <?= $item_info->to_warehouse_name ?>
                        <br><?= lang('status') ?>: <?= $statusss ?>

                    </div>
                </div>



            </div>
        </div>

        <div class="row mb-lg">

            <div class="col-lg-6 col-xs-6">
                <h5 class="p-md bg-items mr-15">
                    <?= lang('Form Warehouse') ?>:
                </h5>
                <div class="pl-sm">
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
                    <h3 style="margin: 0px"><?= $warehouse_code ?></h3>
                    <h4 class="mb0"><?= $client_name ?></h4>
                    <?= $address ?>

                    <br><?= lang('phone') ?>: <?= $phone ?>
                    <br><?= lang('mobile') ?>: <?= $mobile ?>
                    <br><?= lang('email') ?>: <?= $email ?>

                </div>
            </div>

            <div class="col-lg-6 col-xs-6 ">
                <h5 class="p-md bg-items ml-13">
                    <?= lang('To') . ' ' . lang('Warehouse') ?>:
                </h5>
                <div class="pl-sm">
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
                    <h3 style="margin: 0px"><?= $warehouse_code ?></h3>
                    <h4 class="mb0"><?= $client_name ?></h4>
                    <?= $address ?>

                    <br><?= lang('phone') ?>: <?= $phone ?>
                    <br><?= lang('mobile') ?>: <?= $mobile ?>
                    <br><?= lang('email') ?>: <?= $email ?>

                </div>
            </div>

        </div>


        <div class="table-responsive mb-lg">
            <table class="table items purchase-items-preview" page-break-inside: auto;>
                <thead class="bg-items">
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
                        if (isset($item_info) && $item_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                            $qty_heading = lang('hours');
                        } else if (isset($item_info) && $item_info->show_quantity_as == 'qty_hours') {
                            $qty_heading = lang('qty') . '/' . lang('hours');
                        }
                        ?>
                        <th><?php echo $qty_heading; ?></th>
                        <th class="col-sm-1"><?= lang('price') ?></th>
                        <th class="col-sm-2"><?= lang('tax') ?></th>
                        <th class="col-sm-1"><?= lang('total') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $invoice_items = $this->items_model->ordered_items_by_id($item_info->transfer_item_id);
                    if (!empty($invoice_items)) :
                        foreach ($invoice_items as $key => $v_item) :
                            $item_name = $v_item->item_name ? $v_item->item_name : strip_html_tags($v_item->item_desc);
                            $item_tax_name = json_decode($v_item->item_tax_name);
                    ?>
                    <tr class="sortable item" data-item-id="<?= $v_item->transfer_item_id ?>">
                        <td class="item_no dragger pl-lg"><?= $key + 1 ?></td>
                        <td><strong class="block"><?= $item_name ?></strong>
                            <?= strip_html_tags($v_item->item_desc) ?>
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
                    <?php endif ?>


                </tbody>
            </table>
        </div>
        <div class="row" style="margin-top: 35px">
            <div class="col-xs-8">
                <p class="well well-sm mt">
                    <?= $item_info->notes ?>
                </p>
            </div>
            <div class="col-sm-4 pv">
                <div class="clearfix">
                    <p class="pull-left"><?= lang('sub_total') ?></p>
                    <p class="pull-right mr">
                        <?= display_money($this->items_model->transfer_calculate_to('transfer_cost', $item_info->transfer_item_id), '', 2); ?>
                    </p>
                </div>


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
                <div class="clearfix">
                    <p class="pull-left"><?= $tax[0] . ' (' . $tax[1] . ' %)' ?></p>
                    <p class="pull-right mr">
                        <?= display_money($total_tax[$t_key], '', 2); ?>
                    </p>
                </div>
                <?php }
                    }
                } ?>
                <?php if ($tax_total > 0) : ?>
                <div class="clearfix">
                    <p class="pull-left"><?= lang('total') . ' ' . lang('tax') ?></p>
                    <p class="pull-right mr">
                        <?= display_money($tax_total); ?>
                    </p>
                </div>
                <?php endif ?>
                <?php if ($item_info->adjustment > 0) : ?>
                <div class="clearfix">
                    <p class="pull-left"><?= lang('shipping_cost') ?></p>
                    <p class="pull-right mr">
                        <?= display_money($item_info->adjustment); ?>
                    </p>
                </div>
                <?php endif ?>

                <div class="clearfix">
                    <p class="pull-left"><?= lang('total') ?></p>
                    <p class="pull-right mr">
                        <?= display_money($this->items_model->transfer_calculate_to('total', $item_info->transfer_item_id), $currency->symbol); ?>
                    </p>
                </div>


                <?php if (config_item('amount_to_words') == 'Yes') { ?>
                <div class="clearfix">
                    <p class="pull-right h4"><strong class="h3"><?= lang('num_word') ?>
                            : </strong>
                        <?= number_to_word('', $this->items_model->transfer_calculate_to('total', $item_info->transfer_item_id)); ?>
                    </p>
                </div>
                <?php } ?>
            </div>
        </div>

    </div>


</div>




<script type="text/javascript">
$(document).ready(function() {
    init_items_sortable(true);
});

function print_purchase(print_transfer) {
    var printContents = document.getElementById(print_transfer).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>