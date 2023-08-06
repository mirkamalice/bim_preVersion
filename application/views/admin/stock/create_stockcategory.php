<?php echo message_box('success'); ?>
<?php echo message_box('error');
$created = can_action('76', 'created');
$edited = can_action('76', 'edited');
$deleted = can_action('76', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs" style="margin-top: 1px">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/stock/stock_category') ?>"><?= lang('all') . ' ' . lang('stock_category') ?></a>
            </li>
            <li class="<?= $active == 2 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/stock/create_stockcategory') ?>"><?= lang('new') . ' ' . lang('stock_category') ?></a>
            </li>
        </ul>
        <div class="tab-content bg-white">

            <!-- Add Stock Category tab Starts -->
            <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task" style="position: relative;">
                <form data-parsley-validate="" novalidate=""
                    action="<?php echo base_url() ?>admin/stock/save_stock_category/<?php
                                                                                                                                    if (!empty($stock_category_info->stock_category_id)) {
                                                                                                                                        echo $stock_category_info->stock_category_id;
                                                                                                                                    }
                                                                                                                                    ?>" method="post"
                    class="form-horizontal form-groups-bordered">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><?= lang('select') . ' ' . lang('sub_category') ?>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-lg-8">
                                    <select class="form-control select_box" style="width: 100%" name="stock_category_id"
                                        id="new_departments">
                                        <option value=""><?= lang('new_category') ?></option>

                                        <?php $all_stock_category = $this->db->get('tbl_stock_category')->result();
                                            if (!empty($all_stock_category)) {
                                                foreach ($all_stock_category as $v_stock_category) { ?>
                                        <option
                                            <?= (!empty($category_info->stock_category_id) && $category_info->stock_category_id == $v_stock_category->stock_category_id ? 'selected' : null) ?>
                                            value="<?= $v_stock_category->stock_category_id ?>">
                                            <?php
                                                        if (!empty($v_stock_category->stock_category)) {
                                                            $stock_category = $v_stock_category->stock_category;
                                                        } else {
                                                            $stock_category = lang('undefined_category');
                                                        }
                                                        echo $stock_category;
                                                        ?>
                                        </option>
                                        <?php
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group new_departments"
                                style="display: <?= (!empty($category_info->stock_category_id) ? 'none' : 'block') ?>">
                                <label class="col-sm-4 control-label"><?= lang('new_category') ?></label>
                                <div class="col-sm-8">
                                    <input <?= (!empty($category_info->stock_category_id) ? 'disabled' : '') ?>
                                        type="text" name="stock_category" class="form-control new_departments"
                                        value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class=" col-sm-4 control-label"><?= lang('sub_category') ?><span
                                        class="required">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" name="stock_sub_category" required class="form-control"
                                        value="<?php if (!empty($sub_category_info->stock_sub_category)) echo $sub_category_info->stock_sub_category; ?>" />
                                </div>
                            </div>
                            <input type="hidden" name="stock_sub_category_id" class="form-control"
                                value="<?php if (!empty($sub_category_info->stock_sub_category_id)) echo $sub_category_info->stock_sub_category_id; ?>" />

                            <div class="btn-bottom-toolbar text-right">
                                <?php
                                    if (!empty($sub_category_info)) { ?>
                                <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                <button type="button" onclick="goBack()"
                                    class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                                <?php } else {
                                    ?>
                                <button type="submit" class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                                <?php }
                                    ?>
                            </div>

                        </div>


                    </div>
                </form>
            </div>
            <?php } else { ?>
        </div>
        <?php } ?>
</div>
</div>