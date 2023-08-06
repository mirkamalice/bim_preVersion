<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action_by_label('workplace', 'created');
$edited = can_action_by_label('workplace', 'edited');
?>
<?php if (!empty($created) || !empty($edited)) {
    if (!empty($workplace_info)) {
        $workplace_id = $workplace_info->workplace_id;
    } else {
        $workplace_id = null;
    }
?>
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="<?= base_url('admin/attendance/workplace') ?>"><?= lang('manage_workplace') ?></a></li>
            <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="<?= base_url('admin/attendance/new_workplace') ?>"><?= lang('new_workplace') ?></a></li>
        </ul>
        <div class="tab-content bg-white">
            <input type="hidden" name="user_id">
            <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="create">
                <?php echo form_open(base_url('admin/attendance/save_workplace/' . $workplace_id), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'data-parsley-validate' => '', 'role' => 'form')); ?>
                <div class="form-group">
                    <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('name') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-6 col-md-6 col-sm-7">
                        <input type="text" class="form-control" value="<?php
                                                                        if (!empty($workplace_info)) {
                                                                            echo $workplace_info->location_name;
                                                                        }
                                                                        ?>" name="location_name" required="">
                    </div>

                </div>

                <div class="form-group">
                    <label class="col-lg-3 col-md-3 col-sm-4 control-label"><?= lang('workplace_details') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-6 col-md-6 col-sm-7">
                        <textarea class="form-control" name="workplace_details" required=""><?php if (!empty($workplace_info)) {  echo $workplace_info->workplace_details;
                                                                                            }
                                                                                            ?></textarea>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('workplace') . ' ' . lang('location'); ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input type="text" class="form-control inputText2" value="<?php
                                                                                        if (!empty($workplace_info)) {
                                                                                            echo $workplace_info->workplace_location;
                                                                                        }
                                                                                        ?>" id="searchTextField" name="workplace_location">
                            <div class="input-group-addon" id="get_location">
                                <i title="Get Current Location" style="cursor: pointer;" class="fa fa-map-marker"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" class="form-control" value="<?= set_value('lat', config_item('office_lat')); ?>" name="office_lat" id="lat">
                <input type="hidden" class="form-control" value="<?= set_value('long', config_item('office_long')); ?>" name="office_long" id="long">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('allowed_distance') . ' ' . lang('for') . ' ' . lang('clock'); ?>
                        <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <input type="text" class="form-control" value="<?php
                                                                        if (!empty($workplace_info)) {
                                                                            echo $workplace_info->allowed_radius;
                                                                        }
                                                                        ?>" name="allowed_radius" id="dist_from_office">
                    </div>
                </div>


                <!-- End discount Fields -->

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('employee') . ' ' . lang('name') ?></label>

                    <div class="col-sm-6">
                        <select class="by_employee form-control selectpicker" id="teamPositionFilter" data-live-search="true" multiple style="width: 100%" name="user_id[]">
                            <?php
                            $all_employee = $this->attendance_model->get_all_employee();
                            if (!empty($all_employee)) : ?>
                                <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                    <optgroup label="<?php echo $dept_name; ?>">
                                        <?php if (!empty($v_all_employee)) : foreach ($v_all_employee as $v_employee) : ?>
                                                <option value="<?php echo $v_employee->user_id; ?>" <?php
                                                                if (!empty($workplace_info->user_id)) {
                                                                    echo ($v_employee->user_id == $workplace_info->user_id ? 'selected':'');
                                                                }
                                                                ?>><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )' ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 col-md-3 col-sm-4 control-label"></label>
                    <div class="col-lg-5 col-md-5 col-sm-7">
                        <button type="submit" class="btn btn-sm btn-primary"><?= lang('create_workplace') ?></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

<?php } ?>