<?php
$all_task_info = $this->db->where('project_id', $project_details->project_id)->order_by('task_id', 'DESC')->get('tbl_task')->result();
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('gantt') ?>
            <span class="pull-right">
                <div class="btn-group pull-right-responsive margin-right-3">
                    <button type="button" class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <?= lang('show_gantt_by'); ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu animated zoomIn pull-right" role="menu">
                        <li><a href="#" class="resize-gantt"><?= lang('milestones'); ?></a></li>
                        <li><a href="#" class="user-gantt"><?= lang('project_members'); ?></a></li>
                        <li><a href="#" class="status-gantt"><?= lang('status'); ?></a></li>
                    </ul>
                </div>
            </span>
        </h3>
    </div>
    <div class="">
        <?php
        //get gantt data for Milestones
        $gantt_data = '{name: "' . $project_details->project_name . '", desc: "", values: [{
                                label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-headerline"
                                }]},  ';
        foreach ($all_task_info as $g_task) {
            if (!empty($g_task)) {
                if ($g_task->milestones_id == 0) {
                    $tasks_result['uncategorized'][] = $g_task->task_id;
                } else {
                    $milestones_info = $this->db->where('milestones_id', $g_task->milestones_id)->get('tbl_milestones')->row();
                    $tasks_result[$milestones_info->milestone_name][] = $g_task->task_id;
                }
            }
        }
        if (!empty($tasks_result)) {
            foreach ($tasks_result as $cate => $tasks_info) :
                $counter = 0;
                if (!empty($tasks_info)) {
                    foreach ($tasks_info as $tasks_id) :
                        $task = $this->db->where('task_id', $tasks_id)->get('tbl_task')->row();
                        if ($cate != 'uncategorized') {
                            $milestone = $this->db->where('milestones_id', $task->milestones_id)->get('tbl_milestones')->row();
                            if (!empty($milestone)) {
                                $m_start_date = $milestone->start_date;
                                $m_end_date = $milestone->end_date;
                            }
                            $classs = 'gantt-timeline';
                        } else {
                            $cate = lang($cate);
                            $m_start_date = null;
                            $m_end_date = null;
                            $classs = '';
                        }
                        $milestone_Name = "";
                        if ($counter == 0) {
                            $milestone_Name = $cate;
                            $gantt_data .= '
                                {
                                  name: "' . $milestone_Name . '", desc: "", values: [';

                            $gantt_data .= '{
                                label: "", from: "' . $m_start_date . '", to: "' . $m_end_date . '", customClass: "' . $classs . '"
                                }';
                            $gantt_data .= ']
                                },  ';
                        }

                        $counter++;
                        $start = ($task->task_start_date) ? $task->task_start_date : $m_end_date;
                        $end = ($task->due_date) ? $task->due_date : $m_end_date;
                        if ($task->task_status == "completed") {
                            $class = "ganttGrey";
                        } elseif ($task->task_status == "in_progress") {
                            $class = "ganttin_progress";
                        } elseif ($task->task_status == "not_started") {
                            $class = "gantt_not_started";
                        } elseif ($task->task_status == "deferred") {
                            $class = "gantt_deferred";
                        } else {
                            $class = "ganttin_progress";
                        }
                        $gantt_data .= '
                          {
                            name: "", desc: "' . $task->task_name . '", values: [';

                        $gantt_data .= '{
                          label: "' . $task->task_name . '", from: "' . $start . '", to: "' . $end . '", customClass: "' . $class . '"
                          }';
                        $gantt_data .= ']
                          },  ';
                    endforeach;
                }
            endforeach;
        }

        //get gantt data for status
        $tasks_status = array('not_started', 'in_progress', 'completed', 'deferred', 'waiting_for_someone');
        $all_task = $this->db->where('project_id', $project_details->project_id)->get('tbl_task')->result();

        foreach ($tasks_status as $key => $t_status) {
            foreach ($all_task as $v_task) {
                if ($v_task->task_status == $t_status) {
                    $task_with_status[$t_status][] = $v_task;
                }

                # code...
            }
        }

        $gantt_data2 = '{name: "' . $project_details->project_name . '", desc: "", values: [{
                                label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-headerline"
                                }]},  ';
        if (!empty($task_with_status)) {
            foreach ($task_with_status as $status => $task_info) :
                $counter = 0;
                foreach ($task_info as $t_info) :

                    if ($counter == 0) {
                        $user_name = $status;
                        $gantt_data2 .= '
                                {
                                  name: "' . lang($status) . '", desc: "", values: [';

                        $gantt_data2 .= '{
                                label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-timeline"
                                }';
                        $gantt_data2 .= ']
                                },  ';
                    }
                    $counter++;
                    $start = ($t_info->task_start_date) ? $t_info->task_start_date : " ";
                    $end = ($t_info->due_date) ? $t_info->due_date : " ";
                    if ($t_info->task_status == "completed") {
                        $class = "ganttGrey";
                    } elseif ($t_info->task_status == "in_progress") {
                        $class = "ganttin_progress";
                    } elseif ($t_info->task_status == "not_started") {
                        $class = "gantt_not_started";
                    } elseif ($t_info->task_status == "deferred") {
                        $class = "gantt_deferred";
                    } else {
                        $class = "ganttin_progress";
                    }
                    $gantt_data2 .= '
                          {
                            name: "", desc: "' . $t_info->task_name . '", values: [';

                    $gantt_data2 .= '{
                          label: "' . $t_info->task_name . '", from: "' . $start . '", to: "' . $end . '", customClass: "' . $class . '"
                          }';
                    $gantt_data2 .= ']
                          },  ';
                endforeach;
            endforeach;
        }
        // all task wise user id
        $all_task = $this->db->where('project_id', $project_details->project_id)->get('tbl_task')->result();
        if (!empty($all_task)) {
            foreach ($all_task as $v_task) {
                if ($v_task->permission == 'all') {
                    $t_permission_user = $this->items_model->all_permission_user('54');
                    // get all admin user
                    $admin_user = $this->db->where('role_id', 1)->get('tbl_users')->result();
                    // if not exist data show empty array.
                    if (!empty($permission_user)) {
                        $permission_user = $permission_user;
                    } else {
                        $permission_user = array();
                    }
                    if (!empty($admin_user)) {
                        $admin_user = $admin_user;
                    } else {
                        $admin_user = array();
                    }
                    $t_assign_user = array_merge($admin_user, $t_permission_user);
                    foreach ($t_assign_user as $t_users) {
                        $user_id[] = $t_users->user_id;
                    }
                    $task_user[$v_task->task_id] = array_unique($user_id);
                } else {
                    $task_permission = json_decode($v_task->permission);
                    foreach ($task_permission as $t_user_id => $v_permission) {
                        $task_user[$v_task->task_id][] = $t_user_id;
                    }
                }
            }
            foreach ($task_user as $task_id => $users_id) {
                foreach ($users_id as $key => $u_id) {
                    $all_task_by_user[$u_id][] = $task_id;
                }
            }
            // print_r($value);
            $permission = $project_details->permission;
            if ($permission == 'all') {
                $permission_user = $this->items_model->all_permission_user('57');
                // get all admin user
                $admin_user = $this->db->where('role_id', 1)->get('tbl_users')->result();
                // if not exist data show empty array.
                if (!empty($permission_user)) {
                    $permission_user = $permission_user;
                } else {
                    $permission_user = array();
                }
                if (!empty($admin_user)) {
                    $admin_user = $admin_user;
                } else {
                    $admin_user = array();
                }
                $assign_user = array_merge($admin_user, $permission_user);
                foreach ($assign_user as $users) {
                    $p_user[] = $users->user_id;
                }
                $project_user = array_unique($p_user);
            } else {
                $get_permission = json_decode($project_details->permission);
                foreach ($get_permission as $p_user_id => $v_permission) {
                    $project_user[] = $p_user_id;
                }
            }

            $gantt_data3 = '{name: "' . $project_details->project_name . '", desc: "", values: [{
                                label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-headerline"
                                }]},  ';
            foreach ($project_user as $key => $user) :
                $counter = 0;
                foreach ($all_task_by_user as $t_userid => $task_by_user) :
                    if ($user == $t_userid) {
                        $user_info = $this->db->where('user_id', $user)->get('tbl_account_details')->row();
                        if ($counter == 0) {
                            $user_name = $status;
                            $gantt_data3 .= '
                                {
                                  name: "' . $user_info->fullname . '", desc: "", values: [';

                            $gantt_data3 .= '{
                                label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-timeline"
                                }';
                            $gantt_data3 .= ']
                                },  ';
                        }
                        $counter++;
                        foreach ($task_by_user as $task_id) {
                            $t_info = $this->db->where('task_id', $task_id)->get('tbl_task')->row();
                            $start = ($t_info->task_start_date) ? $t_info->task_start_date : " ";
                            $end = ($t_info->due_date) ? $t_info->due_date : " ";
                            if ($t_info->task_status == "completed") {
                                $class = "ganttGrey";
                            } elseif ($t_info->task_status == "in_progress") {
                                $class = "ganttin_progress";
                            } elseif ($t_info->task_status == "not_started") {
                                $class = "gantt_not_started";
                            } elseif ($t_info->task_status == "deferred") {
                                $class = "gantt_deferred";
                            } else {
                                $class = "ganttin_progress";
                            }
                            $gantt_data3 .= '
                          {
                            name: "", desc: "' . $t_info->task_name . '", values: [';

                            $gantt_data3 .= '{
                          label: "' . $t_info->task_name . '", from: "' . $start . '", to: "' . $end . '", customClass: "' . $class . '"
                          }';
                            $gantt_data3 .= ']
                          },  ';
                        }
                    }
                endforeach;
            endforeach;
        }
        ?>

        <div class="gantt"></div>
        <div id="gantData">

            <script type="text/javascript">
                function ganttChart(ganttData) {
                    $(function() {
                        "use strict";
                        $(".gantt").gantt({
                            source: ganttData,
                            minScale: "years",
                            maxScale: "years",
                            navigate: "scroll",
                            itemsPerPage: 30,
                            onItemClick: function(data) {
                                console.log(data.id);
                            },
                            onAddClick: function(dt, rowId) {},
                            onRender: function() {
                                console.log("chart rendered");
                            }
                        });

                    });
                }

                ganttData = [<?= $gantt_data; ?>];
                ganttChart(ganttData);

                $(document).on("click", '.resize-gantt', function(e) {
                    ganttData = [<?= $gantt_data; ?>];
                    ganttChart(ganttData);
                });
                $(document).on("click", '.status-gantt', function(e) {
                    ganttData = [<?= $gantt_data2; ?>];
                    ganttChart(ganttData);
                });
                <?php if (!empty($gantt_data3)) { ?>
                    $(document).on("click", '.user-gantt', function(e) {
                        ganttData = [<?= $gantt_data3; ?>];
                        ganttChart(ganttData);
                    });
                <?php } ?>
            </script>
        </div>
    </div>
</div>


<?php
$direction = $this->session->userdata('direction');
if (!empty($direction) && $direction == 'rtl') {
    $RTL = 'on';
} else {
    $RTL = config_item('RTL');
}
if (!empty($RTL)) {
?>
    <link href="<?php echo base_url() ?>assets/plugins/ganttView/jquery.ganttViewRTL.css?ver=3.0.0" rel="stylesheet">
    <script src="<?php echo base_url() ?>assets/plugins/ganttView/jquery.ganttViewRTL.js"></script>
<?php } else {
?>
    <link href="<?php echo base_url() ?>assets/plugins/ganttView/jquery.ganttView.css?ver=3.0.0" rel="stylesheet">
    <script src="<?php echo base_url() ?>assets/plugins/ganttView/jquery.ganttView.js"></script>
<?php } ?>