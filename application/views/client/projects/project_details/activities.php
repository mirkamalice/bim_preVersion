<?php
$project_settings = json_decode($project_details->project_settings);
if (!empty($project_settings[6]) && $project_settings[6] == 'show_project_history') {
    $activities_info = $this->db->where(array('module' => 'project', 'module_field_id' => $project_details->project_id))->order_by('activity_date', 'desc')->get('tbl_activities')->result();

}
?>

<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('activities') ?>

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
                                        <a href="#" class="text-info"><?= $profile_info->fullname ?></a>
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