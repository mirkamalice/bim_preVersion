    <style>
        .tooltip-inner {
            white-space: pre-wrap;
        }
    </style>
    <div class="panel panel-custom">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?= lang('timesheet') ?>
            </h3>
        </div>

        <div class="table-responsive">
            <table id="table-tasks-timelog" class="table table-striped     DataTables">
                <thead>
                    <tr>
                        <th><?= lang('start_time') ?></th>
                        <th><?= lang('stop_time') ?></th>
                        <th><?= lang('project_name') ?></th>
                        <th class="col-time"><?= lang('time_spend') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_timer = get_result('tbl_tasks_timer', array('project_id' => $project_details->project_id));

                    if (!empty($total_timer)) {
                        foreach ($total_timer as $v_timer) {
                    ?>
                            <tr>

                                <td><span class="label label-success"><?= display_datetime($v_timer->start_time, true) ?></span>
                                </td>
                                <td><span class="label label-danger"><?= display_datetime($v_timer->end_time, true); ?></span>
                                </td>

                                <td>
                                    <a href="<?= base_url() ?>client/projects/project_details/<?= $v_timer->project_id ?>" class="text-info small"><?= $project_details->project_name ?>
                                    </a>
                                </td>
                                <td>
                                    <small class="small text-muted"><?= $this->items_model->get_time_spent_result($v_timer->end_time - $v_timer->start_time) ?></small>
                                </td>

                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>