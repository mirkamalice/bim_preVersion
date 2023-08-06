<?= message_box('success');
$created = can_action('16', 'created');
$edited = can_action('16', 'edited');
$deleted = can_action('16', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">

            <li class=""><a href="<?= base_url('admin/invoice/tax_rates') ?>"><?= lang('tax_rates') ?></a>
            </li>
            <li class="active"><a href="<?= base_url('admin/invoice/createtax_rates') ?>"><?= lang('new_tax_rate') ?></a>
            </li>
        </ul>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->



            <div class="tab-pane active" id="new">
                <form method="post" data-parsley-validate="" novalidate="" action="<?= base_url() ?>admin/invoice/save_tax_rate/<?php
                                                                                                                                if (!empty($tax_rates_info)) {
                                                                                                                                    echo $tax_rates_info->tax_rates_id;
                                                                                                                                }
                                                                                                                                ?>" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?= lang('tax_rate_name') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-5">
                            <input type="text" class="form-control" required value="<?php
                                                                                    if (!empty($tax_rates_info)) {
                                                                                        echo $tax_rates_info->tax_rate_name;
                                                                                    }
                                                                                    ?>" name="tax_rate_name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?= lang('tax_rate_percent') ?> <span class="text-danger">*</span></label>
                        <div class="col-lg-5">
                            <input type="number" step="0.01" data-parsley-type="number" class="form-control" required value="<?php
                                                                                                                                if (!empty($tax_rates_info)) {
                                                                                                                                    echo $tax_rates_info->tax_rate_percent;
                                                                                                                                }
                                                                                                                                ?>" name="tax_rate_percent">
                        </div>
                    </div>
                    <?php
                    $permissionL = null;
                    if (!empty($tax_rates_info->permission)) {
                        $permissionL = $tax_rates_info->permission;
                    }
                    ?>
                    <?= get_permission(4, 8, $permission_user, $permissionL, ''); ?>
                    <div class="btn-bottom-toolbar text-right">
                        <?php
                        if (!empty($tax_rates_info)) { ?>
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                            <button type="button" onclick="goBack()" class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                        <?php } else {
                        ?>
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                        <?php }
                        ?>
                        <button type="submit" name="save" value="2" class="btn btn-sm btn-warning "><?php echo !empty($tax_rates_info->tax_rate_name) ? lang('update') . ' & ' . lang('add_more') : lang('save') . ' & ' . lang('add_more') ?></button>
                    </div>
                </form>
            <?php } else { ?>
            </div>
        <?php } ?>
        </div>
    </div>
    </div>