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
            <h4 class="modal-title" id="myModalLabel"><?= lang('new_promotion') ?></h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <form role="form" id="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" action="<?php echo base_url(); ?>admin/promotion/save_promotion/<?= (!empty($promotions->id) ? $promotions->id : ''); ?>" method="post" class="form-horizontal form-groups-bordered">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('employee') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select name="user_id" class="form-control select_box" style="width: 100%" required>
                            <?php if (!empty($all_employee)) : ?>
                                <?php foreach ($all_employee as $dept_name => $v_all_employee) : ?>
                                    <optgroup label="<?php echo $dept_name; ?>">
                                        <?php if (!empty($v_all_employee)) : foreach ($v_all_employee as $v_employee) : ?>
                                                <option value="<?php echo $v_employee->user_id; ?>" <?php
                                                                                                    echo $v_employee->user_id == !empty($promotions->user_id) ? 'selected' : '';

                                                                                                    ?>><?php echo $v_employee->fullname ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('designation') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select name="designation_id" class="form-control select_box" style="width: 100%" required>
                            <option value=""><?= lang('select') . ' ' . lang('designation') ?></option>
                            <?php if (!empty($all_department_info)) : foreach ($all_department_info as $dept_name => $v_department_info) : ?>
                                    <?php if (!empty($v_department_info)) :
                                        if (!empty($all_dept_info[$dept_name]->deptname)) {
                                            $deptname = $all_dept_info[$dept_name]->deptname;
                                        } else {
                                            $deptname = lang('undefined_department');
                                        }
                                    ?>
                                        <optgroup label="<?php echo $deptname; ?>">
                                            <?php foreach ($v_department_info as $designation) : ?>
                                                <option value="<?php echo $designation->designations_id; ?>" <?php
                                                                                                                echo $designation->designations_id == !empty($promotions->designation_id) ? 'selected' : '';
                                                                                                                ?>><?php echo $designation->designations ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </select>

                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('promotion_title') ?> <span class="required">*</span></label>

                    <div class="col-sm-8">
                        <input type="text" required name="promotion_title" value="<?= (!empty($promotions->promotion_title) ? $promotions->promotion_title : ''); ?>" class="form-control" requried />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?= lang('promotion_date') ?> <span class="required">*</span></label>

                    <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" name="promotion_date" required placeholder="<?= lang('enter') . ' ' . lang('promotion_date') ?>" class="form-control start_date" value="<?php
                                                                                                                                                                                if (!empty($promotions->promotion_date)) {
                                                                                                                                                                                    echo $promotions->promotion_date;
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
                        <textarea name="description" class="form-control textarea"><?= (!empty($promotions->description) ? $promotions->description : ''); ?></textarea>
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