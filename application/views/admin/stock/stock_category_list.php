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
            <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_list" style="position: relative;">
                <?php } else { ?>
                <div class="panel panel-custom">
                    <header class="panel-heading ">
                        <div class="panel-title"><strong><?= lang('all') . ' ' . lang('stock_category') ?></strong>
                        </div>
                    </header>
                    <?php } ?>
                    <!-- NESTED-->
                    <div class="box" style="" data-collapsed="0">
                        <div class="box-body">
                            <!-- Table -->
                            <div class="row">

                                <?php if (!empty($all_stock_category_info)) : foreach ($all_stock_category_info as $akey => $v_stock_category_info) : ?>
                                <?php if (!empty($v_stock_category_info)) :
                                            if (!empty($stock_category_info[$akey]->stock_category)) {
                                                $category = $stock_category_info[$akey]->stock_category;
                                            } else {
                                                $category = lang('undefined_category');
                                            }
                                        ?>
                                <div class="col-sm-6">
                                    <div class="box-heading">
                                        <div class="box-title">
                                            <h4><?php echo $category ?>
                                                <div class="pull-right">
                                                    <?php if (!empty($edited)) { ?>
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="<?= lang('edit') ?>">
                                                        <a href="<?= base_url() ?>admin/stock/edit_stock_category/<?= $stock_category_info[$akey]->stock_category_id ?>"
                                                            class="btn btn-primary btn-xs" data-toggle="modal"
                                                            data-placement="top" data-target="#myModal"><span
                                                                class="fa fa-pencil-square-o"></span></a>
                                                    </span>
                                                    <?php }
                                                                if (!empty($deleted)) { ?>
                                                    <a data-original-title="<?= lang('delete') ?>"
                                                        href="<?php echo base_url() ?>admin/stock/delete_stock_category/<?php echo $stock_category_info[$akey]->stock_category_id; ?>"
                                                        class="btn btn-danger btn-xs" title="" data-toggle="tooltip"
                                                        data-placement="top"
                                                        onclick="return confirm('<?= lang("alert_delete_category") ?>');"><i
                                                            class="fa fa-trash-o"></i></a>
                                                    <?php } ?>
                                                </div>
                                            </h4>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <td class="text-bold col-sm-1">#</td>
                                                <td class="text-bold"><?= lang('stock_sub_category') ?></td>
                                                <?php if (!empty($edited) && !empty($deleted)) { ?>
                                                <td class="text-bold col-sm-2"><?= lang('action') ?></td>
                                                <?php } ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($v_stock_category_info as $key => $v_stock_category) :
                                                            if (!empty($v_stock_category->stock_sub_category)) {
                                                        ?>

                                            <tr>
                                                <td><?php echo $key + 1 ?></td>
                                                <td><?php echo $v_stock_category->stock_sub_category ?></td>
                                                <?php if (!empty($edited) && !empty($deleted)) { ?>
                                                <td>
                                                    <?php if (!empty($edited)) { ?>
                                                    <?php echo btn_edit('admin/stock/create_stockcategory/' . $stock_category_info[$akey]->stock_category_id . '/' . $v_stock_category->stock_sub_category_id); ?>
                                                    <?php }
                                                                            if (!empty($deleted)) { ?>
                                                    <?php echo btn_delete('admin/stock/delete_stock_sub_category/' . $v_stock_category->stock_sub_category_id); ?>
                                                    <?php } ?>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                                            } else {
                                                            ?>
                                            <tr>
                                                <td colspan="3"><?= lang('nothing_to_display') ?></td>
                                            <tr></tr>
                                            <?php };
                                                        endforeach;
                                                        ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>