<?php
$activities_info = $this->db->where(array('user' => $profile_info->user_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();

$edited = can_action('24', 'edited');
$deleted = can_action('24', 'deleted');
$user_info = $this->db->where('user_id', $profile_info->user_id)->get('tbl_users')->row();
$designation = $this->db->where('designations_id', $profile_info->designations_id)->get('tbl_designations')->row();
if (!empty($designation)) {
    $department = $this->db->where('departments_id', $designation->departments_id)->get('tbl_departments')->row();
}

$all_project_info = $this->user_model->my_permission('tbl_project', $profile_info->user_id);
$p_started = 0;
$p_in_progress = 0;
$p_completed = 0;
$project_time = 0;
$project_time = $this->user_model->my_spent_time($profile_info->user_id, true);
if (!empty($all_project_info)) {
    foreach ($all_project_info as $v_user_project) {
        if ($v_user_project->project_status == 'started') {
            $p_started += count((array)$v_user_project->project_status);
        }
        if ($v_user_project->project_status == 'in_progress') {
            $p_in_progress += count((array)$v_user_project->project_status);
        }
        if ($v_user_project->project_status == 'completed') {
            $p_completed += count((array)$v_user_project->project_status);
        }
    }
}

$tasks_info = $this->user_model->my_permission('tbl_task', $profile_info->user_id);

$t_not_started = 0;
$t_in_progress = 0;
$t_completed = 0;
$t_deferred = 0;
$t_waiting_for_someone = 0;
$task_time = 0;
$task_time = $this->user_model->my_spent_time($profile_info->user_id);
if (!empty($tasks_info)) : foreach ($tasks_info as $v_tasks) :
    if (!empty($v_tasks->task_status)) {
        if ($v_tasks->task_status == 'not_started') {
            $t_not_started += count((array)$v_tasks->task_status);
        } else if ($v_tasks->task_status == 'in_progress') {
            $t_in_progress += count((array)$v_tasks->task_status);
        } else if ($v_tasks->task_status == 'completed') {
            $t_completed += count((array)$v_tasks->task_status);
        }
    }

endforeach;
endif;


?>
<div class="unwrap">
    
    <div class="cover-photo bg-cover">
        <div class="p-xl text-white">
            
            <div class="row col-sm-4">
                <div class="row pull-left col-sm-6">
                    <div class=" row-table row-flush">
                        <div class="pull-left text-white ">
                            <div class="">
                                <h4 class="mt-sm mb0"><?php
                                    echo $p_in_progress + $p_started;
                                    ?>
                                </h4>
                                <p class="mb0 text-muted"><?= lang('open') . ' ' . lang('project') ?></p>
                                <small><a href="<?= base_url() ?>admin/projects"
                                          class="mt0 mb0"><?= lang('more_info') ?> <i
                                                class="fa fa-arrow-circle-right"></i></a></small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-lg row-table row-flush">
                        
                        <div class="pull-left">
                            <div class="">
                                <h4 class="mt-sm mb0"><?php
                                    echo $p_completed;
                                    ?>
                                </h4>
                                <p class="mb0 text-muted"><?= lang('complete') . ' ' . lang('project') ?></p>
                                <small><a href="<?= base_url() ?>admin/projects/create/completed"
                                          class="mt0 mb0"><?= lang('more_info') ?> <i
                                                class="fa fa-arrow-circle-right"></i></a></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pull-right col-sm-6">
                    <div class=" row-table row-flush">
                        
                        <div class="pull-left text-white ">
                            <div class="">
                                <h4 class="mt-sm mb0"><?php
                                    echo $t_in_progress + $t_not_started;
                                    ?>
                                </h4>
                                <p class="mb0 text-muted"><?= lang('open') . ' ' . lang('tasks') ?></p>
                                <small><a href="<?= base_url() ?>admin/tasks/all_task"
                                          class="mt0 mb0"><?= lang('more_info') ?> <i
                                                class="fa fa-arrow-circle-right"></i></a></small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-lg row-table row-flush">
                        
                        <div class="pull-left">
                            <div class="">
                                <h4 class="mt-sm mb0"><?php
                                    echo $t_in_progress;
                                    ?>
                                </h4>
                                <p class="mb0 text-muted"><?= lang('complete') . ' ' . lang('tasks') ?></p>
                                <small><a href="<?= base_url() ?>admin/tasks/create/completed"
                                          class="mt0 mb0"><?= lang('more_info') ?><i
                                                class="fa fa-arrow-circle-right"></i></a></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="text-center ">
                    <?php if ($profile_info->avatar) : ?>
                        <img src="<?php echo base_url() . $profile_info->avatar; ?>"
                             class="img-thumbnail img-circle thumb128 ">
                    <?php else : ?>
                        <img src="<?php echo base_url() ?>assets/img/user/02.jpg" alt="Employee_Image"
                             class="img-thumbnail img-circle thumb128">
                        ;
                    <?php endif; ?>
                </div>
                
                <h3 class="m0 text-center"><?= $profile_info->fullname ?>
                </h3>
                <p class="text-center"><?= lang('emp_id') ?>: <?php echo $profile_info->employment_id ?></p>
                <p class="text-center">
                    <?php
                    if (!empty($department)) {
                        $dname = $department->deptname;
                    } else {
                        $dname = lang('undefined_department');
                    }
                    if (!empty($designation->designations)) {
                        $des = ' &rArr; ' . $designation->designations;
                    } else {
                        $des = '& ' . lang('designation');;
                    }
                    echo $dname . ' ' . $des;
                    if (!empty($department->department_head_id) && $department->department_head_id == $profile_info->user_id) { ?>
                        <strong class="label label-warning"><?= lang('department_head') ?></strong>
                    <?php }
                    ?>
                
                </p>
            </div>
            <div class="col-sm-5">
                <div class="pull-left col-sm-6">
                    <div class=" row-table row-flush">
                        <div class="pull-left text-white ">
                            <div class="">
                                <h4 class="mt-sm mb0"><?php
                                    if (!empty($total_attendance)) {
                                        echo $total_attendance;
                                    } else {
                                        echo '0';
                                    }
                                    ?> / <?php echo $total_days; ?>
                                </h4>
                                <p class="mb0 text-muted"><?= lang('monthly') . ' ' . lang('attendance') ?></p>
                                <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                          class="mt0 mb0"><?= lang('more_info') ?> <i
                                                class="fa fa-arrow-circle-right"></i></a></small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-lg row-table row-flush">
                        
                        <div class="pull-left">
                            <div class="">
                                <h4 class="mt-sm mb0"><?php
                                    if (!empty($total_leave)) {
                                        echo $total_leave;
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </h4>
                                <p class="mb0 text-muted"><?= lang('monthly') . ' ' . lang('leave') ?></p>
                                <small><a href="<?= base_url() ?>admin/leave_management"
                                          class="mt0 mb0"><?= lang('more_info') ?> <i
                                                class="fa fa-arrow-circle-right"></i></a></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pull-right col-sm-6">
                    <div class=" row-table row-flush">
                        
                        <div class="pull-left text-white ">
                            <div class="">
                                <h4 class="mt-sm mb0"><?php
                                    if (!empty($total_absent)) {
                                        echo $total_absent;
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </h4>
                                <p class="mb0 text-muted"><?= lang('monthly') . ' ' . lang('absent') ?></p>
                                <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                          class="mt0 mb0"><?= lang('more_info') ?> <i
                                                class="fa fa-arrow-circle-right"></i></a></small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-lg row-table row-flush">
                        
                        <div class="pull-left">
                            <div class="">
                                <h4 class="mt-sm mb0"><?php
                                    if (!empty($total_award)) {
                                        echo $total_award;
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </h4>
                                <p class="mb0 text-muted"><?= lang('total') . ' ' . lang('award') ?></p>
                                <small><a href="<?= base_url() ?>admin/award" class="mt0 mb0"><?= lang('more_info') ?>
                                        <i class="fa fa-arrow-circle-right"></i></a></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    <div class="text-center bg-gray-dark p-lg mb-xl">
        <div class="row row-table">
            <style type="text/css">
                .user-timer ul.timer {
                    margin: 0px;
                }

                .user-timer ul.timer > li.dots {
                    padding: 6px 2px;
                    font-size: 14px;
                }

                .user-timer ul.timer li {
                    color: #fff;
                    font-size: 24px;
                    font-weight: bold;
                }

                .user-timer ul.timer li span {
                    display: none;
                }
            </style>
            <div class="col-xs-3 br user-timer">
                <h3 class="m0"><?= $this->user_model->get_time_spent_result($project_time) ?></h3>
                <p class="m0">
                    <span class="hidden-xs"><?= lang('project') . ' ' . lang('hours') ?></span>
                </p>
            </div>
            <div class="col-xs-3 br user-timer">
                <h3 class="m0"><?= $this->user_model->get_time_spent_result($task_time) ?></h3>
                <span class="hidden-xs"><?= lang('tasks') . ' ' . lang('hours') ?></span>
            </div>
            <div class="col-xs-3 br user-timer">
                <h3 class="m0"><?php
                    $m_min = 0;
                    $m_hour = 0;
                    
                    if (!empty($this_month_working_hour)) {
                        foreach ($this_month_working_hour as $v_month_hour) {
                            $m_min += $v_month_hour['minute'];
                            $m_hour += $v_month_hour['hour'];
                        }
                    }
                    if ($m_min >= 60) {
                        $m_hour += intval($m_min / 60);
                        $m_min = intval($m_min % 60);
                    }
                    echo round($m_hour) . " : " . round($m_min) . " m";;
                    ?></h3>
                <span class="hidden-xs"><?= lang('this_month_working') . ' ' . lang('hours') ?></span>
            </div>
            <div class="col-xs-3 user-timer">
                <h3 class="m0"><?php
                    $min = 0;
                    $hour = 0;
                    if (!empty($all_working_hour)) {
                        foreach ($all_working_hour as $v_all_hours) {
                            $min += $v_all_hours['minute'];
                            $hour += $v_all_hours['hour'];
                        }
                    }
                    if ($min >= 60) {
                        $hour += intval($min / 60);
                        $min = intval($min % 60);
                    }
                    echo round($hour) . " : " . round($min) . " m";;
                    ?></h3>
                <span class="hidden-xs"><?= lang('working') . ' ' . lang('hours') ?></span>
            </div>
        </div>
    </div>

</div>
<?php include_once 'asset/admin-ajax.php'; ?>
<?= message_box('success'); ?>
<?= message_box('error'); ?>

<div class="row mt-lg">
    <div class="col-sm-2">
    
    </div>
    <div class="col-sm-10">
    
    </div>
</div>

<?php
$this->load->view('admin/common/tabs');
?>




