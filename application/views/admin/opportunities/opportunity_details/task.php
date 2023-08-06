<?php 
$edited = can_action('56', 'edited');
$sub_active = 1;

?>
<div class="box" style="border: none; padding-top: 15px;" data-collapsed="0">
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $sub_active == 1 ? 'active' : ''; ?>"><a href="#manage_milestone" data-toggle="tab"><?= lang('task') ?></a>
            </li>
            <?php if (!empty($edited)) { ?>
                <li class=""><a href="<?= base_url() ?>admin/tasks/create/opportunities/<?= $opportunity_details->opportunities_id ?>"><?= lang('new_task') ?></a>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->
            <div class="tab-pane <?= $sub_active == 1 ? 'active' : ''; ?>" id="manage_milestone">
                <div class="table-responsive">
                    <table id="table-milestones" class="table table-striped">
                        <thead>
                            <tr>
                                <th data-check-all>

                                </th>
                                <th class="col-sm-4"><?= lang('task_name') ?></th>
                                <th class="col-sm-2"><?= lang('due_date') ?></th>
                                <th class="col-sm-1"><?= lang('status') ?></th>
                                <th class="col-sm-1"><?= lang('progress') ?></th>
                                <th class="col-sm-3"><?= lang('changes/view') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $all_task_info = get_result('tbl_task', array('opportunities_id' => $opportunity_details->opportunities_id)); 
                            if (!empty($all_task_info)) : foreach ($all_task_info as $key => $v_task) :
                            ?>
                                    <tr id="table-tasks-<?= $v_task->task_id ?>">
                                        <td class="col-sm-1">
                                            <div class="is_complete checkbox c-checkbox">
                                                <label>
                                                    <input type="checkbox" data-id="<?= $v_task->task_id ?>" style="position: absolute;" <?php
                                                                                                                                            if ($v_task->task_progress >= 100) {
                                                                                                                                                echo 'checked';
                                                                                                                                            }
                                                                                                                                            ?>>
                                                    <span class="fa fa-check"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <a style="<?php
                                                        if ($v_task->task_progress >= 100) {
                                                            echo 'text-decoration: line-through;';
                                                        }
                                                        ?>" href="<?= base_url() ?>admin/tasks/details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
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
                                        <td><?php
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
                                            <span class="label label-<?= $label ?>"><?= lang($v_task->task_status) ?>
                                            </span>
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
                                            <?php echo ajax_anchor(base_url("admin/tasks/delete_task/" . $v_task->task_id), "<i class='btn btn-danger btn-xs fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table-tasks-" . $v_task->task_id)); ?>
                                            <?php echo btn_edit('admin/tasks/create/' . $v_task->task_id) ?>
                                            <?php

                                            if ($v_task->timer_status == 'on') { ?>
                                                <a class="btn btn-xs btn-danger" href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $v_task->task_id ?>"><?= lang('stop_timer') ?>
                                                </a>

                                            <?php } else { ?>
                                                <a class="btn btn-xs btn-success" href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $v_task->task_id ?>"><?= lang('start_timer') ?>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Tasks Management-->

        </div>
    </div>
</div>