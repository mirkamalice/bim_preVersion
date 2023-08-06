<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('39', 'created');
$edited = can_action('39', 'edited');
$deleted = can_action('39', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">

        <li class=""><a href="<?= base_url('admin/items/items_list') ?>"><?= lang('all_items') ?></a>
        </li>
        <li class=""><a href="<?= base_url('admin/items/new_items') ?>"><?= lang('new_items') ?></a>
        </li>
        <li class=""><a
                href="<?= base_url('admin/items/newitems_group') ?>"><?= lang('group') . ' ' . lang('list') ?></a>
        </li>
        <li class="active"><a
                href="<?= base_url('admin/items/items_manufacturerlist') ?>"><?= lang('manufacturer') . ' ' . lang('list') ?></a>
        </li>


        <li><a class="import" href="<?= base_url() ?>admin/items/import"><?= lang('import') . ' ' . lang('items') ?></a>
        </li>
    </ul>
    <style type="text/css">
    .custom-bulk-button {
        display: initial;
    }
    </style>
    <div class="tab-content bg-white">
        <div class="tab-pane active" id="manufacturer">

            <div class="table-responsive">
                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th><?= lang('manufacturer') ?></th>
                            <th><?= lang('description') ?></th>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                            <th><?= lang('action') ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $all_manufacturerInfo = get_result('tbl_manufacturer');
                            if (!empty($all_manufacturerInfo)) {
                                foreach ($all_manufacturerInfo as $manufacturerInfo) {
                            ?>
                        <tr id="table_manufacturer_<?= $manufacturerInfo->manufacturer_id ?>">
                            <td><?php
                                            $id = $this->uri->segment(5);
                                            if (!empty($id) && $id == $manufacturerInfo->manufacturer_id) { ?>
                                <form method="post"
                                    action="<?= base_url() ?>admin/items/saved_manufacturer/<?php
                                                                                                                            if (!empty($manufacturer_info)) {
                                                                                                                                echo $manufacturer_info->manufacturer_id;
                                                                                                                            }
                                                                                                                            ?>"
                                    class="form-horizontal">
                                    <input type="text" name="manufacturer" value="<?php
                                                                                                    if (!empty($manufacturer_info)) {
                                                                                                        echo $manufacturer_info->manufacturer;
                                                                                                    }
                                                                                                    ?>"
                                        class="form-control"
                                        placeholder="<?= lang('enter') . ' ' . lang('group') . ' ' . lang('name') ?>"
                                        required>
                                    <?php } else {
                                                echo $manufacturerInfo->manufacturer;
                                            }
                                                ?>
                            </td>
                            <td><?php
                                            $id = $this->uri->segment(5);
                                            if (!empty($id) && $id == $manufacturerInfo->manufacturer_id) { ?>
                                <textarea name="description" rows="1"
                                    class="form-control"><?php
                                                                                                            if (!empty($manufacturer_info)) {
                                                                                                                echo $manufacturer_info->description;
                                                                                                            }
                                                                                                            ?></textarea>
                                <?php } else {
                                                echo $manufacturerInfo->description;
                                            }
                                            ?>
                            </td>
                            <td>
                                <?php
                                            $id = $this->uri->segment(5);
                                            if (!empty($id) && $id == $manufacturerInfo->manufacturer_id) { ?>
                                <?= btn_update() ?>
                                </form>
                                <?= btn_cancel('admin/items/items_manufacturerlist/manufacturer/') ?>
                                <?php } else { ?>
                                <?php if (!empty($edited)) { ?>
                                <?= btn_edit('admin/items/items_manufacturerlist/manufacturer/' . $manufacturerInfo->manufacturer_id) ?>
                                <?php if (!empty($deleted)) { ?>
                                <?php echo ajax_anchor(base_url("admin/items/delete_manufacturer/" . $manufacturerInfo->manufacturer_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_items_group_" . $manufacturerInfo->manufacturer_id)); ?>
                            </td>
                            <?php }
                                                }
                                            } ?>
                        </tr>
                        <?php }
                            } ?>
                        <form role="form" enctype="multipart/form-data" id="form"
                            action="<?php echo base_url(); ?>admin/items/saved_manufacturer" method="post"
                            class="form-horizontal  ">
                            <tr>
                                <td><input required type="text" name="manufacturer" class="form-control"
                                        placeholder="<?= lang('enter') . ' ' . lang('manufacturer') . ' ' . lang('name') ?>">
                                </td>
                                <td>
                                    <textarea name="description" rows="1" class="form-control"></textarea>
                                </td>
                                <td><?= btn_add() ?></td>
                            </tr>
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
</div>