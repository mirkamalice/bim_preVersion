<?php
$project_settings = json_decode($project_details->project_settings);
if (in_array("show_project_bugs", $project_settings)) {
    $all_bugs_info = get_result('tbl_bug', array('project_id' => $project_details->project_id));
    
}
?>

<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('bugs') ?>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table id="table-milestones" class="table table-striped     DataTables">
                <thead>
                    <tr>
                        <th><?= lang('bug_title') ?></th>
                        <th><?= lang('status') ?></th>
                        <th><?= lang('priority') ?></th>
                        <th><?= lang('reporter') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($all_bugs_info as $key => $v_bugs) :
                        $reporter = $this->db->where('user_id', $v_bugs->reporter)->get('tbl_users')->row();
                        if ($reporter->role_id == '1') {
                            $badge = 'danger';
                        } elseif ($reporter->role_id == '2') {
                            $badge = 'info';
                        } else {
                            $badge = 'primary';
                        }
                    ?>
                        <tr>
                            <td><a class="text-info" style="<?php
                                                            if ($v_bugs->bug_status == 'resolve') {
                                                                echo 'text-decoration: line-through;';
                                                            }
                                                            ?>" href="<?= base_url() ?>client/bugs/view_bug_details/<?= $v_bugs->bug_id ?>"><?php echo $v_bugs->bug_title; ?></a>
                            </td>
                            </td>
                            <td><?php
                                if ($v_bugs->bug_status == 'unconfirmed') {
                                    $label = 'warning';
                                } elseif ($v_bugs->bug_status == 'confirmed') {
                                    $label = 'info';
                                } elseif ($v_bugs->bug_status == 'in_progress') {
                                    $label = 'primary';
                                } else {
                                    $label = 'success';
                                }
                                ?>
                                <span class="label label-<?= $label ?>"><?= lang("$v_bugs->bug_status") ?></span>
                            </td>
                            <td><?= ucfirst($v_bugs->priority) ?></td>
                            <td>

                                <span class="badge btn-<?= $badge ?> "><?= $reporter->username ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>