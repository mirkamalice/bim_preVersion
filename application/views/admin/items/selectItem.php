<div class="bt">
    <div class="row prodcutTable" data-terget="<?= $itemType ?>">
        <div class="col-md-12">
            <div class="col-md-6 mt">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon" title="<?= lang('search_product_by_name_code') ?>">
                            <i class="fa fa-barcode"></i>
                        </div>
                        <input type="text" placeholder="<?= lang('search_product_by_name_code'); ?>"
                               id="<?= $itemType ?>_item" class="form-control">
                        <div class="input-group-addon" title="<?= lang('add') . ' ' . lang('manual') ?>"
                             data-toggle="tooltip" data-placement="top">
                            <a data-toggle="modal" data-target="#myModal_lg"
                               href="<?= base_url() ?>admin/items/manuallyItems"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5 pull-right">
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo lang('show_quantity_as'); ?></label>
                    <div class="col-sm-8">
                        <label class="radio-inline c-radio">
                            <input type="radio" value="qty" id="<?php echo lang('qty'); ?>"
                                   name="show_quantity_as" <?php if (isset($info) && $info->show_quantity_as == 'qty') {
                                echo 'checked';
                            } else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {
                                echo 'checked';
                            } ?>>
                            <span class="fa fa-circle"></span><?php echo lang('qty'); ?>
                        </label>
                        <label class="radio-inline c-radio">
                            <input type="radio" value="hours" id="<?php echo lang('hours'); ?>"
                                   name="show_quantity_as" <?php if (isset($info) && $info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                echo 'checked';
                            } ?>>
                            <span class="fa fa-circle"></span><?php echo lang('hours'); ?></label>
                        <label class="radio-inline c-radio">
                            <input type="radio" value="qty_hours" id="<?php echo lang('qty') . '/' . lang('hours'); ?>"
                                   name="show_quantity_as" <?php if (isset($info) && $info->show_quantity_as == 'qty_hours' || isset($qty_hrs_quantity)) {
                                echo 'checked';
                            } ?>>
                            <span class="fa fa-circle"></span><?php echo lang('qty') . '/' . lang('hours'); ?>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive s_table">
                <table class="table invoice-items-table items">
                    <thead style="background: #e8e8e8">
                    <tr>
                        <th></th>
                        <th><?= lang('item_name') ?></th>
                        <th><?= lang('description') ?></th>
                        <?php
                        $invoice_view = config_item('invoice_view');
                        if (!empty($invoice_view) && $invoice_view == '2') {
                            ?>
                            <th class="col-sm-2"><?= lang('hsn_code') ?></th>
                        <?php } ?>
                        <?php
                        $qty_heading = lang('qty');
                        if (isset($info) && $info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                            $qty_heading = lang('hours');
                        } else if (isset($info) && $info->show_quantity_as == 'qty_hours') {
                            $qty_heading = lang('qty') . '/' . lang('hours');
                        }
                        ?>
                        <th class="qty col-sm-1"><?php echo $qty_heading; ?></th>
                        <th class="col-sm-2"><?= lang('price') ?></th>
                        <th class="col-sm-2"><?= lang('tax_rate') ?> </th>
                        <th class="col-sm-1"><?= lang('total') ?></th>
                        <th class="hidden-print"><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody id="<?= $itemType ?>Table">
                    
                    </tbody>
                </table>
            </div>
            
            <div class="row">
                <div class="col-xs-8 pull-right">
                    <table class="table text-right">
                        <tbody>
                        <tr id="subtotal">
                            <td><span class="bold"><?php echo lang('sub_total'); ?> :</span>
                            </td>
                            <td class="subtotal">
                            </td>
                        </tr>
                        <tr id="discount_percent">
                            <?php
                            $adjustmentText = 'shipping_cost';
                            if ($itemType != 'transfer') {
                                $adjustmentText = 'adjustment';
                                ?>
                                <td>
                                    <div class="row">
                                        <div class="col-md-7">
                                                <span class="bold"><?php echo lang('discount'); ?>
                                                    (%)</span>
                                        </div>
                                        <div class="col-md-5">
                                            <?php
                                            $discount_percent = 0;
                                            if (isset($info)) {
                                                if ($info->discount_percent != 0) {
                                                    $discount_percent = $info->discount_percent;
                                                }
                                            } ?>
                                            <input type="text" data-parsley-type="number"
                                                   value="<?php echo $discount_percent; ?>"
                                                   class="form-control pull-left" min="0" max="100"
                                                   name="discount_percent">
                                        </div>
                                    </div>
                                </td>
                                <td class="discount_percent"></td>
                                <?php
                            } ?>
                        
                        </tr>
                        <?php
                        if ($itemType == 'invoice') {
                            do_action('add_more_field_after_discount_field', !empty($info) ? $info : '');
                        } ?>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-7">
                                        <span class="bold"><?php echo lang($adjustmentText); ?></span>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" data-parsley-type="number"
                                               value="<?php if (isset($info)) {
                                                   echo $info->adjustment;
                                               } else {
                                                   echo 0;
                                               } ?>" class="form-control pull-left" name="adjustment">
                                    </div>
                                </div>
                            </td>
                            <td class="adjustment"></td>
                        </tr>
                        <tr>
                            <td><span class="bold"><?php echo lang('total'); ?> :</span>
                            </td>
                            <td class="total">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="removed-items"></div>
<div class="btn-bottom-toolbar text-right">
    <input type="button" id="Preset" value="<?= lang('reset') ?>" name="update" class="btn btn-danger">
    <?php
    if (!empty($add_items)) { ?>
        <input type="hidden" name="isedit" value="1">
        <input type="submit" value="<?= lang('update') ?>" name="create" class="btn btn-primary">
        <button type="button" onclick="goBack()" class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
    <?php } else { ?>
        <input type="submit" value="<?= lang('save_as_draft') ?>" name="save_as_draft" class="btn btn-primary">
        <input type="submit" value="<?= lang('update') ?>" name="update" class="btn btn-success">
    <?php }
    ?>
</div>

<?php
if (!empty($add_items)) {
    if (!empty($info->warehouse_id)) {
        $warehouseId = $info->warehouse_id;
    }
    if (empty($warehouseId)) {
        $warehouseId = '0';
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            store('<?= $itemType; ?>Items', JSON.stringify(<?= $add_items; ?>));
            store('<?= $itemType; ?>Warehouse', JSON.stringify(<?= $warehouseId; ?>));
        });
    </script>
    <?php
} else { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            remove('<?= $itemType; ?>Items');
            remove('<?= $itemType; ?>Warehouse');
        });
    </script>
<?php } ?>
<?php include_once 'assets/js/product.php'; ?>