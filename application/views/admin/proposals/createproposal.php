<form name="myform" role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/proposals/save_proposals/<?php
                                                                                                                                                                                if (!empty($proposals_info)) {
                                                                                                                                                                                    echo $proposals_info->proposals_id;
                                                                                                                                                                                }
                                                                                                                                                                                ?>" method="post" class="form-horizontal  ">

    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
    <?php include_once 'assets/admin-ajax.php'; ?>
    <?php include_once 'assets/js/sales.php'; ?>
    <?= message_box('success'); ?>
    <?= message_box('error'); ?>

    <?php
    $curency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
    ?>
    <div id="proposals_state_report_div"> <?php //$this->load->view("admin/proposals/proposals_state_report"); 
                                            ?></div>
    <?php
    $type = $this->uri->segment(5);

    if (empty($type)) {
        $type = '_' . date('Y');
    }
    ?>

    <?php
    if ($this->session->userdata('user_type') == 1) {
        $type = 'proposal';
        if ($h_s == 'block') {
            $title = lang('hide_quick_state');
            $url = 'hide';
            $icon = 'fa fa-eye-slash';
        } else {
            $title = lang('view_quick_state');
            $url = 'show';
            $icon = 'fa fa-eye';
        }
    ?>
        <div onclick="slideToggle('#state_report')" id="quick_state" data-toggle="tooltip" data-placement="top" title="<?= $title ?>" class="btn-xs btn btn-purple pull-left">
            <i class="fa fa-bar-chart"></i>
        </div>
        <div class="btn-xs btn btn-white pull-left ml ">
            <a class="text-dark" id="change_report" href="<?= base_url() ?>admin/dashboard/change_report/<?= $url . '/' . $type ?>"><i class="<?= $icon ?>"></i>
                <span><?= ' ' . lang('quick_state') . ' ' . lang($url) . ' ' . lang('always') ?></span></a>
        </div>
    <?php }
    $created = can_action('140', 'created');
    $edited = can_action('140', 'edited');
    $deleted = can_action('140', 'deleted');
    if (!empty($created) || !empty($edited)) {
    ?>

        <div class="row">
            <div class="col-sm-12">

                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs">
                        <li class=""><a href="<?= base_url('admin/proposals') ?>"><?= lang('all_proposals') ?></a>
                        </li>
                        <li class="active"><a href="<?= base_url('admin/proposals/createproposal') ?>"><?= lang('create_proposal') ?></a>
                        </li>


                    </ul>
                    <div class="tab-content bg-white">
                        <!-- ************** general *************-->
                        <div class="tab-pane " id="manage">
                        <?php } else { ?>
                            <div class="panel panel-custom">
                                <header class="panel-heading ">
                                    <div class="panel-title"><strong><?= lang('all_proposals') ?></strong></div>
                                </header>
                            <?php } ?>

                            </div>
                            <?php if (!empty($created) || !empty($edited)) { ?>
                                <div class="tab-pane active" id="new">
                                    <div class="row mb-lg invoice proposal-template">
                                        <div class="col-sm-6 col-xs-12 br pv">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label"><?= lang('reference_no') ?> <span class="text-danger">*</span></label>
                                                    <div class="col-lg-7">
                                                        <?php $this->load->helper('string'); ?>
                                                        <input type="text" class="form-control" value="<?php
                                                                                                        if (!empty($proposals_info)) {
                                                                                                            echo $proposals_info->reference_no;
                                                                                                        } else {
                                                                                                            if (empty(config_item('proposal_number_format'))) {
                                                                                                                echo config_item('proposal_prefix');
                                                                                                            }
                                                                                                            if (config_item('increment_proposal_number') == 'FALSE') {
                                                                                                                $this->load->helper('string');
                                                                                                                echo random_string('nozero', 6);
                                                                                                            } else {
                                                                                                                echo $this->proposal_model->generate_proposal_number();
                                                                                                            }
                                                                                                        }
                                                                                                        ?>" name="reference_no">
                                                    </div>

                                                </div>
                                                <?php if (!empty($proposals_info->module)) {
                                                    if ($proposals_info->module == 'leads') {
                                                        $leads_id = $proposals_info->module_id;
                                                    }
                                                    if ($proposals_info->module == 'client') {
                                                        $client_id = $proposals_info->module_id;
                                                    }
                                                } elseif (!empty($module)) {
                                                    if ($module == 'leads') {
                                                        $leads_id = $module_id;
                                                    }
                                                    if ($module == 'client') {
                                                        $client_id = $module_id;
                                                    }
                                                ?>
                                                    <input type="hidden" name="un_module" required class="form-control" value="<?php echo $module ?>" />
                                                    <input type="hidden" name="un_module_id" required class="form-control" value="<?php echo $module_id ?>" />
                                                <?php }
                                                ?>
                                                <div class="form-group" id="border-none">
                                                    <label for="field-1" class="col-sm-3 control-label"><?= lang('related_to') ?> </label>
                                                    <div class="col-sm-7">
                                                        <select name="module" class="form-control select_box" id="check_related" required onchange="get_related_moduleName(this.value,true)" style="width: 100%">
                                                            <option value=""> <?= lang('none') ?> </option>
                                                            <option value="leads" <?= (!empty($leads_id) ? 'selected' : '') ?>>
                                                                <?= lang('leads') ?> </option>
                                                            <option value="client" <?= (!empty($client_id) ? 'selected' : '') ?>>
                                                                <?= lang('client') ?> </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group" id="related_to">

                                                </div>
                                                <?php if (!empty($leads_id)) : ?>
                                                    <div class="form-group <?= $leads_id ? 'leads_module' : 'company' ?>" id="border-none">
                                                        <label for="field-1" class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('leads') ?>
                                                            <span class="required">*</span></label>
                                                        <div class="col-sm-7">
                                                            <select name="leads_id" style="width: 100%" class="select_box <?= $leads_id ? 'leads_module' : 'company' ?>" required="1">
                                                                <?php
                                                                $all_leads_info = $this->db->get('tbl_leads')->result();
                                                                if (!empty($all_leads_info)) {
                                                                    foreach ($all_leads_info as $v_leads) {
                                                                ?>
                                                                        <option value="<?= $v_leads->leads_id ?>" <?php
                                                                                                                    if (!empty($leads_id)) {
                                                                                                                        echo $v_leads->leads_id == $leads_id ? 'selected' : '';
                                                                                                                    }
                                                                                                                    ?>>
                                                                            <?= $v_leads->lead_name ?></option>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group <?= $leads_id ? 'leads_module' : 'company' ?>" id="border-none">
                                                        <label for="field-1" class="col-sm-3 control-label"><?= lang('currency') ?>
                                                            <span class="required">*</span></label>
                                                        <div class="col-sm-7">
                                                            <select name="currency" style="width: 100%" class="select_box <?= $leads_id ? 'leads_module' : 'company' ?>" required="1">
                                                                <?php
                                                                $all_currency = $this->db->get('tbl_currencies')->result();
                                                                foreach ($all_currency as $v_currency) {
                                                                ?>
                                                                    <option value="<?= $v_currency->code ?>" <?= (config_item('default_currency') == $v_currency->code ? ' selected="selected"' : '') ?>>
                                                                        <?= $v_currency->name ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php endif ?>
                                                <?php if (!empty($client_id)) : ?>
                                                    <div class="form-group <?= $client_id ? 'client_module' : 'company' ?>" id="border-none">
                                                        <label for="field-1" class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('client') ?>
                                                            <span class="required">*</span></label>
                                                        <div class="col-sm-7">
                                                            <select name="client_id" style="width: 100%" class="select_box <?= $client_id ? 'client_module' : 'company' ?>" required="1">
                                                                <?php
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

                                                <div class="form-group">
                                                    <label for="discount_type" class="control-label col-sm-3"><?= lang('discount_type') ?></label>
                                                    <div class="col-sm-7">
                                                        <select name="discount_type" class="selectpicker" data-width="100%">
                                                            <option value="" selected>
                                                                <?php echo lang('no') . ' ' . lang('discount'); ?></option>
                                                            <option value="before_tax" <?php
                                                                                        if (isset($proposals_info)) {
                                                                                            if ($proposals_info->discount_type == 'before_tax') {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        } ?>>
                                                                <?php echo lang('before_tax'); ?></option>
                                                            <option value="after_tax" <?php if (isset($proposals_info)) {
                                                                                            if ($proposals_info->discount_type == 'after_tax') {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        } ?>>
                                                                <?php echo lang('after_tax'); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php $this->load->view('admin/items/warehouselist') ?>




                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label"><?= lang('status') ?> </label>
                                                    <div class="col-lg-7">
                                                        <select name="status" class="selectpicker" data-width="100%">
                                                            <option <?php if (isset($proposals_info)) {
                                                                        if ($proposals_info->status == 'draft') {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?> value="draft"><?= lang('draft') ?></option>
                                                            <option <?php if (isset($proposals_info)) {
                                                                        if ($proposals_info->status == 'sent') {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?> value="sent"><?= lang('sent') ?></option>
                                                            <option <?php if (isset($proposals_info)) {
                                                                        if ($proposals_info->status == 'open') {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?> value="open"><?= lang('open') ?></option>
                                                            <option <?php if (isset($proposals_info)) {
                                                                        if ($proposals_info->status == 'revised') {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?> value="revised"><?= lang('revised') ?>
                                                            </option>
                                                            <option <?php if (isset($proposals_info)) {
                                                                        if ($proposals_info->status == 'declined') {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?>value="declined"><?= lang('declined') ?>
                                                            </option>
                                                            <option <?php if (isset($proposals_info)) {
                                                                        if ($proposals_info->status == 'accepted') {
                                                                            echo 'selected';
                                                                        }
                                                                    } ?>value="accepted"><?= lang('accepted') ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="discount_type" class="control-label col-sm-3"><?= lang('tags') ?></label>
                                                    <div class="col-sm-7">
                                                        <input type="text" name="tags" data-role="tagsinput" class="form-control" value="<?php
                                                                                                                                            if (!empty($proposals_info->tags)) {
                                                                                                                                                echo $proposals_info->tags;
                                                                                                                                            }
                                                                                                                                            ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12 br pv">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="field-1" class="col-sm-3 control-label"><?= lang('assigned') . ' ' . lang('users') ?></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control select_box" required style="width: 100%" name="user_id">
                                                            <option value="">
                                                                <?= lang('select') . ' ' . lang('assigned') . ' ' . lang('users') ?>
                                                            </option>
                                                            <?php
                                                            $all_user = $this->db->where('role_id != ', 2)->get('tbl_users')->result();
                                                            if (!empty($all_user)) {
                                                                foreach ($all_user as $v_user) {
                                                                    $profile_info = $this->db->where('user_id', $v_user->user_id)->get('tbl_account_details')->row();
                                                                    if (!empty($profile_info)) {
                                                            ?>
                                                                        <option value="<?= $v_user->user_id ?>" <?php
                                                                                                                if (!empty($proposals_info->user_id)) {
                                                                                                                    echo $proposals_info->user_id == $v_user->user_id ? 'selected' : null;
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
                                                    <label class="col-lg-3 control-label"><?= lang('proposal_date') ?></label>
                                                    <div class="col-lg-7">
                                                        <div class="input-group">
                                                            <input type="text" name="proposal_date" class="form-control datepicker" value="<?php
                                                                                                                                            if (!empty($proposals_info->proposal_date)) {
                                                                                                                                                echo $proposals_info->proposal_date;
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
                                                    <label class="col-lg-3 control-label"><?= lang('expire_date') ?></label>
                                                    <div class="col-lg-7">
                                                        <div class="input-group">
                                                            <input type="text" name="due_date" class="form-control datepicker" value="<?php
                                                                                                                                        if (!empty($proposals_info->due_date)) {
                                                                                                                                            echo $proposals_info->due_date;
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
                                                if (!empty($proposals_info)) {
                                                    $proposals_id = $proposals_info->proposals_id;
                                                } else {
                                                    $proposals_id = null;
                                                }
                                                ?>
                                                <?= custom_form_Fields(11, $proposals_id); ?>

                                            </div>
                                            <?php
                                            $permissionL = null;
                                            if (!empty($proposals_info->permission)) {
                                                $permissionL = $proposals_info->permission;
                                            }
                                            ?>
                                            <?= get_permission(3, 7, $permission_user, $permissionL, ''); ?>

                                        </div>
                                    </div>
                                    <div class="form-group terms">
                                        <label class="col-lg-1 control-label"><?= lang('notes') ?> </label>
                                        <div class="col-lg-11">
                                            <textarea name="notes" class="form-control textarea_"><?php
                                                                                                    if (!empty($proposals_info)) {
                                                                                                        echo $proposals_info->notes;
                                                                                                    } else {
                                                                                                        echo $this->config->item('proposal_terms');
                                                                                                    }
                                                                                                    ?></textarea>
                                        </div>
                                    </div>
                                    <?php
                                    if (!empty($proposals_info)) {
                                        if ($proposals_info->module == 'client') {
                                            $client_info = $this->proposal_model->check_by(array('client_id' => $proposals_info->module_id), 'tbl_client');
                                            $currency = $this->proposal_model->client_currency_symbol($proposals_info->module_id);
                                            $client_lang = $client_info->language;
                                        } else {
                                            $client_info = $this->proposal_model->check_by(array('leads_id' => $proposals_info->module_id), 'tbl_leads');
                                            if (!empty($client_info)) {
                                                $client_info->name = $client_info->lead_name;
                                                $client_info->zipcode = null;
                                            }
                                            $client_lang = 'english';
                                            $currency = $this->proposal_model->check_by(array('code' => $proposals_info->currency), 'tbl_currencies');
                                        }
                                    } else {
                                        $client_lang = 'english';
                                        $currency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                                    }
                                    unset($this->lang->is_loaded[5]);
                                    $language_info = $this->lang->load('sales_lang', $client_lang, TRUE, FALSE, '', TRUE);
                                    ?>

                                    <style type="text/css">
                                        .dropdown-menu>li>a {
                                            white-space: normal;
                                        }

                                        .dragger {
                                            background: url(../assets/img/dragger.png) 10px 32px no-repeat;
                                            cursor: pointer;
                                        }

                                        <?php if (!empty($proposals_info)) {
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
                                    $pdata['itemType'] = 'proposal';
                                    if (!empty($proposals_info)) {
                                        $pdata['add_items'] = $this->proposal_model->ordered_items_by_id($proposals_info->proposals_id, 'proposal', true);
                                        $pdata['info'] = $proposals_info;
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

    $(document).ready(function() {
        init_items_sortable();
    });
</script>
<script>
    $(document).ready(function() {
        ins_data(base_url + 'admin/proposals/proposals_state_report')
    });
</script>