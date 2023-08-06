<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= lang('new') . ' ' . lang('program') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form method="post" action="<?php echo base_url() ?>admin/settings/save_program" class="form-horizontal">
            <div class="panel-body">
                <input type="hidden" name="settings" value="<?= $load_setting ?>">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('program_name') ?> <span
                                class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control" value="<?php
                        if (!empty($awardrule_info->program_name)) {
                            echo $awardrule_info->program_name;
                        }
                        ?>" name="program_name" data-required="true">
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('award_rule') ?> <span
                                class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <?php
                        
                        $selected = ($awardrule_info->award_rule_id) ? $awardrule_info->award_rule_id : '';
                        echo form_dropdown('award_rule_id', $all_awardrule, $selected, array('class' => 'form-control select_box', 'style' => 'width:100%'));
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('client') ?> <span
                                class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <?php
                        
                        $selected = ($awardrule_info->client_id) ? $awardrule_info->client_id : '';
                        echo form_dropdown('client_id', $all_client, $selected, array('class' => 'form-control select_box', 'style' => 'width:100%'));
                        ?>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('start_date') ?></label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input type="text" name="start_date" class="form-control start_date" value="<?php
                            if (!empty($awardrule_info->start_date)) {
                                echo $awardrule_info->start_date;
                            } ?>"
                                   data-date-format="<?= config_item('date_picker_format'); ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('end_date') ?><span class="required">*</span></label>
                    <div class="col-lg-5">
                        <div class="input-group">
                            <input type="text" name="end_date" required="" value="<?php
                            if (!empty($awardrule_info->end_date)) {
                                echo $awardrule_info->end_date;
                            }
                            ?>" class="form-control end_date"
                                   data-date-format="<?= config_item('date_picker_format'); ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('description') ?></label>
                    <div class="col-lg-6">
                        <textarea class="form-control" name="description"><?php
                            if (!empty($awardrule_info->description)) {
                                echo $awardrule_info->description;
                            }
                            ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('status') ?></label>
                    <div class="col-lg-6">
                        <select class="form-control" name=status>
                            <option value="1">Active</option>
                            <option value="0">deactive</option>
                        </select>
                    </div>
                </div>
            
            
            </div>
            <div class="form-group">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-6">
                    <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                
                </div>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $('body').find('select.selectpicker').not('.ajax-search').selectpicker({
            showSubtext: true,
        });
    });
</script>