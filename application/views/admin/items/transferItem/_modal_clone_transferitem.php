
<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= lang('clone') . ' ' . lang('invoice') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <?php echo form_open(base_url('admin/items/cloned_transferitem/' . $item_info->transfer_itemList_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= lang('select') . ' ' . lang('warehouse') ?> <span
                    class="text-danger">*</span>
            </label>
            <div class="col-lg-7">
                <select class="form-control select_box" style="width: 100%" name="warehouse_id" required>
                    <?php
                    if (!empty($all_warehouse)) {
                        foreach ($all_warehouse as $v_warehouse) {
                            if (!empty($item_info->warehouse_id)) {
                                $warehouse_id = $item_info->warehouse_id;
                            }
                            ?>
                            <option value="<?= $v_warehouse->warehouse_id ?>"
                                <?php
                                if (!empty($warehouse_id)) {
                                    echo $warehouse_id == $v_warehouse->warehouse_id ? 'selected' : null;
                                }
                                ?>
                            ><?= $v_warehouse->warehouse_name ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label
                class="col-lg-3 control-label"><?= lang('transfer') . ' ' . lang('date') ?></label>
            <div class="col-lg-7">
                <div class="input-group">
                    <input type="text" name="transfer_date"
                           class="form-control datepicker"
                           value="<?php
                           if (!empty($item_info->date_saved)) {
                               echo $item_info->date_saved;
                           } else {
                               echo date('Y-m-d H:i');
                           }
                           ?>"
                           data-date-format="<?= config_item('date_picker_format'); ?>">
                    <div class="input-group-addon">
                        <a href="#"><i class="fa fa-calendar"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close') ?></button>
            <button type="submit" class="btn btn-primary"><?= lang('clone') ?></button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
