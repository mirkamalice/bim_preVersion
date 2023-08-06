<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('151', 'created');
$edited = can_action('151', 'edited');
$deleted = can_action('151', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class=""><a href="<?= base_url('admin/supplier/') ?>"><?= lang('supplier') . ' ' . lang('list') ?></a>
        </li>
        <li class="active"><a
                href="<?= base_url('admin/supplier/create_supplier') ?>"><?= lang('new') . ' ' . lang('supplier') ?></a>
        </li>

    </ul>
    <div class="tab-content bg-white">

        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
            <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
                action="<?php echo base_url(); ?>admin/supplier/saved_supplier/<?php
                                                                                                                                                                                if (!empty($supplier_info)) {
                                                                                                                                                                                    echo $supplier_info->supplier_id;
                                                                                                                                                                                }
                                                                                                                                                                                ?>" method="post"
                class="form-horizontal  ">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('name') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" value="<?php
                                                                            if (!empty($supplier_info)) {
                                                                                echo $supplier_info->name;
                                                                            }
                                                                            ?>" name="name" required="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('mobile') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" data-parsley-type="number" value="<?php
                                                                                                        if (!empty($supplier_info)) {
                                                                                                            echo $supplier_info->mobile;
                                                                                                        }
                                                                                                        ?>"
                            name="mobile" required="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('phone') ?></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" data-parsley-type="number" value="<?php
                                                                                                        if (!empty($supplier_info)) {
                                                                                                            echo $supplier_info->phone;
                                                                                                        }
                                                                                                        ?>"
                            name="phone">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('email') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" value="<?php
                                                                            if (!empty($supplier_info)) {
                                                                                echo $supplier_info->email;
                                                                            }
                                                                            ?>" name="email" required="">
                    </div>
                </div>
                <!-- End discount Fields -->
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('address') ?> </label>
                    <div class="col-lg-5">
                        <textarea name="address" class="form-control"><?php
                                                                            if (!empty($supplier_info)) {
                                                                                echo $supplier_info->address;
                                                                            }
                                                                            ?></textarea>
                    </div>
                </div>
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('vat_number') ?> </label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" value="<?php
                                                                            if (!empty($supplier_info)) {
                                                                                echo $supplier_info->vat;
                                                                            }
                                                                            ?>" name="vat">
                    </div>
                </div>
                <?php
                    $permissionL = null;
                    if (!empty($supplier_info->permission)) {
                        $permissionL = $supplier_info->permission;
                    }
                    ?>
                <?= get_permission(3, 9, $permission_user, $permissionL, ''); ?>
                <?php
                    if (!empty($supplier_info)) {
                        $supplier_id = $supplier_info->supplier_id;
                    } else {
                        $supplier_id = null;
                    }
                    ?>
                <?= custom_form_Fields(19, $supplier_id); ?>
                <div class="btn-bottom-toolbar text-right">
                    <?php
                        if (!empty($supplier_info)) { ?>
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
        <?php } else { ?>
    </div>
    <?php } ?>

</div>