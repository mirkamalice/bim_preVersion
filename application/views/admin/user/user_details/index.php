<?php
        $edited = can_action('24', 'edited');
        $deleted = can_action('24', 'deleted');
        $user_info = $this->db->where('user_id', $profile_info->user_id)->get('tbl_users')->row();

        
        
?>
<div class="panel panel-custom">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <div class="panel-title">
            <strong><?= $profile_info->fullname ?></strong>
            <?php if (!empty($edited)) { ?>
                <div class="pull-right">
                    <span data-placement="top" data-toggle="tooltip" title="<?= lang('update_conatct') ?>">
                        <a data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/user/update_contact/1/<?= $profile_info->account_details_id ?>" class="text-default text-sm ml"><?= lang('update') ?></a>
                    </span>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="panel-body form-horizontal">
        <div class="form-group mb0  col-sm-6">
            <label class="control-label col-sm-5"><strong><?= lang('emp_id') ?>
                    :</strong></label>
            <div class="col-sm-7 ">
                <p class="form-control-static"><?= $profile_info->employment_id ?></p>

            </div>
        </div>
        <div class="form-group mb0  col-sm-6">
            <label class="control-label col-sm-5"><strong><?= lang('fullname') ?>
                    :</strong></label>
            <div class="col-sm-7 ">
                <p class="form-control-static"><?= $profile_info->fullname ?></p>

            </div>
        </div>
        <?php if ($this->session->userdata('user_type') == 1) { ?>
            <div class="form-group mb0  col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('username') ?>
                        :</strong></label>
                <div class="col-sm-7 ">
                    <p class="form-control-static"><?= $user_info->username ?></p>

                </div>
                <?php 
                ?>
            </div>
            <div class="form-group mb0  col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('password') ?>
                        :</strong></label>
                <div class="col-sm-7 ">
                    <p class="form-control-static"><a data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/user/reset_password/<?= $user_info->user_id ?>"><?= lang('reset_password') ?></a>
                    </p>
                </div>
            </div>
        <?php } ?>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('joining_date') ?>: </label>
            <div class="col-sm-7">
                <?php if (!empty($profile_info->joining_date)) { ?>
                    <p class="form-control-static">
                        <?php echo strftime(config_item('date_format'), strtotime($profile_info->joining_date)); ?>
                    </p>
                <?php } ?>
            </div>
        </div>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('gender') ?>:</label>
            <div class="col-sm-7">
                <?php if (!empty($profile_info->gender)) { ?>
                    <p class="form-control-static"><?php echo lang($profile_info->gender); ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="form-group mb0  col-sm-6">

            <label class="col-sm-5 control-label"><?= lang('date_of_birth') ?>: </label>
            <div class="col-sm-7">
                <?php if (!empty($profile_info->date_of_birth)) { ?>
                    <p class="form-control-static">
                        <?php echo strftime(config_item('date_format'), strtotime($profile_info->date_of_birth)); ?>
                    </p>
                <?php } ?>
            </div>
        </div>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('maratial_status') ?>:</label>
            <div class="col-sm-7">
                <?php if (!empty($profile_info->maratial_status)) { ?>
                    <p class="form-control-static"><?php echo lang($profile_info->maratial_status); ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('fathers_name') ?>: </label>
            <div class="col-sm-7">
                <?php if (!empty($profile_info->father_name)) { ?>
                    <p class="form-control-static"><?php echo "$profile_info->father_name"; ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('mother_name') ?>: </label>
            <div class="col-sm-7">
                <?php if (!empty($profile_info->mother_name)) { ?>
                    <p class="form-control-static"><?php echo "$profile_info->mother_name"; ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('email') ?> : </label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo "$user_info->email"; ?></p>
            </div>
        </div>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('phone') ?> : </label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo "$profile_info->phone"; ?></p>
            </div>
        </div>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('mobile') ?> : </label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo "$profile_info->mobile"; ?></p>
            </div>
        </div>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('skype_id') ?> : </label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo "$profile_info->skype"; ?></p>
            </div>
        </div>
        <?php if (!empty($profile_info->passport)) { ?>
            <div class="form-group mb0  col-sm-6">
                <label class="col-sm-5 control-label"><?= lang('passport') ?>
                    : </label>
                <div class="col-sm-7">
                    <p class="form-control-static"><?php echo "$profile_info->passport"; ?></p>
                </div>
            </div>
        <?php } ?>
        <div class="form-group mb0  col-sm-6">
            <label class="col-sm-5 control-label"><?= lang('present_address') ?>
                : </label>
            <div class="col-sm-7">
                <p class="form-control-static"><?php echo "$profile_info->present_address"; ?></p>
            </div>
        </div>
        <?php $show_custom_fields = custom_form_label(13, $profile_info->user_id);

        if (!empty($show_custom_fields)) {
            foreach ($show_custom_fields as $c_label => $v_fields) {
                
                if (!empty($v_fields)) {
        ?>
                    <div class="form-group mb0  col-sm-6">
                        <label class="col-sm-5 control-label"><?= $c_label ?> : </label>
                        <div class="col-sm-7">
                            <p class="form-control-static"><?= $v_fields ?></p>
                        </div>
                    </div>
        <?php }
            }
        }
        ?>

    </div>
</div>