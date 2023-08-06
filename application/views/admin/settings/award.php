<?php echo message_box('success') ?>

<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <form action="<?php echo base_url() ?>admin/settings/save_awardpoint" enctype="multipart/form-data"
              class="form-horizontal" method="post">
            <div class="panel panel-custom">
                <header class="panel-heading  "><?= lang('award points') . ' ' . lang('settings') ?></header>
                <div class="panel-body">
                    <input type="hidden" name="settings" value="<?= $load_setting ?>">
                    
                    <div class="well well-sm mt">
                        <label class="bb"><?= lang('Customer Award ') . ' ' . lang('Points') ?>
                            <span class="text-danger">*</span></label>
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"><?= lang('Each spent') . ' ' . lang('equal') ?>
                                        <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="client_spent_amount" class="form-control"
                                               style="width:260px" value="<?= config_item('client_spent_amount') ?>"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"><?= lang('award') . ' ' . lang('point') ?>
                                        <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="client_award_ponint" class="form-control"
                                               style="width:260px" value="<?= config_item('client_award_ponint') ?>"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="well well-sm mt">
                        <label class="bb"><?= lang('Staff  Award ') . ' ' . lang('Points') ?> <span class="text-danger">*</span></label>
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"><?= lang('Each spent') . ' ' . lang('equal') ?>
                                        <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="staff_spent_amount" class="form-control"
                                               style="width:260px" value="<?= config_item('staff_spent_amount') ?>"
                                               required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-sm-3 control-label"><?= lang('award') . ' ' . lang('point') ?>
                                        <span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        <input type="text" name="staff_award_ponint" class="form-control"
                                               style="width:260px" value="<?= config_item('staff_award_ponint') ?>"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-3 control-label"></div>
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- End Form -->
</div>