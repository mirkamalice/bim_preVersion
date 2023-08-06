<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('150', 'created');
$edited = can_action('150', 'edited');
$deleted = can_action('150', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
<?php include_once 'assets/admin-ajax.php'; ?>
<?php include_once 'assets/js/sales.php'; ?>

<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/return_stock') ?>"><?= lang('manage') . ' ' . lang('return_stock') ?></a>
        </li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/return_stock/create_returnstock') ?>"><?= lang('new') . ' ' . lang('return_stock') ?></a>
        </li>
    </ul>
    <div class="tab-content bg-white">
        <?php
            if (!empty($return_stock_info)) {
                $return_stock_id = $return_stock_info->return_stock_id;
            } else {
                $return_stock_id = null;
            }
            ?>
        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
            <?php echo form_open(base_url('admin/return_stock/save_return_stock/' . $return_stock_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
            <div class="mb-lg return_stock accounting-template">
                <div class="row">
                    <div class="col-sm-6 col-xs-12 br pv">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('reference_no') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <input type="text" class="form-control" value="<?php
                                                                                        if (!empty($return_stock_info)) {
                                                                                            echo $return_stock_info->reference_no;
                                                                                        } else {
                                                                                            if (empty(config_item('return_stock_number_format'))) {
                                                                                                echo config_item('return_stock_prefix');
                                                                                            }
                                                                                            if (config_item('increment_return_stock_number') == 'FALSE') {
                                                                                                $this->load->helper('string');
                                                                                                echo random_string('nozero', 6);
                                                                                            } else {
                                                                                                echo $this->return_stock_model->generate_return_stock_number();
                                                                                            }
                                                                                        }
                                                                                        ?>" name="reference_no">
                                </div>
                            </div>
                            <?php if (!empty($return_stock_info->module)) {
                                    if ($return_stock_info->module == 'supplier') {
                                        $supplier_id = $return_stock_info->module_id;
                                    }
                                    if ($return_stock_info->module == 'client') {
                                        $client_id = $return_stock_info->module_id;
                                    }
                                } elseif (!empty($module)) {
                                    if ($module == 'supplier') {
                                        $supplier_id = $module_id;
                                    }
                                    if ($module == 'client') {
                                        $client_id = $module_id;
                                    }
                                ?>
                            <input type="hidden" name="un_module" required class="form-control"
                                value="<?php echo $module ?>" />
                            <input type="hidden" name="un_module_id" required class="form-control"
                                value="<?php echo $module_id ?>" />
                            <?php }
                                ?>
                            <div class="form-group" id="border-none">
                                <label for="field-1" class="col-sm-3 control-label"><?= lang('related_to') ?>
                                </label>
                                <div class="col-sm-7">
                                    <select name="module" class="form-control select_box" id="check_related" required
                                        onchange="get_related_moduleName(this.value,true)" style="width: 100%">
                                        <option value=""> <?= lang('none') ?> </option>
                                        <option value="supplier" <?= (!empty($supplier_id) ? 'selected' : '') ?>>
                                            <?= lang('supplier') ?> </option>
                                        <option value="client" <?= (!empty($client_id) ? 'selected' : '') ?>>
                                            <?= lang('client') ?> </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="related_to">

                            </div>

                            <div id="related_result">

                            </div>
                            <?php if (!empty($supplier_id)) : ?>
                            <div class="form-group <?= $supplier_id ? 'leads_module' : 'company' ?>" id="border-none">
                                <label for="field-1"
                                    class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('leads') ?>
                                    <span class="required">*</span></label>
                                <div class="col-sm-7">
                                    <select name="supplier_id" style="width: 100%"
                                        class="select_box <?= $supplier_id ? 'leads_module' : 'company' ?>"
                                        required="1">
                                        <?php
                                                if (!empty($all_supplier)) {
                                                    foreach ($all_supplier as $v_supplier) {
                                                ?>
                                        <option value="<?= $v_supplier->supplier_id ?>" <?php
                                                                                                        if (!empty($supplier_id)) {
                                                                                                            echo $v_supplier->supplier_id == $supplier_id ? 'selected' : '';
                                                                                                        }
                                                                                                        ?>>
                                            <?= $v_supplier->name ?></option>
                                        <?php
                                                    }
                                                }
                                                ?>
                                    </select>
                                </div>
                            </div>

                            <?php endif ?>
                            <?php if (!empty($client_id)) : ?>
                            <div class="form-group <?= $client_id ? 'client_module' : 'company' ?>" id="border-none">
                                <label for="field-1"
                                    class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('client') ?>
                                    <span class="required">*</span></label>
                                <div class="col-sm-7">
                                    <select name="client_id" style="width: 100%"
                                        class="select_box <?= $client_id ? 'client_module' : 'company' ?>" required="1">
                                        <?php
                                                $all_client = get_result('tbl_client');
                                                if (!empty($all_client)) {
                                                    foreach ($all_client as $v_client) {
                                                ?>
                                        <option value="<?= $v_client->client_id ?>" <?php
                                                                                                    if (!empty($client_id)) {
                                                                                                        echo $client_id == $v_client->client_id ? 'selected' : '';
                                                                                                    }
                                                                                                    ?>>
                                            <?= $v_client->name ?></option>
                                        <?php
                                                    }
                                                }
                                                ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif ?>
                            <?php $this->load->view('admin/items/warehouselist') ?>
                            <div class="form-group">
                                <label
                                    class="col-lg-3 control-label"><?= lang('return_stock') . ' ' . lang('date') ?></label>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <input type="text" name="return_stock_date" class="form-control datepicker"
                                            value="<?php
                                                                                                                                if (!empty($return_stock_info->return_stock_date)) {
                                                                                                                                    echo $return_stock_info->return_stock_date;
                                                                                                                                } else {
                                                                                                                                    echo date('Y-m-d');
                                                                                                                                }
                                                                                                                                ?>"
                                            data-date-format="<?= config_item('date_picker_format'); ?>">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('due_date') ?></label>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <input type="text" name="due_date" class="form-control datepicker"
                                            value="<?php
                                                                                                                        if (!empty($return_stock_info->due_date)) {
                                                                                                                            echo $return_stock_info->due_date;
                                                                                                                        } else {
                                                                                                                            echo date('Y-m-d');
                                                                                                                        }
                                                                                                                        ?>"
                                            data-date-format="<?= config_item('date_picker_format'); ?>">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="fa fa-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $permissionL = null;
                                if (!empty($return_stock_info->permission)) {
                                    $permissionL = $return_stock_info->permission;
                                }
                                ?>
                            <?= get_permission(3, 7, $permission_user, $permissionL, ''); ?>


                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 br pv">

                        <div class="row">
                            <div class="form-group">
                                <label for="field-1"
                                    class="col-sm-4 control-label"><?= lang('sales') . ' ' . lang('agent') ?></label>
                                <div class="col-sm-7">
                                    <select class="form-control select_box" required style="width: 100%" name="user_id">
                                        <option value="">
                                            <?= lang('select') . ' ' . lang('sales') . ' ' . lang('agent') ?>
                                        </option>
                                        <?php
                                            $all_user = get_staff_details();
                                            if (!empty($all_user)) {
                                                foreach ($all_user as $v_user) {
                                                    $profile_info = $this->db->where('user_id', $v_user->user_id)->get('tbl_account_details')->row();
                                                    if (!empty($profile_info)) {
                                            ?>
                                        <option value="<?= $v_user->user_id ?>" <?php
                                                                                                if (!empty($return_stock_info->user_id)) {
                                                                                                    echo $return_stock_info->user_id == $v_user->user_id ? 'selected' : null;
                                                                                                } else {
                                                                                                    echo $this->session->userdata('user_id') == $v_user->user_id ? 'selected' : null;
                                                                                                }
                                                                                                ?>>
                                            <?= $profile_info->fullname ?></option>
                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="discount_type"
                                    class="control-label col-sm-4"><?= lang('update_stock') ?></label>
                                <div class="col-sm-7">
                                    <label class="radio-inline c-radio">
                                        <input type="radio" value="Yes" name="update_stock" <?php if (isset($return_stock_info) && $return_stock_info->update_stock == 'Yes') {
                                                                                                    echo 'checked';
                                                                                                } elseif (empty($return_stock_info)) {
                                                                                                    echo 'checked';
                                                                                                } ?>>
                                        <span class="fa fa-circle"></span><?php echo lang('yes'); ?>
                                    </label>
                                    <label class="radio-inline c-radio">
                                        <input type="radio" value="No" name="update_stock" <?php if (isset($return_stock_info) && $return_stock_info->update_stock == 'No') {
                                                                                                    echo 'checked';
                                                                                                } ?>>
                                        <span class="fa fa-circle"></span><?php echo lang('no'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="discount_type"
                                    class="control-label col-sm-4"><?= lang('discount_type') ?></label>
                                <div class="col-sm-7">
                                    <select name="discount_type" class="selectpicker" data-width="100%">
                                        <option value="" selected><?php echo lang('no') . ' ' . lang('discount'); ?>
                                        </option>
                                        <option value="before_tax" <?php
                                                                        if (isset($return_stock_info)) {
                                                                            if ($return_stock_info->discount_type == 'before_tax') {
                                                                                echo 'selected';
                                                                            }
                                                                        } ?>><?php echo lang('before_tax'); ?>
                                        </option>
                                        <option value="after_tax" <?php if (isset($return_stock_info)) {
                                                                            if ($return_stock_info->discount_type == 'after_tax') {
                                                                                echo 'selected';
                                                                            }
                                                                        } ?>><?php echo lang('after_tax'); ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-1 control-label"><?= lang('notes') ?> </label>
                                <div class="col-lg-11 row">
                                    <textarea name="notes" class="textarea"><?php
                                                                                if (!empty($return_stock_info)) {
                                                                                    echo $return_stock_info->notes;
                                                                                } else {
                                                                                    echo $this->config->item('return_stock_terms');
                                                                                }
                                                                                ?></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <style type="text/css">
            .dropdown-menu>li>a {
                white-space: normal;
            }

            .dragger {
                background: url(<?= base_url() ?>assets/img/dragger.png) 10px 32px no-repeat;
                cursor: pointer;
            }

            <?php if ( !empty($return_stock_info)) {
                ?>.dragger {
                    background: url(<?= base_url() ?>assets/img/dragger.png) 10px 32px no-repeat;
                    cursor: pointer;
                }

                <?php
            }

            ?>.input-transparent {
                box-shadow: none;
                outline: 0;
                border: 0 !important;
                background: 0 0;
                padding: 3px;
            }
            </style>

            <?php

                $pdata['itemType'] = 'returnstock';
                if (!empty($return_stock_info)) {
                    $pdata['add_items'] = $this->return_stock_model->ordered_items_by_id($return_stock_info->return_stock_id, true);
                    $pdata['info'] = $return_stock_info;

                }
                $this->load->view('admin/items/selectItem', $pdata); ?>
            <?php echo form_close(); ?>
        </div>
        <?php } else { ?>
    </div>
    <?php } ?>