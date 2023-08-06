<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.css">
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/plugins/dropzone/dropzone.custom.js"></script>

<?php
$created = can_action('100', 'created');
$edited = can_action('100', 'edited');
if (!empty($created) || !empty($edited)) {
?>
    <div class="panel panel-custom">
        <div class="panel-heading">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('new_resignation') ?></h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <form role="form" id="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" action="<?php echo base_url(); ?>admin/resignation/save_resignation/<?= (!empty($resignations->id) ? $resignations->id : ''); ?>" method="post" class="form-horizontal form-groups-bordered">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('employee_name') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select name="employee_id" class="form-control select_box" style="width: 100%" required>
                            <?php if (!empty($all_employee)) : ?>
                                <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                    <optgroup label="<?php echo $dept_name; ?>">
                                        <?php if (!empty($v_all_employee)) : foreach ($v_all_employee as $v_employee) : ?>
                                                <option value="<?php echo $v_employee->user_id; ?>" <?php
                                                                                                    echo $v_employee->user_id == !empty($resignations->employee_id) ? 'selected' : '';

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
                    <label class="col-sm-3 control-label"><?= lang('resignation_date') ?> <span class="required">*</span></label>

                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" name="notice_date" required placeholder="<?= lang('enter') . ' ' . lang('notice_date') ?>" class="form-control start_date" value="<?php
                                                                                                                                                                        if (!empty($resignations->notice_date)) {
                                                                                                                                                                            echo $resignations->notice_date;
                                                                                                                                                                        }
                                                                                                                                                                        ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('last_working_date') ?> <span class="required">*</span></label>

                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" required name="resignation_date" placeholder="<?= lang('enter') . ' ' . lang('resignation_date') ?>" class="form-control start_date" value="<?php
                                                                                                                                                                                    if (!empty($resignations->resignation_date)) {
                                                                                                                                                                                        echo $resignations->resignation_date;
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('description') ?></label>

                    <div class="col-sm-8">
                        <textarea name="description" class="form-control textarea"><?= (!empty($resignations->description) ? $resignations->description : ''); ?></textarea>
                    </div>
                </div>


                <!--hidden input values -->
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-2">
                        <button type="submit" id="file-save-button" class="btn btn-primary btn-block"><?= lang('save') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>