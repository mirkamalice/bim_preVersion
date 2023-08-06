<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>

<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('55', 'created');
$edited = can_action('55', 'edited');
$deleted = can_action('55', 'deleted');
$kanban = $this->session->userdata('leads_kanban');
$uri_segment = $this->uri->segment(4);
if (!empty($kanban)) {
    $k_leads = 'kanban';
} elseif ($uri_segment == 'kanban') {
    $k_leads = 'kanban';
} else {
    $k_leads = 'list';
}

if ($k_leads == 'kanban') {
    $text = 'list';
    $btn = 'purple';
} else {
    $text = 'kanban';
    $btn = 'danger';
}
?>
<div class="row mb-lg">
    <div class="col-sm-2 ">
        <div class="pull-left pr-lg">
            <a href="<?= base_url() ?>admin/leads/create/<?= $text ?>" class="btn btn-xs btn-<?= $btn ?> pull-right"
               data-toggle="tooltip" data-placement="top" title="<?= lang('switch_to_kanban') ?>">
                <i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_' . $text) ?>
            </a>
        </div>
    </div>
    <?php if ($text == 'kanban') {
        $type = $this->uri->segment(4);
        $id = $this->uri->segment(5);
        ?>
    <?php } ?>
</div>
<div class="row">
    <div class="col-sm-12">

        <?php if ($k_leads == 'kanban') { ?>
            <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/kanban/kan-app.css"/>
            <div class="app-wrapper">
                <p class="total-card-counter" id="totalCards"></p>
                <div class="board" id="board"></div>
            </div>
            <?php include_once 'assets/plugins/kanban/leads_kan-app.php'; ?>
        <?php } else { ?>
        <?php if (!empty($created) || !empty($edited)) {
        ?>

        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                            href="<?= base_url('admin/leads') ?>"><?= lang('all_leads') ?></a>
                </li>
                <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                            href="<?= base_url('admin/leads/create') ?>"><?= lang('new_leads') ?></a>
                </li>
                <li><a class="import" href="<?= base_url() ?>admin/leads/import_leads"><?= lang('import_leads') ?></a>
                </li>
            </ul>
            <div class="tab-content bg-white">
                <!-- ************** general *************-->
                <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                    <form role="form" enctype="multipart/form-data" data-parsley-validate="" novalidate=""
                          action="<?php echo base_url(); ?>admin/leads/saved_leads/<?php
                          if (!empty($leads_info)) {
                              echo $leads_info->leads_id;
                          }
                          ?>" method="post"
                          class="form-horizontal">

                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('lead_name') ?> <span
                                            class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->lead_name;
                                    }
                                    ?>" name="lead_name"
                                           required="">
                                </div>
                                <label class="col-lg-2 control-label"><?= lang('lead_status') ?> </label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <select name="lead_status_id" class="form-control select_box"
                                                style="width: 100%" required="">
                                            <?php

                                            if (!empty($status_info)) {
                                                foreach ($status_info as $type => $v_leads_status) {
                                                    if (!empty($v_leads_status)) {
                                                        ?>
                                                        <optgroup label="<?= lang($type) ?>">
                                                            <?php foreach ($v_leads_status as $v_l_status) { ?>
                                                                <option value="<?= $v_l_status->lead_status_id ?>"
                                                                    <?php
                                                                    if (!empty($leads_info->lead_status_id)) {
                                                                        echo $v_l_status->lead_status_id == $leads_info->lead_status_id ? 'selected' : '';
                                                                    }
                                                                    ?>><?= $v_l_status->lead_status ?>
                                                                </option>
                                                            <?php } ?>
                                                        </optgroup>
                                                        <?php
                                                    }
                                                }
                                            }
                                            $created = can_action('127', 'created');
                                            ?>
                                        </select>
                                        <?php if (!empty($created)) { ?>
                                            <div class="input-group-addon"
                                                 title="<?= lang('new') . ' ' . lang('lead_status') ?>"
                                                 data-toggle="tooltip"
                                                 data-placement="top">
                                                <a data-toggle="modal" data-target="#myModal"
                                                   href="<?= base_url() ?>admin/leads/lead_status"><i
                                                            class="fa fa-plus"></i></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">

                                <label class="col-lg-2 control-label"><?= lang('organization') ?> </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->organization;
                                    }
                                    ?>" name="organization">
                                </div>
                                <label class="col-lg-2 control-label"><?= lang('lead_source') ?> </label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <select name="lead_source_id" class="form-control select_box"
                                                style="width: 100%" required="">
                                            <?php
                                            $lead_source_info = $this->db->order_by('lead_source_id', 'DESC')->get('tbl_lead_source')->result();
                                            if (!empty($lead_source_info)) {
                                                foreach ($lead_source_info as $v_lead_source) {
                                                    ?>
                                                    <option value="<?= $v_lead_source->lead_source_id ?>"
                                                        <?= (!empty($leads_info) && $leads_info->lead_source_id == $v_lead_source->lead_source_id ? 'selected' : '') ?>>
                                                        <?= $v_lead_source->lead_source ?></option>
                                                    <?php
                                                }
                                            }
                                            $_created = can_action('128', 'created');
                                            ?>
                                        </select>
                                        <?php if (!empty($_created)) { ?>
                                            <div class="input-group-addon"
                                                 title="<?= lang('new') . ' ' . lang('lead_source') ?>"
                                                 data-toggle="tooltip"
                                                 data-placement="top">
                                                <a data-toggle="modal" data-target="#myModal"
                                                   href="<?= base_url() ?>admin/leads/lead_source"><i
                                                            class="fa fa-plus"></i></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-lg-2 control-label"><?= lang('contact_name') ?> <span
                                            class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->contact_name;
                                    }
                                    ?>" name="contact_name"
                                           required="">
                                </div>
                                <label class="col-lg-2 control-label"><?= lang('email') ?> <span
                                            class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <input type="email" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->email;
                                    }
                                    ?>" name="email"
                                           required="">
                                </div>
                            </div>
                            <div class="form-group">

                                <label class="col-lg-2 control-label"><?= lang('phone') ?><span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->phone;
                                    }
                                    ?>" name="phone"
                                           required="">
                                </div>
                                <label class="col-lg-2 control-label"><?= lang('mobile') ?><span
                                            class="text-danger">*</span>
                                </label>
                                <div class="col-lg-4">
                                    <input type="text" min="0" required="" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->mobile;
                                    }
                                    ?>"
                                           name="mobile"/>
                                </div>
                            </div>
                            <!-- End discount Fields -->
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('city') ?> </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->city;
                                    }
                                    ?>" name="city">
                                </div>

                                <label class="col-lg-2 control-label"><?= lang('state') ?> </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->state;
                                    }
                                    ?>" name="state">
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('country') ?></label>
                                <div class="col-lg-4">
                                    <select name="country" class="form-control person select_box" style="width: 100%">
                                        <optgroup label="Default Country">
                                            <?php if (!empty($leads_info->country)) { ?>
                                                <option value="<?= $leads_info->country ?>"><?= $leads_info->country ?>
                                                </option>
                                            <?php } else { ?>
                                                <option value="<?= $this->config->item('company_country') ?>">
                                                    <?= $this->config->item('company_country') ?></option>
                                            <?php } ?>
                                        </optgroup>
                                        <optgroup label="<?= lang('other_countries') ?>">
                                            <?php
                                            $countries = $this->db->get('tbl_countries')->result();
                                            if (!empty($countries)) : foreach ($countries as $country) :
                                                ?>
                                                <option value="<?= $country->value ?>"><?= $country->value ?></option>
                                            <?php
                                            endforeach;
                                            endif;
                                            ?>
                                        </optgroup>
                                    </select>
                                </div>
                                <label class="col-lg-2 control-label"><?= lang('address') ?> </label>
                                <div class="col-lg-4">
                                    <textarea name="address" class="form-control"><?php
                                        if (!empty($leads_info)) {
                                            echo $leads_info->address;
                                        }
                                        ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">


                                <label class="col-lg-2 control-label"><?= lang('language') ?> </label>
                                <div class="col-lg-4">
                                    <select name="language" class="form-control select_box" style="width: 100%">
                                        <?php

                                        foreach ($languages as $lang) : ?>
                                            <option value="<?= $lang->name ?>" <?php
                                            if (!empty($leads_info->language) && $leads_info->language == $lang->name) {
                                                echo 'selected';
                                            } elseif (empty($leads_info->language) && $this->config->item('language') == $lang->name) {
                                                echo 'selected';
                                            } ?>>
                                                <?= ucfirst($lang->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <label class="col-lg-2 control-label"><?= lang('twitter_profile_link') ?> </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->twitter;
                                    }
                                    ?>" name="twitter">
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('skype_id') ?> </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->skype;
                                    }
                                    ?>" name="skype">
                                </div>

                                <label class="col-lg-2 control-label"><?= lang('facebook_profile_link') ?> </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?php
                                    if (!empty($leads_info)) {
                                        echo $leads_info->facebook;
                                    }
                                    ?>" name="facebook">
                                </div>


                            </div>
                            <?php if (empty($leads_info)) { ?>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><?= lang('contacted_today') ?></label>
                                    <div class="col-lg-4">
                                        <div class="checkbox c-checkbox">
                                            <label class="needsclick">
                                                <input type="hidden" value="off" name="contacted_today"/>
                                                <input type="checkbox" <?php
                                                echo "checked=\"checked\"";
                                                ?> name="contacted_today"
                                                       id="showhide">
                                                <span class="fa fa-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="" id="shresult" style="display: none">
                                        <label class="col-lg-2 control-label"><?= lang('date_contacted') ?></label>
                                        <div class="col-lg-4">
                                            <div class="input-group">
                                                <input type="text" name="last_contact"
                                                       class="form-control datetimepicker"
                                                       value="<?php
                                                       if (!empty($leads_info->last_contact)) {
                                                           echo $leads_info->last_contact;
                                                       } else {
                                                           echo date('Y-m-d H:i');
                                                       }
                                                       ?>"
                                                       data-date-format="<?= config_item('date_picker_format'); ?>">
                                                <div class="input-group-addon">
                                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"><?= lang('last_contact') ?></label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input type="text" name="last_contact" class="form-control datetimepicker"
                                                   value="<?php
                                                   if (!empty($leads_info->last_contact)) {
                                                       echo $leads_info->last_contact;
                                                   }
                                                   ?>"
                                                   data-date-format="<?= config_item('date_picker_format'); ?>">
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="field-1" class="col-lg-2 control-label"><?= lang('tags') ?>
                                </label>
                                <div class="col-lg-4">
                                    <input type="text" name="tags" data-role="tagsinput" class="form-control"
                                           value="<?php
                                           if (!empty($leads_info->tags)) {
                                               echo $leads_info->tags;
                                           }
                                           ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('short_note') ?> </label>
                                <div class="col-lg-10">
                                    <textarea name="notes" class="form-control textarea"><?php
                                        if (!empty($leads_info)) {
                                            echo $leads_info->notes;
                                        }
                                        ?></textarea>
                                </div>
                            </div>
                            <?php
                            if (!empty($leads_info)) {
                                $leads_id = $leads_info->leads_id;
                                $permissionL = $leads_info->permission;
                            } else {
                                $leads_id = null;
                                $permissionL = null;
                            }
                            ?>
                            <?= custom_form_Fields(5, $leads_id, true); ?>


                            <?= get_permission(2, 9, $assign_user, $permissionL, lang('assigned_to')); ?>


                            <?php if (empty($leads_info->converted_client_id) || $leads_info->converted_client_id == 0) { ?>
                                <div class="btn-bottom-toolbar text-right">
                                    <?php
                                    if (!empty($leads_info)) { ?>
                                        <button type="submit"
                                                class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                        <button type="button" onclick="goBack()"
                                                class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                                    <?php } else {
                                        ?>
                                        <button type="submit"
                                                class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                                    <?php }
                                    ?>
                                </div>
                            <?php } ?>
                    </form>
                </div>
                <?php } else { ?>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
</div>
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datetimepicker/jquery.datetimepicker.min.css">
<?php include_once 'assets/plugins/datetimepicker/jquery.datetimepicker.full.php'; ?>
<script type="text/javascript">
    init_datepicker();

    // Date picker init with selected timeformat from settings
    function init_datepicker() {
        var datetimepickers = $('.datetimepicker');
        if (datetimepickers.length == 0) {
            return;
        }
        var opt_time;
        // Datepicker with time
        $.each(datetimepickers, function () {
            opt_time = {
                lazyInit: true,
                scrollInput: false,
                format: 'Y-m-d H:i',
                autoclose: true,
                endDate: "today",
                maxDate: "today"
            };

            opt_time.formatTime = 'H:i';
            // Check in case the input have date-end-date or date-min-date
            var max_date = $(this).data('date-end-date');
            var min_date = $(this).data('date-min-date');
            if (max_date) {
                opt_time.maxDate = max_date;
            }
            if (min_date) {
                opt_time.minDate = min_date;
            }
            // Init the picker
            $(this).datetimepicker(opt_time);
        });
    }
</script>