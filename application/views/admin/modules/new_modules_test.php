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
            <h4 class="modal-title" id="myModalLabel"><?= lang('modules_test') ?></h4>
        </div>
        <div class="modal-body wrap-modal wrap">
            <form role="form" id="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" action="<?php echo base_url(); ?>admin/modules_test/save_modules_test/<?= (!empty($modules_test->test_id) ? $modules_test->test_id : ''); ?>" method="post" class="form-horizontal form-groups-bordered">
                
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('module_name') ?> <span class="required">*</span></label>

                    <div class="col-sm-8">
                        <input type="text" required name="module_name" value="<?= (!empty($modules_test->module_name) ? $modules_test->module_name : ''); ?>" class="form-control" requried />
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('price') ?> <span class="required">*</span></label>

                    <div class="col-sm-8">
                        <input type="number" required name="price" value="<?= (!empty($modules_test->price) ? $modules_test->price : ''); ?>" class="form-control" requried />
                    </div>
                </div>

                <div class="form-group">
                        <label class="col-lg-3 control-label"><strong><?= lang('profile_photo') ?></strong></label>
                        <div class="col-lg-8">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 210px;">
                                    <?php if ($modules_test->preview_image != '') : ?>
                                        <img src="<?php echo base_url() . $modules_test->preview_image; ?>">
                                    <?php else: ?>
                                        <img src="<?= base_url('uploads/default_avatar.jpg') ?>"
                                             alt="Please Connect Your Internet">
                                    <?php endif; ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            <input type="file" name="preview_image" value="upload"
                                                   data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                            <span class="fileinput-exists"><?= lang('change') ?></span>    
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists"
                                           data-dismiss="fileinput"><?= lang('remove') ?></a>
                                </div>
                                <div id="valid_msg" style="color: #e11221"></div>
                            </div>
                        </div>
                    </div>

                

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('preview_url') ?> <span class="required">*</span></label>

                    <div class="col-sm-8">
                        <input type="text" required name="preview_url" placeholder="example.com" value="<?= (!empty($modules_test->preview_url) ? $modules_test->preview_url : ''); ?>" class="form-control" requried />
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('description') ?></label>

                    <div class="col-sm-8">
                        <textarea name="description" class="form-control textarea"><?= (!empty($modules_test->description) ? $modules_test->description : ''); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('order') ?> <span class="required">*</span></label>

                    <div class="col-sm-8">
                        <input type="number" required name="test_order" value="<?= (!empty($modules_test->test_order) ? $modules_test->test_order : ''); ?>" class="form-control" requried />
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