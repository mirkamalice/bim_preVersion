<div class="row">
    <div class="col-sm-12" data-offset="0">
        <div class="panel panel-custom" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title">
                    <strong><?= lang('attendance_report') ?></strong>
                </div>
            </div>
            <div class="panel-body">
                <form id="attendance-form" role="form" enctype="multipart/form-data"
                      action="<?php echo base_url(); ?>admin/attendance/attendance_report" method="post"
                      class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('department') ?><span
                                    class="required">*</span></label>
                        <div class="col-sm-5">
                            <select required name="departments_id" class="form-control select_box">
                                <?php if (!empty($all_department)) : foreach ($all_department as $id => $department) :
                                    if (!empty($department)) {
                                        $deptname = $department;
                                    } else {
                                        $deptname = lang('undefined_department');
                                    }
                                    ?>
                                    <option value="<?php echo $id; ?>" <?php if (!empty($departments_id)) : ?><?php echo $id == $departments_id ? 'selected ' : '' ?><?php endif; ?>>
                                        <?php echo $deptname ?>
                                    </option>
                                <?php
                                endforeach;
                                endif;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"><?= lang('type') ?><span
                                    class="required">*</span></label>
                        <div class="col-sm-5">
                            <select required name="search_type" id="search_type" class="form-control ">
                                <option value="month" <?php if (!empty($search_type)) : ?><?php echo 'month' == $search_type ? 'selected ' : '' ?><?php endif; ?>><?= lang('by') . ' ' . lang('month') ?></option>
                                <option value="period" <?php if (!empty($search_type)) : ?><?php echo 'period' == $search_type ? 'selected ' : '' ?><?php endif; ?>><?= lang('by') . ' ' . lang('period') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="by_month"
                         style="display: <?= !empty($search_type) && $search_type == 'month' || empty($search_type) ? 'block' : 'none' ?>">
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?= lang('month') ?><span
                                        class="required"> *</span></label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type="text" class="form-control monthyear" value="<?php
                                    if (!empty($date)) {
                                        echo date('Y-n', strtotime($date));
                                    }
                                    ?>" name="date">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="by_period"
                         style="display: <?= !empty($search_type) && $search_type == 'period' ? 'block' : 'none' ?>">
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('start_date') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="text" name="start_date" class="form-control start_date"
                                           value="<?php
                                           if (!empty($start_date)) {
                                               echo date('Y-m-d', strtotime($start_date));
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
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <input type="text" name="end_date" class="form-control end_date"
                                           value="<?php
                                           if (!empty($end_date)) {
                                               echo date('Y-m-d', strtotime($end_date));
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
                    <div class="form-group">
                        <label for="field-1" class="col-sm-3 control-label"></label>
                        <div class="col-sm-5 ">
                            <button type="submit" id="sbtn" class="btn btn-primary"><?= lang('search') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>