<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('36', 'created');
$edited = can_action('36', 'edited');
$deleted = can_action('36', 'deleted');
if (!empty($created) || !empty($edited)) {
    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
?>


<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/account/manage_account') ?>"><?= lang('manage_account') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/account/create_account') ?>"><?= lang('new_account') ?></a></li>
    </ul>
    <div class="tab-content bg-white">

        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
            <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
                action="<?php echo base_url(); ?>admin/account/save_account/<?php
                                                                                                                                                                                if (!empty($account_info)) {
                                                                                                                                                                                    echo $account_info->account_id;
                                                                                                                                                                                }
                                                                                                                                                                                ?>" method="post"
                class="form-horizontal  ">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('account_name') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" value="<?php
                                                                            if (!empty($account_info)) {
                                                                                echo $account_info->account_name;
                                                                            }
                                                                            ?>" name="account_name" required="">
                    </div>

                </div>
                <!-- End discount Fields -->
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('description') ?> </label>
                    <div class="col-lg-5">
                        <textarea name="description" class="form-control"><?php
                                                                                if (!empty($account_info)) {
                                                                                    echo $account_info->description;
                                                                                }
                                                                                ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('initial_balance') ?> <span
                            class="text-danger">*</span></label>
                    <div class="col-lg-5">
                        <input type="text" data-parsley-type="number" class="form-control" value="<?php
                                                                                                        if (!empty($account_info)) {
                                                                                                            echo $account_info->balance;
                                                                                                        }
                                                                                                        ?>"
                            name="balance" required="">
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('account_number') ?></label>
                    <div class="col-lg-5">
                        <input type="text" data-parsley-type="number" class="form-control" value="<?php
                                                                                                        if (!empty($account_info)) {
                                                                                                            echo $account_info->account_number;
                                                                                                        }
                                                                                                        ?>"
                            name="account_number">
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('contact_person') ?></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" value="<?php
                                                                            if (!empty($account_info)) {
                                                                                echo $account_info->contact_person;
                                                                            }
                                                                            ?>" name="contact_person">
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('phone') ?></label>
                    <div class="col-lg-5">
                        <input type="text" class="form-control" value="<?php
                                                                            if (!empty($account_info)) {
                                                                                echo $account_info->contact_phone;
                                                                            }
                                                                            ?>" name="contact_phone">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('bank_details') ?></label>
                    <div class="col-lg-5">
                        <textarea name="bank_details" class="form-control"><?php
                                                                                if (!empty($account_info)) {
                                                                                    echo $account_info->bank_details;
                                                                                }
                                                                                ?></textarea>
                    </div>
                </div>
                <?php
                    if (!empty($account_info)) {
                        $account_id = $account_info->account_id;
                        $permissionL = $account_info->permission;
                    } else {
                        $account_id = null;
                        $permissionL = null;
                    }
                    ?>
                <?= custom_form_Fields(21, $account_id); ?>

                <?= get_permission(3, 9, $permission_user, $permissionL, ''); ?>

                <div class="btn-bottom-toolbar text-right">
                    <?php
                        if (!empty($account_info)) { ?>
                    <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                    <button type="button" onclick="goBack()"
                        class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                    <?php } else {
                        ?>
                    <button type="submit" class="btn btn-sm btn-primary"><?= lang('create_acount') ?></button>
                    <?php }
                        ?>
                </div>
            </form>
        </div>
        <?php } else { ?>
    </div>
    <?php } ?>
</div>
</div>