<?php echo message_box('success') ?>
<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">
        
        <form method="post" action="<?php echo base_url() ?>admin/settings/save_card_config" class="form-horizontal">
            <div class="panel panel-custom">
                <header class="panel-heading "><?= lang('card_config') . ' ' . lang('setting') ?></header>
                <div class="panel-body">
                    <input type="hidden" name="settings" value="<?= $load_setting ?>">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('name') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" required="" class="form-control" value="" name="name"
                                   data-required="true">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('login_background') ?></label>
                        <div class="col-lg-7">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 210px;height: 150px">
                                    <?php if (!empty($login_background[0]) && $login_background[0] == 'video') { ?>
                                        <video style="width: 100%;min-height: 100%" autoplay="autoplay" muted="muted"
                                               preload="auto" loop>
                                            <source src="<?php echo base_url() . config_item('login_background'); ?>"
                                                    type="video/webm">
                                        </video>
                                    <?php } ?>
                                    <?php if (!empty($login_background[0]) && $login_background[0] == 'image') {
                                        ?>
                                        <img src="<?php echo base_url() . config_item('login_background'); ?>">
                                    <?php } ?>
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="width: 210px;"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">
                                            <input type="file" name="login_background" value="upload"
                                                   data-buttonText="<?= lang('choose_file') ?>" id="myImg"/>
                                            <span class="fileinput-exists"><?= lang('change') ?></span>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists"
                                           data-dismiss="fileinput"><?= lang('remove') ?></a>
                                
                                </div>
                                <div id="valid_msg" style="color: #e11221">You can add video/image</div>
                            
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('client') ?> <span
                                    class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <?php
                            $selected = ($items_info->client_id) ? $items_info->client_id : '';
                            echo form_dropdown('client_id', $all_client, $selected, array('class' => 'form-control select_box', 'style' => 'width:100%'));
                            ?>
                        </div>
                    </div>
                    
                    <div id="smtp_config">
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('card') ?> </label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control" value="" name="card">
                            
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('Point from') ?></label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control" value="" name="award_point_from">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('Point to') ?></label>
                            <div class="col-lg-6">
                                <input type="text" required="" class="form-control" value="" name="award_point_to">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('description') ?></label>
                            <div class="col-lg-6">
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                        </div>
                    
                    
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>