<?= message_box('success') ?>
<?= message_box('error') ?>
    <script src="<?= base_url() ?>assets/plugins/jquery-ui/jquery-u.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/awesomplete/awesomplete.css">
    <script src="<?= base_url() ?>assets/plugins/awesomplete/awesomplete.min.js"></script>
<?php
$edited = can_action('154', 'edited');
$deleted = can_action('154', 'deleted');
if (!empty($invoice_info)) {
    $invoices_id = $invoice_info->invoices_id;
} else {
    $invoices_id = null;
}
echo form_open(base_url('admin/invoice/save_invoice/' . $invoices_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>

    <section class="panel panel-custom ">
        <header class="panel-heading">
            <div class="panel-title">
                <?= lang('pos_sales') ?>
                <div class="pull-right">
                    <input type="button" id="Preset" value="<?= lang('reset') ?>" name="update" class="btn btn-danger">
                    <input type="submit" value="<?= lang('paid') ?>" name="Paid" class="btn btn-success">
                    <input type="submit" value="<?= lang('draft') ?>" name="save_as_draft" class="btn btn-warning">
                    <input type="hidden" value="1" name="pos">
                </div>
            </div>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6 br pv">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-lg-3 control-label col-md-3 col-sm-3"><?= lang('reference_no') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($invoice_info)) {
                                    echo $invoice_info->reference_no;
                                } else {
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
                            <label class="col-lg-3 control-label col-md-3 col-sm-3"><?= lang('client') ?> <span
                                        class="text-danger">*</span>
                            </label>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <?php
                                $selected = (!empty($invoice_info->client_id) ? $invoice_info->client_id : '');
                                echo form_dropdown('client_id', $all_client, $selected, array('class' => 'form-control select_box', 'style' => 'width:100%'));
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label col-md-3 col-sm-3"><?= lang('invoice_date') ?></label>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <div class="input-group">
                                    <input type="text" name="invoice_date" class="form-control datepicker" value="<?php
                                    if (!empty($invoice_info->invoice_date)) {
                                        echo $invoice_info->invoice_date;
                                    } else {
                                        echo jdate('Y-m-d H:i');
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
                            <label class="col-lg-3 control-label col-md-3 col-sm-3"><?= lang('due_date') ?></label>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <div class="input-group">
                                    <input type="text" name="due_date" class="form-control datepicker" value="<?php
                                    if (!empty($invoice_info->due_date)) {
                                        echo $invoice_info->due_date;
                                    } else {
                                        echo strftime(date('Y-m-d H:i', strtotime("+" . config_item('invoices_due_after') . " days")));
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
                            <label for="discount_type"
                                   class="control-label col-sm-3"><?= lang('discount_type') ?></label>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <select name="discount_type" class="selectpicker" data-width="100%">
                                    <option value=""
                                            selected><?php echo lang('no') . ' ' . lang('discount'); ?></option>
                                    <option value="before_tax" <?php
                                    if (isset($invoice_info)) {
                                        if ($invoice_info->discount_type == 'before_tax') {
                                            echo 'selected';
                                        }
                                    } ?>><?php echo lang('before_tax'); ?></option>
                                    <option value="after_tax" <?php if (isset($invoice_info)) {
                                        if ($invoice_info->discount_type == 'after_tax') {
                                            echo 'selected';
                                        }
                                    } ?>><?php echo lang('after_tax'); ?></option>
                                </select>
                            </div>
                        </div>
                        <?php $this->load->view('admin/items/warehouselist') ?>
                        <div class="form-group">
                            <label for="field-1"
                                   class="col-lg-3 col-md-3 col-sm-3 control-label"><?= lang('sales') . ' ' . lang('agent') ?></label>
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <select class="form-control select_box" required style="width: 100%" name="user_id">
                                    <option value=""><?= lang('select') . ' ' . lang('sales') . ' ' . lang('agent') ?>
                                    </option>
                                    <?php
                                    $all_user = get_staff_details();
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
                                                ?>><?= $profile_info->fullname ?>
                                                </option>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="term"
                                   placeholder="<?= lang('enter_product_name_and_code') ?>" id="search_text">
                            <hr class="m0 mb-lg mt-lg"/>
                        </div>

                        <div id="product_result" class="product_result">

                        </div>
                    </div>
                </div>
            </div>
            <style type="text/css">
                .dropdown-menu > li > a {
                    white-space: normal;
                }

                .dragger {
                    background: url(<?= base_url() ?>assets/img/dragger.png) 10px 32px no-repeat;
                    cursor: pointer;
                }

                <?php if ( !empty($purchase_info)) {
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
            <hr/>
            <div class="mt-lg prodcutTable" data-terget="pos">
                <div class="col-lg-6 col-md-5">
                    <div class="form-group">
                        <input type="text" placeholder="<?= lang('search_product_by_name_code'); ?>" id="pos_item"
                               class="form-control">
                    </div>
                </div>
                <div class="col-lg-5 col-md-7 pull-right float-none">
                    <div class="form-group">
                        <label class="col-lg-4 col-md-4  control-label"><?php echo lang('show_quantity_as'); ?></label>
                        <div class="col-lg-8 col-md-8 ">
                            <label class="radio-inline c-radio">
                                <input type="radio" value="qty" id="<?php echo lang('qty'); ?>" name="show_quantity_as"
                                    <?php if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty') {
                                        echo 'checked';
                                    } else if (!isset($hours_quantity) && !isset($qty_hrs_quantity)) {
                                        echo 'checked';
                                    } ?>>
                                <span class="fa fa-circle"></span><?php echo lang('qty'); ?>
                            </label>
                            <label class="radio-inline c-radio">
                                <input type="radio" value="hours" id="<?php echo lang('hours'); ?>"
                                       name="show_quantity_as"
                                    <?php if (isset($purchase_info) && $purchase_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                        echo 'checked';
                                    } ?>>
                                <span class="fa fa-circle"></span><?php echo lang('hours'); ?>
                            </label>
                            <label class="radio-inline c-radio">
                                <input type="radio" value="qty_hours"
                                       id="<?php echo lang('qty') . '/' . lang('hours'); ?>"
                                       name="show_quantity_as"
                                    <?php if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty_hours' || isset($qty_hrs_quantity)) {
                                        echo 'checked';
                                    } ?>>
                                <span class="fa fa-circle"></span><?php echo lang('qty') . '/' . lang('hours'); ?>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="table-responsive s_table">
                    <table class="table invoice-items-table items">
                        <thead style="background: #e8e8e8">
                        <tr>
                            <th></th>
                            <th><?= lang('item_name') ?></th>
                            <th><?= lang('description') ?></th>
                            <?php
                            $purchase_view = config_item('purchase_view');
                            if (!empty($purchase_view) && $purchase_view == '2') {
                                ?>
                                <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('hsn_code') ?></th>
                            <?php } ?>
                            <?php
                            $qty_heading = lang('qty');
                            if (isset($purchase_info) && $purchase_info->show_quantity_as == 'hours' || isset($hours_quantity)) {
                                $qty_heading = lang('hours');
                            } else if (isset($purchase_info) && $purchase_info->show_quantity_as == 'qty_hours') {
                                $qty_heading = lang('qty') . '/' . lang('hours');
                            }
                            ?>
                            <th class="qty col-lg-1 col-md-1 col-sm-1"><?php echo $qty_heading; ?></th>
                            <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('price') ?></th>
                            <th class="col-lg-2 col-md-2 col-sm-2"><?= lang('tax_rate') ?> </th>
                            <th class="col-lg-1 col-md-1 col-sm-1"><?= lang('total') ?></th>
                            <th class="col-lg-1 col-md-1 col-sm-1 hidden-print"><?= lang('action') ?></th>
                        </tr>
                        </thead>
                        <tbody id="posTable">

                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 pull-right">
                        <table class="table text-right">
                            <tbody>
                            <tr id="subtotal">
                                <td><span class="bold"><?php echo lang('sub_total'); ?> :</span>
                                </td>
                                <td class="subtotal">
                                </td>
                            </tr>
                            <tr id="discount_percent">
                                <td>
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <span class="bold"><?php echo lang('discount'); ?>
                                                (%)</span>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                            <?php
                                            $discount_percent = 0;
                                            if (isset($purchase_info)) {
                                                if ($purchase_info->discount_percent != 0) {
                                                    $discount_percent = $purchase_info->discount_percent;
                                                }
                                            }
                                            ?>
                                            <input type="text" data-parsley-type="number"
                                                   value="<?php echo $discount_percent; ?>"
                                                   class="form-control pull-left"
                                                   min="0" max="100" name="discount_percent">
                                        </div>
                                    </div>
                                </td>
                                <td class="discount_percent"></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-lg-7 col-md-7 col-sm-7">
                                            <span class="bold"><?php echo lang('adjustment'); ?></span>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                            <input type="text" data-parsley-type="number"
                                                   value="<?php if (isset($purchase_info)) {
                                                       echo $purchase_info->adjustment;
                                                   } else {
                                                       echo 0;
                                                   } ?>"
                                                   class="form-control pull-left" name="adjustment">
                                        </div>
                                    </div>
                                </td>
                                <td class="adjustment"></td>
                            </tr>
                            <tr>
                                <td><span class="bold"><?php echo lang('total'); ?> :</span>
                                </td>
                                <td class="total">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="removed-items"></div>
            </div>
        </div>
    </section>

<?php echo form_close(); ?>
<?php include_once 'assets/js/product.php'; ?>