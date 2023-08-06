<?php
$project_settings = json_decode($project_details->project_settings);
if (!empty($project_settings[2]) && $project_settings[2] == 'show_project_tasks') {
    $all_task_info = get_result('tbl_task',array('project_id'=>$id));
}
?>

<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('task') ?>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table id="table-milestones" class="table table-striped     DataTables">
                <thead>
                    <tr>
                        <th><?= lang('task_name') ?></th>
                        <th><?= lang('due_date') ?></th>
                        <th class="col-sm-1"><?= lang('progress') ?></th>
                        <th class="col-sm-1"><?= lang('status') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($all_task_info as $key => $v_task) :
                    ?>
                        <tr>

                            <td><a class="text-info" style="<?php
                                                            if ($v_task->task_progress >= 100) {
                                                                echo 'text-decoration: line-through;';
                                                            }
                                                            ?>" href="<?= base_url() ?>client/tasks/details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                            </td>

                            <td><?php
                                $due_date = $v_task->due_date;
                                $due_time = strtotime($due_date);
                                $current_time = strtotime(date('Y-m-d'));
                                ?>
                                <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                    <span class="label label-danger"><?= lang('overdue') ?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <div class="inline ">
                                    <div class="easypiechart text-success" style="margin: 0px;" data-percent="<?= $v_task->task_progress ?>" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="#<?php
                                                                                                                                                                                                                if ($v_task->task_progress == 100) {
                                                                                                                                                                                                                    echo '8ec165';
                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                    echo 'fb6b5b';
                                                                                                                                                                                                                }
                                                                                                                                                                                                                ?>" data-rotate="270" data-scale-Color="false" data-size="50" data-animate="2000">
                                        <span class="small text-muted"><?= $v_task->task_progress ?>
                                            %</span>
                                    </div>
                                </div>

                            </td>
                            <td>
                                <?php
                                if ($v_task->task_status == 'completed') {
                                    $label = 'success';
                                } elseif ($v_task->task_status == 'not_started') {
                                    $label = 'info';
                                } elseif ($v_task->task_status == 'deferred') {
                                    $label = 'danger';
                                } else {
                                    $label = 'warning';
                                }
                                ?>
                                <span class="label label-<?= $label ?>"><?= lang($v_task->task_status) ?> </span>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>