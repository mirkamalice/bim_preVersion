<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class=""><a
                    href="<?= base_url('admin/warehouse/manage') ?>"><?= lang('manage') . ' ' . lang('warehouse') ?></a>
        </li>
        <li class="active"><a
                    href="<?= base_url('admin/warehouse/create') ?>"><?= lang('new') . ' ' . lang('warehouse') ?></a>
        </li>
    </ul>
    <div class="tab-content bg-white">
        <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="warehouse_form"
              action="<?= base_url('admin/warehouse/save_warehouse/' . (!empty($warehouse_info) ? $warehouse_info->warehouse_id : '0') . '/' . $inline); ?>"
              method="post" class="form-horizontal  <?= (!empty($inline) ? 'myInline' : '') ?> ">

            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('warehouse') . ' ' . lang('code') ?> <span
                            class="text-danger">*</span></label>
                <div class="col-lg-5">
                    <input type="text" class="form-control" value="<?php
                    if (!empty($warehouse_info)) {
                        echo $warehouse_info->warehouse_code;
                    }
                    ?>" name="warehouse_code" required="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('warehouse') . ' ' . lang('name') ?> <span
                            class="text-danger">*</span></label>
                <div class="col-lg-5">
                    <input type="text" class="form-control" value="<?php
                    if (!empty($warehouse_info)) {
                        echo $warehouse_info->warehouse_name;
                    }
                    ?>" name="warehouse_name" required="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('phone') ?> <span class="text-danger">*</span></label>
                <div class="col-lg-5">
                    <input type="text" class="form-control" value="<?php
                    if (!empty($warehouse_info)) {
                        echo $warehouse_info->phone;
                    }
                    ?>" name="phone" required="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('mobile') ?> <span class="text-danger">*</span></label>
                <div class="col-lg-5">
                    <input type="text" class="form-control" value="<?php
                    if (!empty($warehouse_info)) {
                        echo $warehouse_info->mobile;
                    }
                    ?>" name="mobile" required="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('email') ?> <span class="text-danger">*</span></label>
                <div class="col-lg-5">
                    <input type="text" class="form-control" value="<?php
                    if (!empty($warehouse_info)) {
                        echo $warehouse_info->email;
                    }
                    ?>" name="email" required="">
                </div>
            </div>
            <!-- End discount Fields -->
            <div class="form-group terms">
                <label class="col-lg-3 control-label"><?= lang('address') ?> </label>
                <div class="col-lg-5">
                    <textarea name="address" class="form-control"><?php
                        if (!empty($warehouse_info)) {
                            echo $warehouse_info->address;
                        }
                        ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"><?= lang('image') ?></label>
                <div class="col-lg-9">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 210px;">
                            <?php if (!empty($warehouse_info->image)) : ?>
                                <img src="<?php echo base_url($warehouse_info->image); ?>">
                            <?php else : ?>
                                <img src="<?= base_url('uploads/default_avatar.jpg') ?>"
                                     alt="Please Connect Your Internet">
                            <?php endif; ?>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                        <div>
                            <span class="btn btn-default btn-file">
                                <span class="fileinput-new">
                                    <input type="file" name="image" value="upload"
                                           data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                    <span class="fileinput-exists"><?= lang('change') ?></span>
                                </span>
                                <a href="#" class="btn btn-default fileinput-exists"
                                   data-dismiss="fileinput"><?= lang('remove') ?></a>

                        </div>

                        <div id="valid_msg" style="color: #e11221"></div>

                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="field-1" class="col-sm-3 control-label"><?= lang('status') ?></label>

                <div class="col-sm-8">
                    <div class="col-sm-4 row">
                        <div class="checkbox-inline c-checkbox">
                            <label>
                                <input
                                    <?= empty($warehouse_info) ? 'checked' : '' ?>
                                    <?= (!empty($warehouse_info->status) && $warehouse_info->status == 'published' ? 'checked' : ''); ?>
                                        class="select_one" type="checkbox" name="status" value="published">
                                <span class="fa fa-check"></span> <?= lang('published') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="checkbox-inline c-checkbox">
                            <label>
                                <input <?= (!empty($warehouse_info->status) && $warehouse_info->status == 'unpublished' ? 'checked' : ''); ?>
                                        class="select_one" type="checkbox" name="status" value="unpublished">
                                <span class="fa fa-check"></span> <?= lang('unpublished') ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if (!empty($warehouse_info)) {
                $warehouse_id = $warehouse_info->warehouse_id;
                $permissionL = $warehouse_info->permission;
            } else {
                $warehouse_id = null;
                $permissionL = null;
            }
            if (!empty($inline)) {
                echo get_permission_modal(3, 9, $permission_user, $permissionL, '');
            } else {
                echo get_permission(3, 9, $permission_user, $permissionL, '');
            }
            ?>


            <div class="btn-bottom-toolbar text-right">
                <?php
                if (!empty($warehouse_info)) { ?>
                    <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                    <button type="button" onclick="goBack()"
                            class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                <?php } else {
                    ?>
                    <button type="submit" class="btn btn-sm btn-primary"><?= lang('create') ?></button>
                <?php }
                ?>
            </div>
        </form>
    </div>
</div>
