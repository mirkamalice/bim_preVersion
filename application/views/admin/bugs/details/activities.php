<?php 
$activities_info = $this->db->where(array('module' => 'bugs', 'module_field_id' => $bug_details->bug_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();

?>
<div class="tab-pane " id="activities">
    <div class="panel panel-custom">
        <div class="panel-heading">
            <h3 class="panel-title"><?= lang('activities') ?>
                <?php
                $role = $this->session->userdata('user_type');
                if ($role == 1) {
                ?>
                    <span class="btn-xs pull-right">
                        <a href="<?= base_url() ?>admin/tasks/claer_activities/bugs/<?= $bug_details->bug_id ?>"><?= lang('clear') . ' ' . lang('activities') ?></a>
                    </span>
                <?php } ?>
            </h3>
        </div>
        <div class="panel-body " id="chat-box">
            <?php
            if (!empty($activities_info)) {
                foreach ($activities_info as $v_activities) {
                    $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                    $user_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_users')->row();
            ?>
                    <div class="timeline-2">
                        <div class="time-item">
                            <div class="item-info">
                                <small data-toggle="tooltip" data-placement="top" title="<?= display_datetime($v_activities->activity_date) ?>" class="text-muted"><?= time_ago($v_activities->activity_date); ?></small>

                                <p><strong>
                                        <?php if (!empty($profile_info)) {
                                        ?>
                                            <a href="<?= base_url() ?>admin/user/user_details/<?= $profile_info->user_id ?>" class="text-info"><?= $profile_info->fullname ?></a>
                                        <?php } ?>
                                    </strong> <?= sprintf(lang($v_activities->activity)) ?>
                                    <strong><?= $v_activities->value1 ?></strong>
                                    <?php if (!empty($v_activities->value2)) { ?>
                                <p class="m0 p0"><strong><?= $v_activities->value2 ?></strong></p>
                            <?php } ?>
                            </p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>