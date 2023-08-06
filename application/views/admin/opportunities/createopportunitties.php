<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('56', 'created');
$edited = can_action('56', 'edited');
$deleted = can_action('56', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/opportunities') ?>"><?= lang('all_opportunities') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/opportunities/create') ?>"><?= lang('new_opportunities') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->

        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
            <form role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
                action="<?php echo base_url(); ?>admin/opportunities/saved_opportunity/<?php
                                                                                                                                                                                        if (!empty($opportunity_info)) {
                                                                                                                                                                                            echo $opportunity_info->opportunities_id;
                                                                                                                                                                                        }
                                                                                                                                                                                        ?>" method="post"
                class="form-horizontal  ">

                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?= lang('opportunity_name') ?> <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?php
                                                                                if (!empty($opportunity_info)) {
                                                                                    echo $opportunity_info->opportunity_name;
                                                                                }
                                                                                ?>" name="opportunity_name"
                                required="">
                        </div>
                        <label class="col-lg-2 control-label"><?= lang('stages') ?> </label>
                        <div class="col-lg-4">
                            <select name="stages" class="form-control select_box" style="width: 100%;" required="">
                                <option value="new"
                                    <?= (!empty($opportunity_info) && $opportunity_info->stages == 'new' ? 'selected' : '') ?>>
                                    <?= lang('new') ?></option>
                                <option value="qualification"
                                    <?= (!empty($opportunity_info) && $opportunity_info->stages == 'qualification' ? 'selected' : '') ?>>
                                    <?= lang('qualification') ?></option>
                                <option value="proposition"
                                    <?= (!empty($opportunity_info) && $opportunity_info->stages == 'proposition' ? 'selected' : '') ?>>
                                    <?= lang('proposition') ?></option>
                                <option value="won"
                                    <?= (!empty($opportunity_info) && $opportunity_info->stages == 'won' ? 'selected' : '') ?>>
                                    <?= lang('won') ?></option>
                                <option value="lost"
                                    <?= (!empty($opportunity_info) && $opportunity_info->stages == 'lost' ? 'selected' : '') ?>>
                                    <?= lang('lost') ?></option>
                                <option value="dead"
                                    <?= (!empty($opportunity_info) && $opportunity_info->stages == 'dead' ? 'selected' : '') ?>>
                                    <?= lang('dead') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?= lang('probability') ?> %</label>
                        <div class="col-lg-4">
                            <input name="probability" data-ui-slider="" type="text"
                                value="<?php if (!empty($opportunity_info->probability)) echo $opportunity_info->probability; ?>"
                                data-slider-min="0" data-slider-max="100" data-slider-step="1"
                                data-slider-value="<?php if (!empty($opportunity_info->probability)) echo $opportunity_info->probability; ?>"
                                data-slider-orientation="horizontal" class="slider slider-horizontal"
                                data-slider-id="red">


                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"><?= lang('close_date') ?></label>
                            <?php
                                if (!empty($opportunity_info)) {
                                    $close_date = jdate('Y-m-d', strtotime($opportunity_info->close_date));
                                    $next_action_date = jdate('Y-m-d', strtotime($opportunity_info->next_action_date));
                                } else {
                                    $close_date = jdate('Y-m-d');
                                    $next_action_date = jdate('Y-m-d');
                                }
                                ?>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <input class="form-control datepicker" type="text" value="<?= $close_date; ?>"
                                        name="close_date" data-date-format="<?= config_item('date_picker_format'); ?>">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="border-none">

                        <label for="field-1" class="col-sm-2 control-label"><?= lang('current_state') ?> <span
                                class="required">*</span></label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <select name="opportunities_state_reason_id" style="width: 100%" class="select_box"
                                    required="">
                                    <?php
                                        if (!empty($all_state)) {
                                            foreach ($all_state as $state => $opportunities_state) {
                                                if (!empty($state)) {
                                        ?>
                                    <optgroup label="<?= lang($state) ?>">
                                        <?php foreach ($opportunities_state as $v_state) { ?>
                                        <option value="<?= $v_state->opportunities_state_reason_id ?>"
                                            <?php
                                                                                                                            if (!empty($opportunity_info->opportunities_state_reason_id)) {
                                                                                                                                echo $v_state->opportunities_state_reason_id == $opportunity_info->opportunities_state_reason_id ? 'selected' : '';
                                                                                                                            }
                                                                                                                            ?>><?= $v_state->opportunities_state_reason ?>
                                        </option>
                                        <?php } ?>
                                    </optgroup>
                                    <?php
                                                }
                                            }
                                        }
                                        $created = can_action('129', 'created');
                                        ?>
                                </select>
                                <?php if (!empty($created)) { ?>
                                <div class="input-group-addon"
                                    title="<?= lang('new') . ' ' . lang('opportunities_state_reason') ?>"
                                    data-toggle="tooltip" data-placement="top">
                                    <a data-toggle="modal" data-target="#myModal"
                                        href="<?= base_url() ?>admin/opportunities/opportunities_state_reason"><i
                                            class="fa fa-plus"></i></a>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?= lang('expected_revenue') ?></label>
                        <div class="col-lg-4">

                            <input type="text" data-parsley-type="number" min="0" class="form-control" value="<?php
                                                                                                                    if (!empty($opportunity_info)) {
                                                                                                                        echo $opportunity_info->expected_revenue;
                                                                                                                    }
                                                                                                                    ?>"
                                name="expected_revenue">
                        </div>
                        <label class="col-lg-2 control-label"><?= lang('new_link') ?></label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?php
                                                                                if (!empty($opportunity_info)) {
                                                                                    echo $opportunity_info->new_link;
                                                                                }
                                                                                ?>" name="new_link" />
                        </div>

                    </div>
                    <!-- End discount Fields -->
                    <div class="form-group terms">

                        <label class="col-lg-2 control-label"><?= lang('next_action') ?> </label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" value="<?php
                                                                                if (!empty($opportunity_info)) {
                                                                                    echo $opportunity_info->next_action;
                                                                                }
                                                                                ?>" name="next_action">
                        </div>
                        <label class="col-lg-2 control-label"><?= lang('next_action_date') ?></label>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input class="form-control datepicker" type="text" value="<?= $next_action_date; ?>"
                                    name="next_action_date"
                                    data-date-format="<?= config_item('date_picker_format'); ?>">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar"></i></a>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label"><?= lang('short_note') ?> </label>
                        <div class="col-lg-8">
                            <textarea name="notes" class="form-control textarea"><?php
                                                                                        if (!empty($opportunity_info)) {
                                                                                            echo $opportunity_info->notes;
                                                                                        }
                                                                                        ?></textarea>
                        </div>
                    </div>
                    <?php
                        if (!empty($opportunity_info)) {
                            $opportunities_id = $opportunity_info->opportunities_id;
                        } else {
                            $opportunities_id = null;
                        }
                        ?>
                    <?= custom_form_Fields(8, $opportunities_id, true); ?>

                    <?php
                        $permissionL = null;
                        if (!empty($opportunities_info->permission)) {
                            $permissionL = $opportunities_info->permission;
                        }
                        ?>
                    <?= get_permission(2, 8, $assign_user, $permissionL, lang('who_responsible')); ?>


                    <div class="btn-bottom-toolbar text-right">
                        <?php
                            if (!empty($opportunity_info)) { ?>
                        <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                        <button type="button" onclick="goBack()"
                            class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                        <?php } else {
                            ?>
                        <button type="submit" class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                        <?php }
                            ?>
                    </div>
            </form>
        </div>
        <?php } else { ?>
    </div>
    <?php } ?>