<?php echo message_box('success') ?>
<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">
        <form action="<?php echo base_url() ?>admin/settings/save_transfer" enctype="multipart/form-data"
              class="form-horizontal" method="post">
            <div class="panel panel-custom">
                <header class="panel-heading  "><?= lang('transfer') . ' ' . lang('settings') ?></header>
                <div class="panel-body">
                    <input type="hidden" name="settings" value="<?= $load_setting ?>">
                    
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('transfer') . ' ' . lang('prefix') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="transfer_prefix" class="form-control" style="width:260px"
                                   value="<?= config_item('transfer_prefix') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('transfer') . ' ' . lang('start_no') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-lg-7">
                            <input type="text" name="transfer_start_no" class="form-control" style="width:260px"
                                   value="<?= config_item('transfer_start_no') ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('transfer') . ' ' . lang('number_format') ?></label>
                        <div class="col-lg-5">
                            <input type="text" name="transfer_number_format" class="form-control" style="width:260px"
                                   value="<?php
                                   if (empty(config_item('transfer_number_format'))) {
                                       echo '[' . config_item('transfer_prefix') . ']' . '[yyyy][mm][dd][number]';
                                   } else {
                                       echo config_item('transfer_number_format');
                                   } ?>">
                            <small>ex [<?= config_item('transfer_prefix') ?>] = <?= lang('transfer_prefix') ?>,[yyyy] =
                                'Current Year (<?= date('Y') ?>)'[yy] ='Current Year (<?= date('y') ?>)',[mm] =
                                'Current Month(<?= date('M') ?>)',[m] =
                                'Current Month(<?= date('m') ?>)',[dd] = 'Current Date (<?= date('d') ?>)',[number] =
                                'Invoice Number (<?= sprintf('%04d', config_item('transfer_start_no')) ?>)'
                            </small>
                        </div>
                    </div>
                    <div class="form-group terms">
                        <label class="col-lg-3 control-label"><?= lang('transfer_notes') ?></label>
                        <div class="col-lg-9">
                            <textarea class="form-control textarea"
                                      name="transfer_notes"><?= config_item('transfer_notes') ?></textarea>
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