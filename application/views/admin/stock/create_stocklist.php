<?php echo message_box('success'); ?>
<?php echo message_box('error');
$created = can_action('81', 'created');
$edited = can_action('81', 'edited');
$deleted = can_action('81', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs" style="margin-top: 1px">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/stock/stock_list') ?>"><?= lang('all') . ' ' . lang('stock') ?></a>
            </li>
            <li class="<?= $active == 2 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/stock/create_stocklist') ?>"><?= lang('new') . ' ' . lang('stock') ?></a>
            </li>
        </ul>
        <div class="tab-content bg-white">

            <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task" style="position: relative;">
                <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data"
                    action="<?php echo base_url() ?>admin/stock/save_stock/<?php
                                                                                                                                                                    if (!empty($stock_info->item_history_id)) {
                                                                                                                                                                        echo $stock_info->item_history_id;
                                                                                                                                                                    }
                                                                                                                                                                    ?>" method="post"
                    class="form-horizontal form-groups-bordered">

                    <div class="form-group ">
                        <label class="control-label col-sm-3"><?= lang('stock_category') ?><span
                                class="required">*</span></label>
                        <div class="col-sm-5">

                            <select name="stock_sub_category_id" style="width: 100%" class="form-control select_box">
                                <option value=""><?= lang('select') . ' ' . lang('stock_category') ?></option>
                                <?php if (!empty($all_category_info)) : foreach ($all_category_info as $cate_name => $v_category_info) : ?>
                                <?php if (!empty($v_category_info)) :
                                                if (!empty($cate_name)) {
                                                    $cate_name = $cate_name;
                                                } else {
                                                    $cate_name = lang('undefined_category');
                                                }
                                            ?>
                                <optgroup label="<?php echo $cate_name; ?>">
                                    <?php foreach ($v_category_info as $sub_category) :
                                                        if (!empty($sub_category->stock_sub_category)) {
                                                    ?>
                                    <option value="<?php echo $sub_category->stock_sub_category_id; ?>"
                                        <?php
                                                                                                                                if (!empty($stock_info->stock_sub_category_id)) {
                                                                                                                                    echo $sub_category->stock_sub_category_id == $stock_info->stock_sub_category_id ? 'selected' : '';
                                                                                                                                }
                                                                                                                                ?>>
                                        <?php echo $sub_category->stock_sub_category ?></option>
                                    <?php
                                                        }
                                                    endforeach;
                                                    ?>
                                </optgroup>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="control-label col-sm-3 "><?= lang('buying_date') ?><span
                                class="required">*</span></label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input required type="text" class="form-control   datepicker" name="purchase_date"
                                    value="<?php
                                                                                                                                if (!empty($stock_info->purchase_date)) {
                                                                                                                                    echo $stock_info->purchase_date;
                                                                                                                                }
                                                                                                                                ?>"
                                    data-format="yyyy/mm/dd">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $direction = $this->session->userdata('direction');
                        if (!empty($direction) && $direction == 'rtl') {
                            $RTL = 'on';
                        } else {
                            $RTL = config_item('RTL');
                        }
                        ?>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('item_name') ?><span
                                class="required"> * </span></label>

                        <div class="col-sm-5">
                            <input required type="text" <?php
                                                            if (!empty($RTL)) { ?> dir="rtl" <?php }
                                                                                                ?> name="item_name"
                                class="form-control" placeholder="" id="query"
                                value="<?php
                                                                                                                                                                                    if (!empty($stock_info->item_name)) {
                                                                                                                                                                                        echo $stock_info->item_name;
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <?php if (!empty($from_history) || empty($stock_info)) { ?>
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('inventory') ?> <span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <input required type="text" data-parsley-type="number" name="inventory" placeholder=""
                                class="form-control"
                                value="<?php
                                                                                                                                                        if (!empty($stock_info->inventory)) {
                                                                                                                                                            echo $stock_info->inventory;
                                                                                                                                                        }
                                                                                                                                                        ?>">
                        </div>
                        <?php } elseif (!empty($stock_info)) { ?>
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('total_stock') ?> <span
                                class="required">*</span></label>

                        <div class="col-sm-5">
                            <input required type="text" name="inventory" readonly placeholder="" class="form-control"
                                value="<?php
                                                                                                                                        if (!empty($stock_info->total_stock)) {
                                                                                                                                            echo $stock_info->total_stock;
                                                                                                                                        }
                                                                                                                                        ?>">
                        </div>
                        <?php } ?>
                    </div>

                    <div class="btn-bottom-toolbar text-right">
                        <?php
                            if (!empty($stock_info)) { ?>
                        <button type="submit" id="i_submit"
                            class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                        <button type="button" onclick="goBack()"
                            class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                        <?php } else {
                            ?>
                        <button type="submit" id="i_submit" class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                        <?php }
                            ?>
                    </div>

                    <!-- Hidden input field-->
                    <input type="hidden" name="item_history_id" value="<?php
                                                                            if (!empty($stock_info->item_history_id)) {
                                                                                echo $stock_info->item_history_id;
                                                                            }
                                                                            ?>">
                </form>
            </div>
        </div>
        <?php } else { ?>
</div>
<?php } ?>
</div>
</div>