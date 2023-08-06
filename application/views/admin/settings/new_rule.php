<div class="panel panel-custom">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= lang('new') . ' ' . lang('award_rule') ?></h4>
    </div>
    <div class="modal-body wrap-modal wrap">
        <form method="post" action="<?php echo base_url() ?>admin/settings/save_award_rule" class="form-horizontal">
            <div class="panel-body">
                <input type="hidden" name="settings" value="<?= $load_setting ?>">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('name') ?> <span class="text-danger">*</span></label>
                    <div class="col-lg-6">
                        <input type="text" required="" class="form-control" value="<?php
                        if (!empty($awardrule_info->rule_name)) {
                            echo $awardrule_info->rule_name;
                        }
                        ?>" name="rule_name" data-required="true">
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
                
                <div id="">
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('card') ?> </label>
                        <div class="col-lg-6">
                            <input type="text" required="" class="form-control" value="<?php
                            if (!empty($awardrule_info->card)) {
                                echo $awardrule_info->card;
                            }
                            ?>" name="card">
                        
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('Point from') ?></label>
                        <div class="col-lg-6">
                            <input type="text" required="" class="form-control" value="<?php
                            if (!empty($awardrule_info->award_point_from)) {
                                echo $awardrule_info->award_point_from;
                            }
                            ?>" name="award_point_from">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('Point to') ?></label>
                        <div class="col-lg-6">
                            <input type="text" required="" class="form-control" value="<?php
                            if (!empty($awardrule_info->award_point_to)) {
                                echo $awardrule_info->award_point_to;
                            }
                            ?>" name="award_point_to">
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