<?php 

?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('notes') ?></h3>
    </div>
    <div class="panel-body">

        <form action="<?= base_url() ?>admin/leads/save_leads_notes/<?php
                                                                    if (!empty($leads_details)) {
                                                                        echo $leads_details->leads_id;
                                                                    }
                                                                    ?>" enctype="multipart/form-data" method="post" id="form" class="form-horizontal">
            <div class="form-group">
                <div class="col-lg-12">
                    <textarea class="form-control textarea" name="notes"></textarea>
                </div>
            </div>
            <div class="form-group" id="date_contacted" style="display: none">
                <div class="col-lg-12">
                    <label class="control-label"><?= lang('date_contacted') ?></label>
                    <div class="input-group">
                        <input required type="text" name="last_contact" class="form-control datetimepicker" value="<?php
                                                                                                                    if (!empty($leads_info->last_contact)) {
                                                                                                                        echo $leads_info->last_contact;
                                                                                                                    } else {
                                                                                                                        echo date('Y-m-d H:i');
                                                                                                                    }
                                                                                                                    ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                        <div class="input-group-addon">
                            <a href="#"><i class="fa fa-calendar"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <div class="checkbox c-checkbox">
                        <label class="needsclick">
                            <input type="hidden" value="off" name="i_got_touch_with_leads" />
                            <input type="checkbox" value="touch_with_leads" data-result="show" name="contacted_indicator" class="select_one hideshow">
                            <span class="fa fa-check"></span>
                        </label> <?= lang('i_got_touch_with_leads') ?>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="checkbox c-checkbox">
                        <label class="needsclick">
                            <input type="hidden" value="off" name="i_have_not_contacted" />
                            <input type="checkbox" <?php
                                                    echo "checked=\"checked\"";
                                                    ?> class="select_one hideshow" data-result="hide" value="not_contacted" name="contacted_indicator">
                            <span class="fa fa-check"></span>
                        </label> <?= lang('i_have_not_contacted') ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2">
                    <button type="submit" id="sbtn" class="btn btn-primary"><?= lang('updates') ?></button>
                </div>
            </div>
        </form>
        <?php
        $leads_notes = get_result('tbl_leads_notes', array('leads_id' => $leads_details->leads_id));
        if (!empty($leads_notes)) {
        ?>
            <hr class="mt-md mb-sm" />
            <?php foreach ($leads_notes as $v_notes) { ?>
                <div class="mb-mails col-sm-12" id="leads_notes_<?php $v_notes->notes_id ?>">
                    <img alt="Mail Avatar" src="<?= base_url(staffImage($v_notes->user_id)) ?>" class="mb-mail-avatar pull-left">
                    <div class="mb-mail-date pull-right">
                        <?php if (!empty($v_notes->last_contact)) { ?>
                            <span data-toggle="tooltip" title="<?= $v_notes->last_contact ?>"><i class="fa fa-phone-square text-success"></i></span>
                        <?php } ?>
                        <?= time_ago($v_notes->created_time) ?> <strong data-toggle="tooltip" data-placement="top" style="cursor:pointer" class="" title="" data-fade-out-on-success="#leads_notes_<?php $v_notes->notes_id ?>" data-act="ajax-request" data-action-url="<?= base_url('admin/leads/delete_notes/' . $v_notes->notes_id . '/' . $leads_details->leads_id) ?>" data-original-title="Delete"><i class="text-danger fa fa-trash-o"></i></strong>
                    </div>
                    <div class="mb-mail-meta">
                        <div class="pull-left">
                            <div class="mb-mail-from"><a href="<?= base_url('admin/user/user_details/' . $v_notes->user_id) ?>">
                                    <?= fullname($v_notes->user_id) ?></a>
                            </div>
                        </div>
                        <div class="mb-mail-preview"><?= $v_notes->notes ?></div>
                        <div class="mb-mail-album pull-left"></div>


                    </div>
                </div>
        <?php }
        } ?>
    </div>
</div>