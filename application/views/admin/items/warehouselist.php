<div class="form-group">
    <label for="warehouse" class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('warehouse') ?>
        <span
                class="text-danger">*</span>
    </label>
    <div class="col-sm-5">
        <div class="input-group">
            <?php
            $usertype = profile();
            if ($usertype->role_id == 3) {
                $warehouseList = $this->admin_model->select_data('tbl_warehouse', 'warehouse_id', 'warehouse_name', array('status' => 'published', 'warehouse_id' => $this->session->userdata['warehouse_id']));
                $selected = ($this->session->userdata['warehouse_id']);
            } else {
                $warehouseList = $this->admin_model->select_data('tbl_warehouse', 'warehouse_id', 'warehouse_name', array('status' => 'published'));
                $selected = (!empty($warehouseID) ? $warehouseID : '');
            }
            echo form_dropdown('warehouse_id', $warehouseList, $selected, array('class' => 'form-control select_box ' . (!empty($warehouseID) ? $warehouseID : 'mwarehouse') . '', 'onchange' => 'getItemByWarehouse(this.value)', 'required' => true, 'data-live-search' => true, 'style' => 'width:100%'));
            echo '<input type=' . 'hidden' . ' name=' . 'warehouseId' . ' class=' . (!empty($warehouseID) ? $warehouseID : 'WarehouseValue') . ' value=' . $selected . '>';
            ?>
            <div class="input-group-addon" title="<?= lang('new') . ' ' . lang('warehouse') ?>" data-toggle="tooltip"
                 data-placement="top">
                <?php if ($usertype->role_id == 1) { ?>
                    <a data-toggle="modal" data-target="#myModal_lg"
                       href="<?= base_url() ?>admin/warehouse/create/0/from_warehouse_id"><i class="fa fa-plus"></i></a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>