<?php
$edited = can_action('54', 'edited');
$sub_tasks = config_item('allow_sub_tasks');
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="#sub_general" data-toggle="tab"><?= lang('all') . ' ' . lang('sub_tasks') ?></a>
        </li>
        <?php if (!empty($edited)) { ?>
            <li>
                <a href="<?= base_url('admin/tasks/create/sub_tasks/' . $task_details->task_id) ?>"><?= lang('new') . ' ' . lang('sub_tasks') ?></a>
            </li>
        <?php } ?>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="sub_general">
            <div class="table-responsive">
                <table id="table-tasks" class="table table-striped     DataTables">
                    <thead>
                    <tr>
                        <th data-check-all>
                        
                        </th>
                        <th><?= lang('task_name') ?></th>
                        <th><?= lang('due_date') ?></th>
                        <th class="col-sm-1"><?= lang('progress') ?></th>
                        <th class="col-sm-1"><?= lang('status') ?></th>
                        <th class="col-sm-2"><?= lang('changes/view') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $all_sub_tasks = get_result('tbl_task', array('sub_task_id' => $task_details->task_id));
                    if (!empty($all_sub_tasks)) : foreach ($all_sub_tasks as $key => $v_task) :
                        ?>
                        <tr>
                            <td>
                                <div class="is_complete checkbox c-checkbox">
                                    <label>
                                        <input type="checkbox" data-id="<?= $v_task->task_id ?>"
                                               style="position: absolute;" <?php
                                        if ($v_task->task_progress >= 100) {
                                            echo 'checked';
                                        }
                                        ?>>
                                        <span class="fa fa-check"></span>
                                    </label>
                                </div>
                            </td>
                            <td><a class="text-info" style="<?php
                                if ($v_task->task_progress >= 100) {
                                    echo 'text-decoration: line-through;';
                                }
                                ?>"
                                   href="<?= base_url() ?>admin/tasks/details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
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
                                    <div class="easypiechart text-success" style="margin: 0px;"
                                         data-percent="<?= $v_task->task_progress ?>"
                                         data-line-width="5" data-track-Color="#f0f0f0"
                                         data-bar-color="#<?php
                                         if ($v_task->task_progress == 100) {
                                             echo '8ec165';
                                         } else {
                                             echo 'fb6b5b';
                                         }
                                         ?>" data-rotate="270" data-scale-Color="false"
                                         data-size="50" data-animate="2000">
                                                                    <span
                                                                            class="small text-muted"><?= $v_task->task_progress ?>
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
                                <span class="label label-<?= $label ?>"><?= lang($v_task->task_status) ?>
                                                            </span>
                            </td>
                            <td>
                                <?php echo btn_view('admin/tasks/details/' . $v_task->task_id) ?>
                                <?php if (!empty($t_edited)) { ?>
                                    <?php echo btn_edit('admin/tasks/create/' . $v_task->task_id) ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="contact">
            <form role="form" enctype="multipart/form-data" id="form"
                  action="<?php echo base_url(); ?>admin/tasks/update_tasks_timer/<?php
                  if (!empty($tasks_timer_info)) {
                      echo $tasks_timer_info->tasks_timer_id;
                  }
                  ?>" method="post" class="form-horizontal">
                <?php
                if (!empty($tasks_timer_info)) {
                    $start_date = date('Y-m-d', $tasks_timer_info->start_time);
                    $start_time = date('H:i', $tasks_timer_info->start_time);
                    $end_date = date('Y-m-d', $tasks_timer_info->end_time);
                    $end_time = date('H:i', $tasks_timer_info->end_time);
                } else {
                    $start_date = '';
                    $start_time = '';
                    $end_date = '';
                    $end_time = '';
                }
                ?>
                <?php if ($this->session->userdata('user_type') == '1' && empty($tasks_timer_info->tasks_timer_id)) { ?>
                    <div class="form-group margin">
                        <div class="col-sm-8 center-block">
                            <label class="control-label"><?= lang('select') . ' ' . lang('tasks') ?>
                                <span class="required">*</span></label>
                            <select class="form-control select_box" name="task_id" required="" style="width: 100%">
                                <?php
                                $all_tasks_info = $this->db->get('tbl_task')->result();
                                if (!empty($all_tasks_info)) : foreach ($all_tasks_info as $v_task_info) :
                                    ?>
                                    <option
                                            value="<?= $v_task_info->task_id ?>" <?= $v_task_info->task_id == $task_details->task_id ? 'selected' : null ?>>
                                        <?= $v_task_info->task_name ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="task_id" value="<?= $task_details->task_id ?>">
                <?php } ?>
                <div class="form-group margin">
                    <div class="col-sm-4">
                        <label class="control-label"><?= lang('start_date') ?> </label>
                        <div class="input-group">
                            <input type="text" name="start_date" class="form-control datepicker"
                                   value="<?= $start_date ?>"
                                   data-date-format="<?= config_item('date_picker_format'); ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label"><?= lang('start_time') ?></label>
                        <div class="input-group">
                            <input type="text" name="start_time" class="form-control timepicker2"
                                   value="<?= $start_time ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-clock-o"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group margin">
                    <div class="col-sm-4">
                        <label class="control-label"><?= lang('end_date') ?></label>
                        <div class="input-group">
                            <input type="text" name="end_date" class="form-control datepicker" value="<?= $end_date ?>"
                                   data-date-format="<?= config_item('date_picker_format'); ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label"><?= lang('end_time') ?></label>
                        <div class="input-group">
                            <input type="text" name="end_time" class="form-control timepicker2"
                                   value="<?= $end_time ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-clock-o"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group margin">
                    <div class="col-sm-8 center-block">
                        <label class="control-label"><?= lang('edit_reason') ?><span class="required">*</span></label>
                        <div>
                                                <textarea class="form-control" name="reason" required="" rows="6"><?php
                                                    if (!empty($tasks_timer_info)) {
                                                        echo $tasks_timer_info->reason;
                                                    }
                                                    ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 20px;">
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                    </div>
                </div>
            </form>
        </div>
    
    
    </div>
</div>
