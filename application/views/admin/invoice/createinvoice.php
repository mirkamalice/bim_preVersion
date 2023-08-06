<form name="myform" role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
      action="<?php echo base_url(); ?>admin/invoice/save_invoice/<?php
      if (!empty($invoice_info)) {
          echo $invoice_info->invoices_id;
      }
      ?>" method="post" class="form-horizontal  ">
    
    <div class="<?php if (!isset($invoice_info) || (isset($invoice_info) && !empty($invoices_to_merge) && count($invoices_to_merge) == 0)) {
        echo ' hide';
    } ?>" id="invoice_top_info">
        <div class="panel-body">
            <div class="row">
                <div id="merge" class="col-md-8">
                    <?php if (isset($invoice_info) && !empty($invoices_to_merge)) {
                        $this->load->view('admin/invoice/merge_invoice', array('invoices_to_merge' => $invoices_to_merge));
                    } ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
    <?php include_once 'assets/admin-ajax.php'; ?>
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>
    
    <div id="invoice_state_report_div">
    
    
    </div>
    


    <?php
    $h_s = config_item('invoice_state');
    if ($this->session->userdata('user_type') == 1) {
        if ($h_s == 'block') { ?>
            <!--<script>
            $(document).ready(function () { ins_data(base_url+'admin/invoice/invoice_state_report'); });
            </script>-->
            <?php
            
            $title = lang('hide_quick_state');
            $url = 'hide';
            $icon = 'fa fa-eye-slash';
        } else {
            $title = lang('view_quick_state');
            $url = 'show';
            $icon = 'fa fa-eye';
            ?>
            <!--    <script>
                    $(document).ready(function () {  $("#quick_state").on("click", function(){
                        if($('#state_report').length){ } else{
                        ins_data(base_url+'admin/invoice/invoice_state_report');}
                    }); });
                </script>-->
            <?php
        }
        ?>
        <div onclick="slideToggle('#state_report')" id="quick_state" data-toggle="tooltip" data-placement="top"
             title="<?= $title ?>" class="btn-xs btn btn-purple pull-left">
            <i class="fa fa-bar-chart"></i>
        </div>
        <div class="btn-xs btn btn-white pull-left ml ">
            <a class="text-dark" id="change_report" href="<?= base_url() ?>admin/dashboard/change_report/<?= $url ?>"><i
                        class="<?= $icon ?>"></i>
                <span><?= ' ' . lang('quick_state') . ' ' . lang($url) . ' ' . lang('always') ?></span></a>
        </div>
        <?php
    }
    $created = can_action('13', 'created');
    $edited = can_action('13', 'edited');
    $deleted = can_action('13', 'deleted');
    if (!empty($created) || !empty($edited)) {
    ?>

    
    <div class="row">
        <div class="col-sm-12">

            <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs">
                    <li class=""><a
                                href="<?= base_url('admin/invoice/manage_invoice') ?>"><?= lang('all_invoices') ?></a>
                    </li>
                    <li class="active"><a
                                href="<?= base_url('admin/invoice/createinvoice') ?>"><?= lang('create_invoice') ?></a>
                    </li>
                </ul>
                <div class="tab-content bg-white">
                    <!-- ************** general *************-->
                    <?php } ?>
                    
                    
                    
                    <?php if (!empty($created) || !empty($edited)) { ?>
                    <div class="tab-pane active" id="create">
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
                                        <label class="col-lg-3 control-label"><?= lang('reference_no') ?> <span
                                                    class="text-danger">*</span></label>
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
                                        <div class="col-lg-1">
                                            <div class="btn btn-xs btn-info" id="start_recurring">
                                                <?= lang('recurring') ?></div>
                                        </div>
                                    
                                    </div>
                                    <div id="show_recurring"
                                         class="<?= (!empty($invoice_info) && $invoice_info->recurring == 'Yes' ? '' : 'hide') ?>">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('recur_frequency') ?>
                                            </label>
                                            <div class="col-lg-4">
                                                <select name="recuring_frequency" id="recuring_frequency"
                                                        class="form-control">
                                                    <option value="none"><?= lang('none') ?></option>
                                                    <option value="7D" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '7D' ? 'selected' : '') ?>>
                                                        <?= lang('week') ?></option>
                                                    <option value="1M" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '1M' ? 'selected' : '') ?>>
                                                        <?= lang('month') ?></option>
                                                    <option value="3M" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '3M' ? 'selected' : '') ?>>
                                                        <?= lang('quarter') ?></option>
                                                    <option value="6M" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '6M' ? 'selected' : '') ?>>
                                                        <?= lang('six_months') ?></option>
                                                    <option value="1Y" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '1Y' ? 'selected' : '') ?>>
                                                        <?= lang('1year') ?></option>
                                                    <option value="2Y" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '2Y' ? 'selected' : '') ?>>
                                                        <?= lang('2year') ?></option>
                                                    <option value="3Y" <?= (!empty($invoice_info) && $invoice_info->recur_frequency == '3Y' ? 'selected' : '') ?>>
                                                        <?= lang('3year') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('start_date') ?></label>
                                            <div class="col-lg-7">
                                                <?php
                                                if (!empty($invoice_info) && $invoice_info->recurring == 'Yes') {
                                                    $recur_start_date = jdate('Y-m-d', strtotime($invoice_info->recur_start_date));
                                                    $recur_end_date = jdate('Y-m-d', strtotime($invoice_info->recur_end_date));
                                                } else {
                                                    $recur_start_date = jdate('Y-m-d');
                                                    $recur_end_date = jdate('Y-m-d');
                                                }
                                                ?>
                                                <div class="input-group">
                                                    <input class="form-control datepicker" type="text"
                                                           value="<?= $recur_start_date; ?>" name="recur_start_date"
                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
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
                                                    <input class="form-control datepicker" type="text"
                                                           value="<?= $recur_end_date; ?>" name="recur_end_date"
                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="f_client_id">
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('client') ?> <span
                                                        class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-7">
                                                <div class="input-group">
                                                    <select class="form-control select_box" style="width: 100%"
                                                            name="client_id" onchange="get_project_by_id(this.value)"
                                                            required="">
                                                        <option value="">
                                                            <?= lang('select') . ' ' . lang('client') ?></option>
                                                        <?php
                                                        if (!empty($all_client)) {
                                                            foreach ($all_client as $v_client) {
                                                                if (!empty($project_info->client_id)) {
                                                                    $client_id = $project_info->client_id;
                                                                } elseif (!empty($invoice_info->client_id)) {
                                                                    $client_id = $invoice_info->client_id;
                                                                } elseif (!empty($c_id)) {
                                                                    $client_id = $c_id;
                                                                }
                                                                ?>
                                                                <option value="<?= $v_client->client_id ?>" <?php
                                                                if (!empty($client_id)) {
                                                                    echo $client_id == $v_client->client_id ? 'selected' : null;
                                                                }
                                                                ?>>
                                                                    <?= ucfirst($v_client->name) ?>
                                                                </option>
                                                                <?php
                                                            }
                                                        }
                                                        $acreated = can_action('4', 'created');
                                                        ?>
                                                    </select>
                                                    <?php if (!empty($acreated)) { ?>
                                                        <div class="input-group-addon"
                                                             title="<?= lang('new') . ' ' . lang('client') ?>"
                                                             data-toggle="tooltip" data-placement="top">
                                                            <a data-toggle="modal" data-target="#myModal"
                                                               href="<?= base_url() ?>admin/client/new_client"><i
                                                                        class="fa fa-plus"></i></a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('project') ?></label>
                                        <div class="col-lg-7">
                                            <select class="form-control" style="width: 100%" name="project_id"
                                                    id="client_project">
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
                                                        foreach ($all_project as $v_cproject) {
                                                            ?>
                                                            <option value="<?= $v_cproject->project_id ?>" <?php
                                                            if (!empty($project_id)) {
                                                                echo $v_cproject->project_id == $project_id ? 'selected' : '';
                                                            }
                                                            ?>>
                                                                <?= $v_cproject->project_name ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label"><?= lang('invoice_date') ?></label>
                                        <div class="col-lg-7">
                                            <div class="input-group">
                                                <input type="text" name="invoice_date" class="form-control datepicker"
                                                       value="<?php
                                                       if (!empty($invoice_info->invoice_date)) {
                                                           echo $invoice_info->invoice_date;
                                                       } else {
                                                           echo jdate('Y-m-d');
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
                                                <input type="text" name="due_date" class="form-control datepicker"
                                                       value="<?php
                                                       if (!empty($invoice_info->due_date)) {
                                                           echo $invoice_info->due_date;
                                                       } else {
                                                           echo strftime(date('Y-m-d', strtotime("+" . config_item('invoices_due_after') . " days")));
                                                       }
                                                       ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                                                <div class="input-group-addon">
                                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="discount_type"
                                               class="control-label col-sm-3"><?= lang('discount_type') ?></label>
                                        <div class="col-sm-7">
                                            <select name="discount_type" class="selectpicker" data-width="100%">
                                                <option value="" selected>
                                                    <?php echo lang('no') . ' ' . lang('discount'); ?></option>
                                                <option value="before_tax" <?php
                                                if (isset($invoice_info)) {
                                                    if ($invoice_info->discount_type == 'before_tax') {
                                                        echo 'selected';
                                                    }
                                                } ?>>
                                                    <?php echo lang('before_tax'); ?></option>
                                                <option value="after_tax" <?php if (isset($invoice_info)) {
                                                    if ($invoice_info->discount_type == 'after_tax') {
                                                        echo 'selected';
                                                    }
                                                } ?>>
                                                    <?php echo lang('after_tax'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <?php
                                    do_action('add_more_field_after_discount', !empty($invoice_info) ? $invoice_info : '');
                                    
                                    $this->load->view('admin/items/warehouselist') ?>
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
                                        <label for="field-1" class="col-sm-4 control-label"><?= lang('tags') ?></label>
                                        <div class="col-sm-7">
                                            <input type="text" name="tags" data-role="tagsinput" class="form-control"
                                                   value="<?php
                                                   if (!empty($invoice_info->tags)) {
                                                       echo $invoice_info->tags;
                                                   }
                                                   ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="field-1"
                                               class="col-sm-4 control-label"><?= lang('sales') . ' ' . lang('agent') ?></label>
                                        <div class="col-sm-7">
                                            <select class="form-control select_box" required style="width: 100%"
                                                    name="user_id">
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
                                    <?php
                                    $all_payment = get_result('tbl_online_payment');
                                    foreach ($all_payment as $key => $payment) {
                                        $allow_gateway = 'allow_' . slug_it(strtolower($payment->gateway_name));
                                        $gateway_status = slug_it(strtolower($payment->gateway_name)) . '_status';
                                        if (config_item($gateway_status) == 'active') { ?>
                                            <div class="form-group">
                                                <label for="field-1"
                                                       class="col-sm-4 control-label"><?= lang($allow_gateway) ?></label>
                                                <div class="col-sm-7">
                                                    <div class="checkbox c-checkbox">
                                                        <label class="needsclick">
                                                            <input type="checkbox"
                                                                   value="Yes" <?php if (!empty($invoice_info) && $invoice_info->$allow_gateway == 'Yes') {
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
                                            <label for="field-1"
                                                   class="col-sm-4 control-label"><?= lang('visible_to_client') ?>
                                                <span class="required">*</span></label>
                                            <div class="col-sm-8">
                                                <input data-togglse="toggle" name="client_visible" value="Yes" <?php
                                                if (!empty($invoice_info->client_visible) && $invoice_info->client_visible == 'Yes') {
                                                    echo 'checked';
                                                }
                                                ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                                       data-onstyle="success" data-offstyle="danger" type="checkbox">
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12 ">
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
                            .dropdown-menu > li > a {
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
                            $pdata['add_items'] = $this->invoice_model->ordered_items_by_id($invoice_info->invoices_id, 'invoices', true);
                            $pdata['info'] = $invoice_info;
                        }
                        $this->load->view('admin/items/selectItem', $pdata); ?>

</form>
<?php } else { ?>
    </div>
<?php } ?>
</div>

<script type="text/javascript">
    function slideToggle($id) {
        $('#quick_state').attr('data-original-title', '<?= lang('view_quick_state') ?>');
        $($id).slideToggle("slow");
    }

    $(document).ready(function () {
        $("#select_all_tasks").on("click", function () {
            $(".tasks_list").prop('checked', $(this).prop('checked'));
        });
        $("#select_all_expense").on("click", function () {
            $(".expense_list").prop('checked', $(this).prop('checked'));
        });
        $('[data-toggle="popover"]').popover();

        $('#start_recurring').on("click", function () {
            if ($('#show_recurring').is(":visible")) {
                $('#recuring_frequency').prop('disabled', true);
            } else {
                $('#recuring_frequency').prop('disabled', false);
            }
            $('#show_recurring').slideToggle("fast");
            $('#show_recurring').removeClass("hide");
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        init_items_sortable();
    });
</script>

<?php
if ($this->session->userdata('user_type') == 1) {
    if ($h_s == 'block') { ?>
        <script>
            $(document).ready(function () {
                ins_data(base_url + 'admin/invoice/invoice_state_report');
            });
        </script>
        <?php
        
    } else { ?>
        <script>
            $(document).ready(function () {
                $("#quick_state").on("click", function () {
                    if ($('#state_report').length) {
                    } else {
                        ins_data(base_url + 'admin/invoice/invoice_state_report');
                    }
                });
            });
        </script>
        <?php
    }
    ?>
<?php } ?>
<script>
    $(document).ready(function () {
        ins_data(base_url + 'admin/invoice/invo_data');
    });
</script>