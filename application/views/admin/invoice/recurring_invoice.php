<form name="myform" role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/invoice/save_invoice/<?php
                                                                                                                                                                            if (!empty($invoice_info)) {
                                                                                                                                                                                echo $invoice_info->invoices_id;
                                                                                                                                                                            }
                                                                                                                                                                            ?>" method="post" class="form-horizontal  ">
    <div class="<?php if (!isset($invoice_info) || (isset($invoice_info) && count(array($invoices_to_merge)) == 0)) {
                    echo ' hide';
                } ?>" id="invoice_top_info">
        <div class="panel-body">
            <div class="row">
                <div id="merge" class="col-md-8">
                    <?php if (isset($invoice_info)) {
                        $this->load->view('admin/invoice/merge_invoice', array('invoices_to_merge' => $invoices_to_merge));
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
    <?php include_once 'assets/admin-ajax.php'; ?>
    <?php include_once 'assets/js/sales.php'; ?>
    <?= message_box('success'); ?>
    <?= message_box('error');
    $created = can_action('51', 'created');
    $edited = can_action('51', 'edited');
    $deleted = can_action('51', 'deleted');
    if (!empty($created) || !empty($edited)) {
    ?>

    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="active"><a
                    href="<?= base_url('admin/invoice/recurring_invoice') ?>"><?= lang('recurring_invoice') ?></a>
            </li>
            <li class=""><a
                    href="<?= base_url('admin/invoice/createrecuringinvoice') ?>"><?= lang('create_invoice') ?></a>
            </li>


        </ul>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->
            <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                <?php } else { ?>
                    <div class="panel panel-custom">
                        <header class="panel-heading ">
                            <div class="panel-title"><strong><?= lang('recurring_invoice') ?></strong></div>
                        </header>
                    <?php } ?>
                    <div class="table-responsive">
                        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?= lang('invoice') ?></th>
                                    <th class="col-date"><?= lang('due_date') ?></th>
                                    <th><?= lang('client_name') ?></th>
                                    <th class="col-currency"><?= lang('due_amount') ?></th>
                                    <th><?= lang('status') ?></th>
                                    <th><?= lang('tags') ?></th>
                                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                                        <th class="hidden-print"><?= lang('action') ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <script type="text/javascript">
                                    $(document).ready(function() {
                                        list = base_url + "admin/invoice/recurringinvoiceList";
                                    });
                                </script>

                            </tbody>
                        </table>
                    </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                            <?php
                            if (!empty($invoice_info)) {
                                $client_info = $this->invoice_model->check_by(array('client_id' => $invoice_info->client_id), 'tbl_client');
                                if (!empty($client_info)) {
                                    $client_lang = $client_info->language;
                                    $currency = $this->invoice_model->client_currency_symbol($invoice_info->client_id);
                                } else {
                                    $client_lang = 'english';
                                    $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                }
                            } else {
                                $client_lang = 'english';
                                $currency = $this->invoice_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                            }
                            unset($this->lang->is_loaded[5]);
                            $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
                            ?>

                            <div class="row mb-lg invoice accounting-template">
                                <div class="col-sm-6 col-xs-12 br pv">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('reference_no') ?> <span class="text-danger">*</span></label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" value="<?php
                                                                                                if (!empty($invoice_info)) {
                                                                                                    echo $invoice_info->reference_no;
                                                                                                } else {
                                                                                                    if (empty(config_item('invoice_number_format'))) {
                                                                                                        echo config_item('invoice_prefix');
                                                                                                    }
                                                                                                    if (config_item('increment_invoice_number') == 'FALSE') {
                                                                                                        $this->load->helper('string');
                                                                                                        echo random_string('nozero', 6);
                                                                                                    } else {
                                                                                                        echo $this->invoice_model->generate_invoice_number();
                                                                                                    }
                                                                                                }
                                                                                                ?>" name="reference_no">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('recur_frequency') ?> </label>
                                            <div class="col-lg-4">
                                                <select name="recuring_frequency" id="recuring_frequency" class="form-control">
                                                    <option value="none"><?= lang('none') ?></option>
                                                    <option value="7D"><?= lang('week') ?></option>
                                                    <option value="1M"><?= lang('month') ?></option>
                                                    <option value="3M"><?= lang('quarter') ?></option>
                                                    <option value="6M"><?= lang('six_months') ?></option>
                                                    <option value="1Y"><?= lang('1year') ?></option>
                                                    <option value="2Y"><?= lang('2year') ?></option>
                                                    <option value="3Y"><?= lang('3year') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('start_date') ?></label>
                                            <div class="col-lg-7">
                                                <?php
                                                if (!empty($invoice_info) && $invoice_info->recurring == 'Yes') {
                                                    $recur_start_date = date('Y-m-d', strtotime($invoice_info->recur_start_date));
                                                    $recur_end_date = date('Y-m-d', strtotime($invoice_info->recur_end_date));
                                                } else {
                                                    $recur_start_date = date('Y-m-d');
                                                    $recur_end_date = date('Y-m-d');
                                                }
                                                ?>
                                                <div class="input-group">
                                                    <input class="form-control start_date" type="text" value="<?= $recur_start_date; ?>" name="recur_start_date" data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('end_date') ?></label>
                                            <div class="col-lg-7">
                                                <div class="input-group">
                                                    <input class="form-control end_date" type="text" value="<?= $recur_end_date; ?>" name="recur_end_date" data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="f_client_id">
                                            <div class="form-group">
                                                <label class="col-lg-3 control-label"><?= lang('client') ?> <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-7">
                                                    <div class="input-group">
                                                        <select class="form-control select_box" required style="width: 100%" name="client_id" onchange="get_project_by_id(this.value)">
                                                            <option value=""><?= lang('select') . ' ' . lang('client') ?>
                                                            </option>
                                                            <?php
                                                            if (!empty($all_client)) {
                                                                foreach ($all_client as $v_client) {
                                                                    if (!empty($project_info->client_id)) {
                                                                        $client_id = $project_info->client_id;
                                                                    } elseif (!empty($invoice_info->client_id)) {
                                                                        $client_id = $invoice_info->client_id;
                                                                    }
                                                            ?>
                                                                    <option value="<?= $v_client->client_id ?>" <?php
                                                                                                                if (!empty($client_id)) {
                                                                                                                    echo $client_id == $v_client->client_id ? 'selected' : null;
                                                                                                                }
                                                                                                                ?>>
                                                                        <?= ucfirst($v_client->name) ?></option>
                                                            <?php
                                                                }
                                                            }
                                                            $acreated = can_action('4', 'created');
                                                            ?>
                                                        </select>
                                                        <?php if (!empty($acreated)) { ?>
                                                            <div class="input-group-addon" title="<?= lang('new') . ' ' . lang('client') ?>" data-toggle="tooltip" data-placement="top">
                                                                <a data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/client/new_client"><i class="fa fa-plus"></i></a>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('project') ?></label>
                                            <div class="col-lg-7">
                                                <select class="form-control" style="width: 100%" name="project_id" id="client_project">
                                                    <option value=""><?= lang('none') ?></option>
                                                    <?php

                                                    if (!empty($client_id)) {

                                                        if (!empty($project_info->project_id)) {
                                                            $project_id = $project_info->project_id;
                                                        } elseif ($invoice_info->project_id) {
                                                            $project_id = $invoice_info->project_id;
                                                        }
                                                        $all_project = $this->db->where('client_id', $client_id)->get('tbl_project')->result();
                                                        if (!empty($all_project)) {
                                                            foreach ($all_project as $v_project) {
                                                    ?>
                                                                <option value="<?= $v_project->project_id ?>" <?php
                                                                                                                if (!empty($project_id)) {
                                                                                                                    echo $v_project->project_id == $project_id ? 'selected' : '';
                                                                                                                }
                                                                                                                ?>>
                                                                    <?= $v_project->project_name ?></option>
                                                    <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                        $this->load->view('admin/items/warehouselist') ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('invoice_date') ?></label>
                                            <div class="col-lg-7">
                                                <div class="input-group">
                                                    <input type="text" name="invoice_date" class="form-control datepicker" value="<?php
                                                                                                                                    if (!empty($invoice_info->invoice_date)) {
                                                                                                                                        echo $invoice_info->invoice_date;
                                                                                                                                    } else {
                                                                                                                                        echo date('Y-m-d');
                                                                                                                                    }
                                                                                                                                    ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
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
                                                    <input type="text" name="due_date" class="form-control datepicker" value="<?php
                                                                                                                                if (!empty($invoice_info->due_date)) {
                                                                                                                                    echo $invoice_info->due_date;
                                                                                                                                } else {
                                                                                                                                    echo date('Y-m-d');
                                                                                                                                }
                                                                                                                                ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                        $permissionL = null;
                                        if (!empty($invoice_info->permission)) {
                                            $permissionL = $invoice_info->permission;
                                        }
                                        ?>
                                        <?= get_permission(3, 7, $permission_user, $permissionL, ''); ?>
                                        <?php
                                        if (!empty($invoice_info)) {
                                            $invoices_id = $invoice_info->invoices_id;
                                        } else {
                                            $invoices_id = null;
                                        }
                                        ?>
                                        <?= custom_form_Fields(9, $invoices_id); ?>

                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12 br pv">

                                    <div class="row">
                                        <div class="form-group">
                                            <label for="field-1" class="col-sm-4 control-label"><?= lang('sales') . ' ' . lang('agent') ?></label>
                                            <div class="col-sm-7">
                                                <select class="form-control select_box" required style="width: 100%" name="user_id">
                                                    <option value="">
                                                        <?= lang('select') . ' ' . lang('sales') . ' ' . lang('agent') ?>
                                                    </option>
                                                    <?php
                                                    $all_user = $this->db->where('role_id != ', 2)->get('tbl_users')->result();
                                                    if (!empty($all_user)) {
                                                        foreach ($all_user as $v_user) {
                                                            $profile_info = $this->db->where('user_id', $v_user->user_id)->get('tbl_account_details')->row();
                                                            if (!empty($profile_info)) {
                                                    ?>
                                                                <option value="<?= $v_user->user_id ?>" <?php
                                                                                                        if (!empty($invoice_info->user_id)) {
                                                                                                            echo $invoice_info->user_id == $v_user->user_id ? 'selected' : null;
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
                                            <label for="discount_type" class="control-label col-sm-4"><?= lang('discount_type') ?></label>
                                            <div class="col-sm-7">
                                                <select name="discount_type" class="selectpicker" data-width="100%">
                                                    <option value="" selected><?php echo lang('no') . ' ' . lang('discount'); ?>
                                                    </option>
                                                    <option value="before_tax" <?php
                                                                                if (isset($invoice_info)) {
                                                                                    if ($invoice_info->discount_type == 'before_tax') {
                                                                                        echo 'selected';
                                                                                    }
                                                                                } ?>><?php echo lang('before_tax'); ?>
                                                    </option>
                                                    <option value="after_tax" <?php if (isset($invoice_info)) {
                                                                                    if ($invoice_info->discount_type == 'after_tax') {
                                                                                        echo 'selected';
                                                                                    }
                                                                                } ?>><?php echo lang('after_tax'); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                        $all_payment = get_result('tbl_online_payment');
                                        foreach ($all_payment as $key => $payment) {
                                            $allow_gateway = 'allow_' . slug_it(strtolower($payment->gateway_name));
                                            $gateway_status = slug_it(strtolower($payment->gateway_name)) . '_status';
                                            if (config_item($gateway_status) == 'active') { ?>
                                                <div class="form-group">
                                                    <label for="field-1" class="col-sm-4 control-label"><?= lang($allow_gateway) ?></label>
                                                    <div class="col-sm-7">
                                                        <div class="checkbox c-checkbox">
                                                            <label class="needsclick">
                                                                <input type="checkbox" value="Yes" <?php if (!empty($invoice_info) && $invoice_info->$allow_gateway == 'Yes') {
                                                                                                        echo 'checked';
                                                                                                    } ?> name="<?= $allow_gateway ?>">
                                                                <span class="fa fa-check"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php }
                                        } ?>


                                        <?php if (!empty($project_id)) : ?>
                                            <div class="form-group">
                                                <label for="field-1" class="col-sm-4 control-label"><?= lang('visible_to_client') ?>
                                                    <span class="required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input data-toggle="toggle" name="client_visible" value="Yes" <?php
                                                                                                                    if (!empty($invoice_info->client_visible) && $invoice_info->client_visible == 'Yes') {
                                                                                                                        echo 'checked';
                                                                                                                    }
                                                                                                                    ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success" data-offstyle="danger" type="checkbox">
                                                </div>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-xs-12">

                                    <div class="">
                                        <label class="col-lg-1 control-label"><?= lang('notes') ?> </label>
                                        <div class="col-lg-11 row">
                                            <textarea name="notes" class="textarea_"><?php
                                                                                        if (!empty($invoice_info)) {
                                                                                            echo $invoice_info->notes;
                                                                                        } else {
                                                                                            echo $this->config->item('default_terms');
                                                                                        }
                                                                                        ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <style type="text/css">
                                .dropdown-menu>li>a {
                                    white-space: normal;
                                }

                                .dragger {
                                    background: url(../../assets/img/dragger.png) 10px 32px no-repeat;
                                    cursor: pointer;
                                }

                                <?php if (!empty($invoice_info)) {
                                ?>.dragger {
                                    background: url(../../../../assets/img/dragger.png) 10px 32px no-repeat;
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
                            $pdata['itemType'] = 'invoice';

                            if (!empty($invoice_info)) {
                                $pdata['add_items'] = $this->invoice_model->ordered_items_by_id($invoice_info->invoices_id, 'invoices', true);;
                                $pdata['info'] = $invoice_info;
                            }

                            $this->load->view('admin/items/selectItem', $pdata); ?>

                        <?php } else { ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
</form>