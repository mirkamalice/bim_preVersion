<?php
$edited = can_action('54', 'edited');
$time_active = 1;
$task_timer_id = $this->uri->segment(6);
if ($task_timer_id) {
    $time_active = 2;
    $tasks_timer_info = get_row('tbl_tasks_timer', array('tasks_timer_id' => $task_timer_id));
}
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $time_active == 1 ? 'active' : ''; ?>"><a href="#general"
                                                                 data-toggle="tab"><?= lang('timesheet') ?></a>
        </li>
        <?php if (!empty($edited)) { ?>
            <li class="<?= $time_active == 2 ? 'active' : ''; ?>"><a href="#contact"
                                                                     data-toggle="tab"><?= lang('manual_entry') ?></a>
            </li>
        <?php } ?>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $time_active == 1 ? 'active' : ''; ?>" id="general">
            <div class="table-responsive">
                <table id="table-tasks-timelog" class="table table-striped     DataTables">
                    <thead>
                    <tr>
                        <th><?= lang('user') ?></th>
                        <th><?= lang('start_time') ?></th>
                        <th><?= lang('stop_time') ?></th>
                        <th><?= lang('task_name') ?></th>
                        <th class="col-time"><?= lang('time_spend') ?></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $total_timer = get_result('tbl_tasks_timer', array('task_id' => $task_details->task_id, 'start_time !=' => 0, 'end_time !=' => 0));
                    if (!empty($total_timer)) {
                        foreach ($total_timer as $v_tasks) {
                            $task_info = get_row('tbl_task', array('task_id' => $v_tasks->task_id));
                            if (!empty($task_info)) {
                                ?>
                                <tr id="table_tasks_timer-<?= $v_tasks->tasks_timer_id ?>">
                                    <td class="small">
                                        
                                        <a class="pull-left recect_task  ">
                                            <?php
                                            $profile_info = $this->db->where(array('user_id' => $v_tasks->user_id))->get('tbl_account_details')->row();
                                            $user_info = $this->db->where(array('user_id' => $v_tasks->user_id))->get('tbl_users')->row();
                                            if (!empty($user_info)) {
                                                ?>
                                                <img style="width: 30px;margin-left: 18px;
                                                                             height: 29px;
                                                                             border: 1px solid #aaa;"
                                                     src="<?= base_url() . $profile_info->avatar ?>" class="img-circle">
                                                
                                                <?= ucfirst($user_info->username) ?>
                                            <?php } else {
                                                echo '-';
                                            } ?>
                                        </a>
                                    
                                    
                                    </td>
                                    
                                    <td>
                                        <span class="label label-success"><?= strftime(config_item('date_format'), $v_tasks->start_time) . ' ' . display_time($v_tasks->start_time, true) ?></span>
                                    </td>
                                    <td>
                                        <span class="label label-danger"><?= strftime(config_item('date_format'), $v_tasks->end_time) . ' ' . display_time($v_tasks->end_time, true) ?></span>
                                    </td>
                                    
                                    <td>
                                        <?php if (!empty($edited)) { ?>
                                            
                                            <a href="<?= base_url() ?>admin/tasks/details/<?= $v_tasks->task_id ?>"
                                               class="text-info small"><?= $task_info->task_name ?>
                                                <?php
                                                if (!empty($v_tasks->reason)) {
                                                    $edit_user_info = $this->db->where(array('user_id' => $v_tasks->edited_by))->get('tbl_users')->row();
    
                                                    echo '<i class="text-danger" data-html="true" data-toggle="tooltip" data-placement="top" title="Reason : ' . $v_tasks->reason . '<br>' . ' Edited By : ' . $edit_user_info->username . '">Edited</i>';
                                                }
                                                ?>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <small class="small text-muted"><?= $this->tasks_model->get_time_spent_result($v_tasks->end_time - $v_tasks->start_time) ?></small>
                                    </td>
                                    <td>
                                        <?= btn_edit('admin/tasks/details/' . $v_tasks->task_id . '/timesheets/' . $v_tasks->tasks_timer_id) ?>
                                        <?php if ($v_tasks->user_id == $this->session->userdata('user_id')) { ?>
                                            <?php echo ajax_anchor(base_url("admin/tasks/update_tasks_timer/" . $v_tasks->tasks_timer_id . '/delete_task_timmer'), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_tasks_timer-" . $v_tasks->tasks_timer_id)); ?>
                                        <?php } ?>
                                    </td>

                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane <?= $time_active == 2 ? 'active' : ''; ?>" id="contact">
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
                                    <option value="<?= $v_task_info->task_id ?>" <?= $v_task_info->task_id == $task_details->task_id ? 'selected' : null ?>>
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
                            <input type="text" name="start_date" class="form-control start_date"
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
                            <input type="text" name="end_date" class="form-control end_date" value="<?= $end_date ?>"
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