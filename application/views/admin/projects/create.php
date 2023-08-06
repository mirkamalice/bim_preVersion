<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>

<div id="all_projects_state_report_div">

</div>

<?= message_box('success'); ?>
<?= message_box('error');
$all_customer_group = $this->db->where('type', 'projects')->order_by('customer_group_id', 'DESC')->get('tbl_customer_group')->result();
$all_client = $this->items_model->get_permission('tbl_client');
$created = can_action('57', 'created');
$edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="row">
    <div class="col-sm-12">

        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                        href="<?= base_url('admin/projects') ?>"><?= lang('all') . ' ' . lang($tab) ?></a>
                </li>

                <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                        href="<?= base_url('admin/projects/create') ?>"><?= lang('new_project') ?></a>
                </li>
                <li><a class="import"
                        href="<?= base_url() ?>admin/projects/import"><?= lang('import') . ' ' . lang('project') ?></a>
                </li>
            </ul>
            <div class="tab-content bg-white">

                <?php
                    $client_project = $this->uri->segment(4);
                    if ($client_project == 'client_project') {
                        $client_id = $this->uri->segment(5);
                    }
                    if (!empty($project_info)) {
                        $projects_id = $project_info->project_id;
                    } else {
                        $projects_id = null;
                    }
                    ?>
                <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                    <?php echo form_open(base_url('admin/projects/saved_project/' . $projects_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                    <div class="panel-body">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('project_no') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" value="<?php
                                                                                        if (!empty($project_info)) {
                                                                                            echo $project_info->project_no;
                                                                                        } else {
                                                                                            if (empty(config_item('projects_number_format'))) {
                                                                                                echo config_item('projects_prefix');
                                                                                            }
                                                                                            echo $this->items_model->generate_projects_number();
                                                                                        }
                                                                                        ?>" name="project_no">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('project_name') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" value="<?php
                                                                                        if (!empty($project_info)) {
                                                                                            echo $project_info->project_name;
                                                                                        }
                                                                                        ?>" name="project_name"
                                        required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label
                                    class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('categories') ?></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <select name="category_id" class="form-control select_box" style="width: 100%">
                                            <?php
                                                if (!empty($all_customer_group)) {
                                                    foreach ($all_customer_group as $customer_group) : ?>
                                            <option value="<?= $customer_group->customer_group_id ?>"
                                                <?php
                                                                                                                    if (!empty($project_info->category_id) && $project_info->category_id == $customer_group->customer_group_id) {
                                                                                                                        echo 'selected';
                                                                                                                    } ?>>
                                                <?= $customer_group->customer_group; ?></option>
                                            <?php endforeach;
                                                }
                                                $created = can_action('125', 'created');
                                                ?>
                                        </select>
                                        <?php if (!empty($created)) { ?>
                                        <div class="input-group-addon"
                                            title="<?= lang('new') . ' ' . lang('categories') ?>" data-toggle="tooltip"
                                            data-placement="top">
                                            <a data-toggle="modal" data-target="#myModal"
                                                href="<?= base_url() ?>admin/projects/new_category"><i
                                                    class="fa fa-plus"></i></a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('select_client') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <select name="client_id" class="form-control select_box" style="width: 100%"
                                            required="">
                                            <option value=""><?= lang('select_client') ?></option>
                                            <?php
                                                $all_client = $this->db->get('tbl_client')->result();
                                                if (!empty($all_client)) {
                                                    foreach ($all_client as $v_client) {
                                                ?>
                                            <option value="<?= $v_client->client_id ?>" <?php
                                                                                                    if (!empty($project_info) && $project_info->client_id == $v_client->client_id) {
                                                                                                        echo 'selected';
                                                                                                    } else if (!empty($client_id) && $client_id == $v_client->client_id) {
                                                                                                        echo 'selected';
                                                                                                    }
                                                                                                    ?>>
                                                <?= $v_client->name ?></option>
                                            <?php
                                                    }
                                                }
                                                $acreated = can_action('4', 'created');
                                                ?>
                                        </select>
                                        <?php if (!empty($acreated)) { ?>
                                        <div class="input-group-addon" title="<?= lang('new') . ' ' . lang('client') ?>"
                                            data-toggle="tooltip" data-placement="top">
                                            <a data-toggle="modal" data-target="#myModal"
                                                href="<?= base_url() ?>admin/client/new_client"><i
                                                    class="fa fa-plus"></i></a>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <script src="<?= base_url() ?>assets/js/jquery-ui.js"></script>
                            <?php $direction = $this->session->userdata('direction');
                                if (!empty($direction) && $direction == 'rtl') {
                                    $RTL = 'on';
                                } else {
                                    $RTL = config_item('RTL');
                                }
                                ?>
                            <?php
                                if (!empty($RTL)) { ?>
                            <!-- bootstrap-editable -->
                            <script type="text/javascript"
                                src="<?= base_url() ?>assets/plugins/jquery-ui/jquery.ui.slider-rtl.js">
                            </script>
                            <?php }
                                ?>
                            <style>
                            .ui-widget.ui-widget-content {
                                border: 1px solid #dde6e9;
                            }

                            .ui-corner-all,
                            .ui-corner-bottom,
                            .ui-corner-left,
                            .ui-corner-bl {
                                border: 7px solid #28a9f1;
                            }

                            .ui-widget-content {
                                border: 1px solid #dddddd;
                                /*background: #E1E4E9;*/
                                color: #333333;
                            }

                            .ui-slider {
                                position: relative;
                                text-align: left;
                            }

                            .ui-slider-horizontal {
                                height: 1em;
                            }

                            .ui-state-default,
                            .ui-widget-content .ui-state-default,
                            .ui-widget-header .ui-state-default,
                            .ui-button,
                            html .ui-button.ui-state-disabled:hover,
                            html .ui-button.ui-state-disabled:active {
                                border: 1px solid #1797be;
                                background: #1797be;
                                font-weight: normal;
                                color: #454545;
                            }

                            .ui-slider-horizontal .ui-slider-handle {
                                top: -.3em;
                                margin-left: -.1em;
                                ;
                                margin-right: -.1em;
                                ;
                            }

                            .ui-slider .ui-slider-handle:hover {
                                background: #1797be;
                            }

                            .ui-slider .ui-slider-handle {
                                position: absolute;
                                z-index: 2;
                                width: 1.2em;
                                ;
                                height: 1.5em;
                                cursor: default;
                                -ms-touch-action: none;
                                touch-action: none;

                            }

                            .ui-state-disabled,
                            .ui-widget-content .ui-state-disabled,
                            .ui-widget-header .ui-state-disabled {
                                opacity: .35;
                                filter: Alpha(Opacity=35);
                                background-image: none;
                            }

                            .ui-state-disabled {
                                cursor: default !important;
                                pointer-events: none;
                            }

                            .ui-slider.ui-state-disabled .ui-slider-handle,
                            .ui-slider.ui-state-disabled .ui-slider-range {
                                filter: inherit;
                            }

                            .ui-slider-range,
                            .ui-widget-header,
                            .ui-slider-handle:before,
                            .list-group-item.active,
                            .list-group-item.active:hover,
                            .list-group-item.active:focus,
                            .icon-frame {
                                background-image: none;
                                background: #28a9f1;
                            }
                            </style>
                            <?php
                                if (!empty($project_info)) {
                                    $value = $this->items_model->get_project_progress($project_info->project_id);
                                } else {
                                    $value = 0;
                                }
                                ?>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?php echo lang('progress'); ?> </label>
                                <div class="col-lg-8">
                                    <?php echo form_hidden('progress', $value); ?>
                                    <div class="project_progress_slider project_progress_slider_horizontal mbot15">
                                    </div>

                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <div class="">
                                                <div class="pull-left mt">
                                                    <?php echo lang('progress'); ?>
                                                    <span class="label_progress "><?php echo $value; ?>%</span>
                                                </div>
                                                <div class="checkbox c-checkbox pull-right" data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="<?php echo lang('calculate_progress_through_tasks'); ?>">
                                                    <label class="needsclick">
                                                        <input class="select_one" type="checkbox" <?php if ((!empty($project_info) && $project_info->calculate_progress == 'through_tasks')) {
                                                                                                            echo 'checked';
                                                                                                        } ?>
                                                            name="calculate_progress" value="through_tasks"
                                                            id="progress_from_tasks">
                                                        <span class="fa fa-check"></span>
                                                        <small><?php echo lang('through_tasks'); ?></small>
                                                    </label>
                                                </div>
                                                <div class="checkbox c-checkbox pull-right" data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="<?php echo lang('calculate_progress_through_project_hours'); ?>">
                                                    <label class="needsclick">
                                                        <input class="select_one" type="checkbox" <?php if ((!empty($project_info) && $project_info->calculate_progress == 'through_project_hours')) {
                                                                                                            echo 'checked';
                                                                                                        } ?>
                                                            name="calculate_progress" value="through_project_hours"
                                                            id="through_project_hours">
                                                        <span class="fa fa-check"></span>
                                                        <small><?php echo lang('through_project_hours'); ?></small>
                                                    </label>
                                                </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <script>
                            $(document).ready(function() {
                                var progress_input = $('input[name="progress"]');
                                <?php if ((!empty($project_info) && $project_info->calculate_progress == 'through_project_hours')) { ?>
                                var progress_from_tasks = $('#through_project_hours');
                                <?php } elseif ((!empty($project_info) && $project_info->calculate_progress == 'through_tasks')) { ?>
                                var progress_from_tasks = $('#progress_from_tasks');
                                <?php } else { ?>
                                var progress_from_tasks = $('.select_one');
                                <?php } ?>

                                var progress = progress_input.val();
                                $('.project_progress_slider').slider({
                                    range: "min",
                                    <?php
                                            if (!empty($RTL)) { ?>
                                    isRTL: true,
                                    <?php }
                                            ?>
                                    min: 0,
                                    max: 100,
                                    value: progress,
                                    disabled: progress_from_tasks.prop('checked'),
                                    slide: function(event, ui) {
                                        progress_input.val(ui.value);
                                        $('.label_progress').html(ui.value + '%');
                                    }
                                });
                                progress_from_tasks.on('change', function() {
                                    var _checked = $(this).prop('checked');
                                    $('.project_progress_slider').slider({
                                        disabled: _checked,
                                    });
                                });
                            });
                            </script>

                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('start_date') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input required type="text" name="start_date" class="form-control start_date"
                                            value="<?php
                                                                                                                                    if (!empty($project_info->start_date)) {
                                                                                                                                        echo date('Y-m-d', strtotime($project_info->start_date));
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
                                <label class="col-lg-3 control-label"><?= lang('end_date') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input required type="text" name="end_date" class="form-control end_date"
                                            value="<?php
                                                                                                                                if (!empty($project_info->end_date)) {
                                                                                                                                    echo date('Y-m-d', strtotime($project_info->end_date));
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
                                <label class="col-lg-3 control-label"><?= lang('billing_type') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <select name="billing_type" onchange="get_billing_value(this.value)"
                                        class="form-control select_box" style="width: 100%" required="">
                                        <option <?php
                                                    if (!empty($project_info->billing_type)) {
                                                        echo $project_info->billing_type == 'fixed_rate' ? 'selected' : null;
                                                    } ?> value="fixed_rate"><?= lang('fixed_rate') ?></option>
                                        <option <?php
                                                    if (!empty($project_info->billing_type)) {
                                                        echo $project_info->billing_type == 'project_hours' ? 'selected' : null;
                                                    } ?> value="project_hours">
                                            <?= lang('only') . ' ' . lang('project_hours') ?>
                                        </option>
                                        <option <?php
                                                    if (!empty($project_info->billing_type)) {
                                                        echo $project_info->billing_type == 'tasks_hours' ? 'selected' : null;
                                                    } ?> value="tasks_hours">
                                            <?= lang('only') . ' ' . lang('tasks_hours') ?></option>
                                        <option <?php
                                                    if (!empty($project_info->billing_type)) {
                                                        echo $project_info->billing_type == 'tasks_and_project_hours' ? 'selected' : null;
                                                    } ?> value="tasks_and_project_hours">
                                            <?= lang('tasks_and_project_hours') ?>
                                        </option>
                                    </select>
                                    <small class="based_on_tasks_hour"
                                        <?php
                                                                            if (!empty($project_info) && $project_info->billing_type == 'tasks_hours' || !empty($project_info) && $project_info->billing_type == 'tasks_and_project_hours') {
                                                                                echo 'style="display: block;"';
                                                                            } else {
                                                                                echo 'style="display: none;"';
                                                                            } ?>><?php echo lang('based_on_hourly_rate') ?></small>
                                </div>
                            </div>
                            <div class="form-group fixed_rate " <?php
                                                                    if (!empty($project_info) && $project_info->billing_type == 'fixed_rate') {
                                                                        echo 'style="display: block;"';
                                                                    } elseif (!empty($project_info) && $project_info->billing_type != 'fixed_rate') {
                                                                        echo 'style="display: none;"';
                                                                    }
                                                                    ?>>
                                <label class="col-lg-3 control-label"><?= lang('fixed_price') ?></label>
                                <div class="col-lg-8">
                                    <input data-parsley-type="number" type="text" class="form-control fixed_rate"
                                        value="<?php
                                                                                                                                if (!empty($project_info->project_cost)) {
                                                                                                                                    echo $project_info->project_cost;
                                                                                                                                }
                                                                                                                                ?>" placeholder="50" name="project_cost">
                                </div>
                            </div>

                            <div class="form-group hourly_rate " <?php
                                                                        if (!empty($project_info) && $project_info->billing_type == 'project_hours' || !empty($project_info) && $project_info->billing_type == 'tasks_and_project_hours') {
                                                                            echo 'style="display: block;"';
                                                                        } elseif (!empty($project_info) && $project_info->billing_type == 'fixed_rate' || !empty($project_info) && $project_info->billing_type == 'tasks_hours') {
                                                                            echo 'style="display: none;"';
                                                                        }
                                                                        ?>>
                                <label class="col-lg-3 control-label"><?= lang('project_hourly_rate') ?></label>
                                <div class="col-lg-8">
                                    <input data-parsley-type="number" type="text" class="form-control hourly_rate"
                                        value="<?php
                                                                                                                                if (!empty($project_info->hourly_rate)) {
                                                                                                                                    echo $project_info->hourly_rate;
                                                                                                                                }
                                                                                                                                ?>"
                                        placeholder="50" name="hourly_rate">
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('estimate_hours') ?></label>
                                <div class="col-lg-8">
                                    <input type="number" step="0.01" value="<?php
                                                                                if (!empty($project_info->estimate_hours)) {
                                                                                    $result = explode(':', $project_info->estimate_hours);
                                                                                    echo $result[0] . '.' . $result[1];
                                                                                }
                                                                                ?>" class="form-control"
                                        name="estimate_hours">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('status') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-8">
                                    <select name="project_status" class="form-control select_box" style="width: 100%"
                                        required="">
                                        <option <?php
                                                    if (!empty($project_info->project_status)) {
                                                        echo $project_info->project_status == 'started' ? 'selected' : null;
                                                    } ?> value="started"><?= lang('started') ?></option>
                                        <option <?php
                                                    if (!empty($project_info->project_status)) {
                                                        echo $project_info->project_status == 'in_progress' ? 'selected' : null;
                                                    } ?> value="in_progress"><?= lang('in_progress') ?></option>
                                        <option <?php
                                                    if (!empty($project_info->project_status)) {
                                                        echo $project_info->project_status == 'on_hold' ? 'selected' : null;
                                                    } ?> value="on_hold"><?= lang('on_hold') ?></option>
                                        <option <?php
                                                    if (!empty($project_info->project_status)) {
                                                        echo $project_info->project_status == 'cancel' ? 'selected' : null;
                                                    } ?> value="cancel"><?= lang('cancel') ?></option>
                                        <option <?php
                                                    if (!empty($project_info->project_status)) {
                                                        echo $project_info->project_status == 'completed' ? 'selected' : null;
                                                    } ?> value="completed"><?= lang('completed') ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('demo_url') ?></label>
                                <div class="col-lg-8">
                                    <input type="text" value="<?php
                                                                    if (!empty($project_info->demo_url)) {
                                                                        echo $project_info->demo_url;
                                                                    }
                                                                    ?>" class="form-control"
                                        placeholder="http://www.demourl.com" name="demo_url">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('tags') ?></label>
                                <div class="col-lg-7">
                                    <input type="text" name="tags" data-role="tagsinput" class="form-control"
                                        value="<?php
                                                                                                                            if (!empty($project_info->tags)) {
                                                                                                                                echo $project_info->tags;
                                                                                                                            }
                                                                                                                            ?>">
                                </div>
                            </div>
                            <?php
                                if (!empty($project_info)) {
                                    $project_id = $project_info->project_id;
                                } else {
                                    $project_id = null;
                                }
                                ?>
                            <?= custom_form_Fields(4, $project_id); ?>

                            <?php
                                $permissionL = null;
                                if (!empty($project_info->permission)) {
                                    $permissionL = $project_info->permission;
                                }
                                ?>
                            <?= get_permission(3, 8, $assign_user, $permissionL, lang('assined_to')); ?>

                        </div>
                        <div class="col-sm-5">
                            <!-- checkbox -->
                            <?php
                                $project_permissions = $this->db->get('tbl_project_settings')->result();
                                if (!empty($project_info->project_settings)) {
                                    $current_permissions = $project_info->project_settings;
                                    if ($current_permissions == NULL) {
                                        $current_permissions = '{"settings":"on"}';
                                    }
                                    $get_permissions = json_decode($current_permissions);
                                }

                                foreach ($project_permissions as $v_permissions) {
                                ?>
                            <div class="checkbox c-checkbox">
                                <label class="needsclick">
                                    <input name="<?= $v_permissions->settings_id ?>"
                                        value="<?= $v_permissions->settings ?>"
                                        <?php
                                                                                                                                        if (!empty($project_info->project_settings)) {
                                                                                                                                            if (in_array($v_permissions->settings, $get_permissions)) {
                                                                                                                                                echo "checked=\"checked\"";
                                                                                                                                            }
                                                                                                                                        } else {
                                                                                                                                            echo "checked=\"checked\"";
                                                                                                                                        }
                                                                                                                                        ?> type="checkbox">
                                    <span class="fa fa-check"></span>
                                    <?= lang($v_permissions->settings) ?>
                                </label>
                            </div>
                            <hr class="mt-sm mb-sm" />
                            <?php } ?>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><?= lang('description') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-10">

                                    <textarea style="" name="description" class="form-control textarea_"
                                        placeholder="<?= lang('description') ?>"><?php
                                                                                                                                                        if (!empty($project_info->description)) {
                                                                                                                                                            echo $project_info->description;
                                                                                                                                                        }
                                                                                                                                                        ?></textarea>
                                </div>
                            </div>
                            <div class="btn-bottom-toolbar text-right">
                                <?php
                                    if (!empty($project_info)) { ?>
                                <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                <button type="button" onclick="goBack()"
                                    class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                                <?php } else {
                                    ?>
                                <button type="submit" class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                                <?php }
                                    ?>
                            </div>
                        </div>


                    </div>
                    <?php echo form_close(); ?>
                </div>
                <?php } else { ?>
            </div>
            <?php } ?>