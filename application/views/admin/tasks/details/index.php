<?php
$edited = can_action('54', 'edited');
$can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $task_details->task_id));
$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $task_details->task_id, 'module_name' => 'tasks');
$check_existing = $this->tasks_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $title = lang('remove_todo');
} else {
    $url = 'add_todo_list/tasks/' . $task_details->task_id;
    $btn = 'warning';
    $title = lang('add_todo_list');
}
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?php if (!empty($task_details->task_name)) echo $task_details->task_name; ?>
            <div class="pull-right ml-sm">
                <a data-toggle="tooltip" data-placement="top" title="<?= $title ?>"
                   href="<?= base_url() ?>admin/projects/<?= $url ?>"
                   class="btn-xs btn btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>
            </div>
            <div class="pull-right ml-sm">
                <a data-toggle="tooltip" data-placement="top" title="<?= lang('export_report') ?>"
                   href="<?= base_url() ?>admin/tasks/export_report/<?= $task_details->task_id ?>"
                   class="btn-xs btn btn-success"><i class="fa fa-file-pdf-o"></i></a>
            </div>
            <?php
            
            if (!empty($can_edit) && !empty($edited)) {
                ?>
                <span class="btn-xs pull-right"><a
                            href="<?= base_url() ?>admin/tasks/create/<?= $task_details->task_id ?>"><?= lang('edit') . ' ' . lang('task') ?></a>
                                </span>
            <?php } ?>
        
        
        </h3>
    </div>
    <?php
    $p_category = $this->db->where('customer_group_id', $task_details->category_id)->get('tbl_customer_group')->row();
    if (!empty($p_category)) {
        $pc_name = $p_category->customer_group;
    } else {
        $pc_name = '-';
    }
    ?>
    <div class="panel-body form-horizontal task_details">
        <?php $task_details_view = config_item('task_details_view');
        if (!empty($task_details_view) && $task_details_view == '2') {
            ?>
            <div class="row">
                <div class="col-md-3 br">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('task_name') ?> :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                if (!empty($task_details->task_name)) {
                                    echo $task_details->task_name;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('categories') ?> :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                if (!empty($pc_name)) {
                                    echo $pc_name;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('tags') ?> :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                if (!empty($task_details)) {
                                    echo get_tags($task_details->tags, true);
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        if (!empty($task_details->project_id)) :
                            $project_info = $this->db->where('project_id', $task_details->project_id)->get('tbl_project')->row();
                            $milestones_info = $this->db->where('milestones_id', $task_details->milestones_id)->get('tbl_milestones')->row();
                            ?>
                            <div class="form-group ">
                                <div class="col-sm-4"><strong><?= lang('project_name') ?>
                                        :</strong></div>
                                <div class="col-sm-8 ">
                                    <?php if (!empty($project_info->project_name)) echo $project_info->project_name; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4"><strong><?= lang('milestone') ?>
                                        :</strong></div>
                                <div class="col-sm-8 ">
                                    <?php if (!empty($milestones_info->milestone_name)) echo $milestones_info->milestone_name; ?>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php
                        if (!empty($task_details->opportunities_id)) :
                            $opportunity_info = $this->db->where('opportunities_id', $task_details->opportunities_id)->get('tbl_opportunities')->row();
                            ?>
                            <div class="form-group">
                                <div class="col-sm-4"><strong
                                            class="mr-sm"><?= lang('opportunity_name') ?></strong></div>
                                <div class="col-sm-8">
                                    <?php if (!empty($opportunity_info->opportunity_name)) echo $opportunity_info->opportunity_name; ?>
                                </div>
                            </div>
                        <?php endif ?>
                        
                        <?php
                        if (!empty($task_details->leads_id)) :
                            $leads_info = $this->db->where('leads_id', $task_details->leads_id)->get('tbl_leads')->row();
                            ?>
                            <div class="form-group">
                                <div class="col-sm-4"><strong
                                            class="mr-sm"><?= lang('leads_name') ?></strong>
                                </div>
                                <div class="col-sm-8">
                                    <?php if (!empty($leads_info->lead_name)) echo $leads_info->lead_name; ?>
                                </div>
                            </div>
                        <?php endif ?>
                        
                        <?php
                        if (!empty($task_details->bug_id)) :
                            $bugs_info = $this->db->where('bug_id', $task_details->bug_id)->get('tbl_bug')->row();
                            ?>
                            <div class="form-group">
                                <div class="col-sm-4"><strong
                                            class="mr-sm"><?= lang('bug_title') ?></strong>
                                </div>
                                <div class="col-sm-8">
                                    <?php if (!empty($bugs_info->bug_title)) echo $bugs_info->bug_title; ?>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php
                        if (!empty($task_details->goal_tracking_id)) :
                            $goal_tracking_info = $this->db->where('goal_tracking_id', $task_details->goal_tracking_id)->get('tbl_goal_tracking')->row();
                            ?>
                            <div class="form-group">
                                <div class="col-sm-4"><strong
                                            class="mr-sm"><?= lang('goal_tracking') ?></strong></div>
                                <div class="col-sm-8">
                                    <?php if (!empty($goal_tracking_info->subject)) echo $goal_tracking_info->subject; ?>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php
                        if (!empty($task_details->sub_task_id)) :
                            $sub_task = $this->db->where('task_id', $task_details->sub_task_id)->get('tbl_task')->row();
                            ?>
                            <div class="form-group">
                                <div class="col-sm-4"><strong
                                            class="mr-sm"><?= lang('sub_tasks') ?></strong>
                                </div>
                                <div class="col-sm-8">
                                    <?php if (!empty($sub_task->task_name)) echo $sub_task->task_name; ?>
                                </div>
                            </div>
                        <?php endif ?>
                        
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('start_date') ?> :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                if (!empty($task_details->task_start_date)) {
                                    echo strftime(config_item('date_format'), strtotime($task_details->task_start_date));
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        $due_date = $task_details->due_date;
                        $due_time = strtotime($due_date);
                        $current_time = strtotime(date('Y-m-d'));
                        if ($current_time > $due_time && $task_details->task_status != 'completed') {
                            $text = 'text-danger';
                        } else {
                            $text = null;
                        }
                        ?>
                        <div class="form-group">
                            <div class="col-sm-4"><strong class="<?= $text ?>"><?= lang('due_date') ?>
                                    :</strong></div>
                            <div class="col-sm-8 <?= $text ?>">
                                <?php
                                if (!empty($task_details->due_date)) {
                                    echo strftime(config_item('date_format'), strtotime($task_details->due_date));
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('task_status') ?>
                                    :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                $disabled = null;
                                if ($task_details->task_status == 'completed') {
                                    $label = 'success';
                                    $disabled = 'disabled';
                                } elseif ($task_details->task_status == 'not_started') {
                                    $label = 'info';
                                } elseif ($task_details->task_status == 'deferred') {
                                    $label = 'danger';
                                } else {
                                    $label = 'warning';
                                }
                                ?>
                                <div class="label label-<?= $label ?>  ">
                                    <?= lang($task_details->task_status) ?></div>
                                <?php
                                ?>
                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-success dropdown-toggle"
                                                data-toggle="dropdown">
                                            <?= lang('change') ?>
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu animated zoomIn">
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/not_started' ?>"><?= lang('not_started') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/in_progress' ?>"><?= lang('in_progress') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/completed' ?>"><?= lang('completed') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/deferred' ?>"><?= lang('deferred') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/waiting_for_someone' ?>"><?= lang('waiting_for_someone') ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="col-md-3 br">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('timer_status') ?>:</strong></div>
                            <div class="col-sm-8">
                                <?php if (timer_status('tasks', $task_details->task_id, 'on')) { ?>
                                    <span class="label label-success"><?= lang('on') ?></span>
                                    
                                    <a class="btn btn-xs btn-danger "
                                       href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $task_details->task_id ?>"><?= lang('stop_timer') ?>
                                    </a>
                                <?php } else {
                                    ?>
                                    <span class="label label-danger"><?= lang('off') ?></span>
                                    <?php $this_permission = $this->tasks_model->can_action('tbl_task', 'view', array('task_id' => $task_details->task_id), true);
                                    if (!empty($this_permission)) { ?>
                                        <a class="btn btn-xs btn-success <?= $disabled ?>"
                                           href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $task_details->task_id ?>"><?= lang('start_timer') ?>
                                        </a>
                                    <?php }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('project_hourly_rate') ?> :</strong>
                            </div>
                            <div class="col-sm-8">
                                <?php
                                if (!empty($task_details->hourly_rate)) {
                                    echo $task_details->hourly_rate;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('created_by') ?> :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                if (!empty($task_details->created_by)) {
                                    echo $this->db->where('user_id', $task_details->created_by)->get('tbl_account_details')->row()->fullname;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <small><?= lang('created_date') ?> :</small>
                            </div>
                            <div class="col-sm-8">
                                <?php
                                if (!empty($task_details->due_date)) {
                                    echo strftime(config_item('date_format'), strtotime($task_details->task_created_date)) . ' ' . display_time($task_details->task_created_date);
                                }
                                ?>
                            </div>
                        </div>
                    
                    </form>
                </div>
                
                <div class="col-md-3 br">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        
                        <?php $show_custom_fields = custom_form_label(3, $task_details->task_id);
                        
                        if (!empty($show_custom_fields)) {
                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                if (!empty($v_fields)) {
                                    ?>
                                    <div class="form-group">
                                        <div class="col-sm-4"><strong><?= $c_label ?> :</strong></div>
                                        <div class="col-sm-8">
                                            <?= $v_fields ?>
                                        </div>
                                    </div>
                                <?php }
                            }
                        }
                        ?>
                        
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('estimated_hour') ?>
                                    :</strong></div>
                            <div class="col-sm-8 ">
                                <?php if (!empty($task_details->task_hour)) echo $task_details->task_hour; ?>
                                <?= lang('hours') ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('billable') ?>
                                    :</strong></div>
                            <div class="col-sm-8 ">
                                <?php if (!empty($task_details->billable)) {
                                    if ($task_details->billable == 'Yes') {
                                        $billable = 'success';
                                        $text = lang('yes');
                                    } else {
                                        $billable = 'danger';
                                        $text = lang('no');
                                    };
                                } else {
                                    $billable = '';
                                    $text = '-';
                                }; ?>
                                <strong class="label label-<?= $billable ?>">
                                    <?= $text ?>
                                </strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('participants') ?>
                                    :</strong></div>
                            <div class="col-sm-8 ">
                                <?php
                                if ($task_details->permission != 'all') {
                                    $get_permission = json_decode($task_details->permission);
                                    if (is_object($get_permission)) :
                                        foreach ($get_permission as $permission => $v_permission) :
                                            $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                            if ($user_info->role_id == 1) {
                                                $label = 'circle-danger';
                                            } else {
                                                $label = 'circle-success';
                                            }
                                            $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                            ?>
                                            
                                            
                                            <a href="#" data-toggle="tooltip" data-placement="top"
                                               title="<?= $profile_info->fullname ?>"><img
                                                        src="<?= base_url() . $profile_info->avatar ?>"
                                                        class="img-circle img-xs" alt="">
                                                <span class="custom-permission circle <?= $label ?>  circle-lg"></span>
                                            </a>
                                        <?php
                                        endforeach;
                                    endif;
                                } else { ?><strong><?= lang('everyone') ?></strong>
                                    <i title="<?= lang('permission_for_all') ?>"
                                       class="fa fa-question-circle" data-toggle="tooltip"
                                       data-placement="top"></i>
                                    
                                    <?php
                                }
                                ?>
                                <?php
                                $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $task_details->task_id));
                                if (!empty($can_edit) && !empty($edited)) {
                                    ?>
                                    <span data-placement="top" data-toggle="tooltip"
                                          title="<?= lang('add_more') ?>">
                                                        <a data-toggle="modal" data-target="#myModal"
                                                           href="<?= base_url() ?>admin/tasks/update_users/<?= $task_details->task_id ?>"
                                                           class="text-default ml"><i class="fa fa-plus"></i></a>
                                                    </span>
                                    <?php
                                }
                                ?>
                            
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        
                        <?php
                        
                        $task_time = $this->tasks_model->task_spent_time_by_id($task_details->task_id);
                        ?>
                        <?= $this->tasks_model->get_time_spent_result($task_time) ?>
                        <?php
                        if (!empty($task_details->billable) && $task_details->billable == 'Yes') {
                            $total_time = $task_time / 3600;
                            $total_cost = $total_time * $task_details->hourly_rate;
                            $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                            ?>
                            <h2 class="text-center"><?= lang('total_bill') ?>
                                : <?= display_money($total_cost, $currency->symbol) ?></h2>
                        <?php }
                        $estimate_hours = $task_details->task_hour;
                        $percentage = $this->tasks_model->get_estime_time($estimate_hours);
                        
                        if ($task_time < $percentage) {
                            $total_time = $percentage - $task_time;
                            $worked = '<storng style="font-size: 15px;"  class="required">' . lang('left_works') . '</storng>';
                        } else {
                            $total_time = $task_time - $percentage;
                            $worked = '<storng style="font-size: 15px" class="required">' . lang('extra_works') . '</storng>';
                        }
                        
                        ?>
                        <div class="text-center">
                            <div class="">
                                <?= $worked ?>
                            </div>
                            <div class="">
                                <?= $this->tasks_model->get_spent_time($total_time) ?>
                            </div>
                        </div>
                    </form>
                </div>
            
            </div>
            
            <div class="row">
                <div class="col-md-6 br ">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        <blockquote style="font-size: 12px;word-wrap: break-word;"><?php
                            if (!empty($task_details->task_description)) {
                                echo $task_details->task_description;
                            }
                            ?></blockquote>
                    </form>
                </div>
                <div class="col-md-6">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        <div class="col-sm-12">
                            <strong><?= lang('completed') ?>:</strong>
                        </div>
                        <div class="col-sm-12">
                            <?php
                            if ($task_details->task_progress < 49) {
                                $progress = 'progress-bar-danger';
                            } elseif ($task_details->task_progress > 50 && $task_details->task_progress < 99) {
                                $progress = 'progress-bar-primary';
                            } else {
                                $progress = 'progress-bar-success';
                            }
                            ?>
                            <span class="">
                                                <div class="mt progress progress-striped ">
                                                    <div class="progress-bar <?= $progress ?> " data-toggle="tooltip"
                                                         data-original-title="<?= $task_details->task_progress ?>%"
                                                         style="width: <?= $task_details->task_progress ?>%"></div>
                                                </div>
                                            </span>
                        </div>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="form-group col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('task_name') ?>
                        :</strong></label>
                <div class="col-sm-7 ">
                    <p class="form-control-static"><?= ($task_details->task_name) ?></p>
                </div>
            </div>
            
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-4"><strong><?= lang('categories') ?>
                        :</strong></label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php if (!empty($pc_name)) echo $pc_name; ?></p>
                </div>
            </div>
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('tags') ?>
                        :</strong></label>
                <div class="col-sm-7">
                    <p class="form-control-static"><?php
                        if (!empty($task_details)) {
                            echo get_tags($task_details->tags, true);
                        }
                        ?></p>
                </div>
            </div>
            
            <div class="form-group col-sm-6">
                <label class="control-label col-sm-4"><strong><?= lang('task_status') ?>
                        :</strong></label>
                <div class="pull-left mt">
                    <?php
                    $disabled = null;
                    if ($task_details->task_status == 'completed') {
                        $label = 'success';
                        $disabled = 'disabled';
                    } elseif ($task_details->task_status == 'not_started') {
                        $label = 'info';
                    } elseif ($task_details->task_status == 'deferred') {
                        $label = 'danger';
                    } else {
                        $label = 'warning';
                    }
                    ?>
                    <p class="form-control-static label label-<?= $label ?>  ">
                        <?= lang($task_details->task_status) ?></p>
                </div>
                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                    <div class="col-sm-1 mt">
                        <div class="btn-group">
                            <button class="btn btn-xs btn-success dropdown-toggle"
                                    data-toggle="dropdown">
                                <?= lang('change') ?>
                                <span class="caret"></span></button>
                            <ul class="dropdown-menu animated zoomIn">
                                <li>
                                    <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/not_started' ?>"><?= lang('not_started') ?></a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/in_progress' ?>"><?= lang('in_progress') ?></a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/completed' ?>"><?= lang('completed') ?></a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/deferred' ?>"><?= lang('deferred') ?></a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/waiting_for_someone' ?>"><?= lang('waiting_for_someone') ?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-4"><strong><?= lang('timer_status') ?>
                        :</strong></label>
                <div class="col-sm-8 mt">
                    <?php if (timer_status('tasks', $task_details->task_id, 'on')) { ?>
                        <span class="label label-success"><?= lang('on') ?></span>
                        
                        <a class="btn btn-xs btn-danger "
                           href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $task_details->task_id ?>"><?= lang('stop_timer') ?>
                        </a>
                    <?php } else {
                        ?>
                        <span class="label label-danger"><?= lang('off') ?></span>
                        <?php $this_permission = $this->tasks_model->can_action('tbl_task', 'view', array('task_id' => $task_details->task_id), true);
                        if (!empty($this_permission)) { ?>
                            <a class="btn btn-xs btn-success <?= $disabled ?>"
                               href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $task_details->task_id ?>"><?= lang('start_timer') ?>
                            </a>
                        <?php }
                    }
                    ?>
                </div>
            </div>
            
            
            <?php
            if (!empty($task_details->project_id)) :
                $project_info = $this->db->where('project_id', $task_details->project_id)->get('tbl_project')->row();
                $milestones_info = $this->db->where('milestones_id', $task_details->milestones_id)->get('tbl_milestones')->row();
                ?>
                <div class="form-group  col-sm-6">
                    <label class="control-label col-sm-5"><strong><?= lang('project_name') ?>
                            :</strong></label>
                    <div class="col-sm-7 ">
                        <p class="form-control-static">
                            <?php if (!empty($project_info->project_name)) echo $project_info->project_name; ?>
                        </p>
                    </div>
                </div>
                <div class="form-group  col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('milestone') ?>
                            :</strong></label>
                    <div class="col-sm-8 ">
                        <p class="form-control-static">
                            <?php if (!empty($milestones_info->milestone_name)) echo $milestones_info->milestone_name; ?>
                        </p>
                    </div>
                </div>
            <?php endif ?>
            <?php
            if (!empty($task_details->opportunities_id)) :
                $opportunity_info = $this->db->where('opportunities_id', $task_details->opportunities_id)->get('tbl_opportunities')->row();
                ?>
                <div class="form-group  col-sm-10">
                    <label class="control-label col-sm-3 "><strong
                                class="mr-sm"><?= lang('opportunity_name') ?></strong></label>
                    <div class="col-sm-8 " style="margin-left: -5px;">
                        <p class="form-control-static">
                            <?php if (!empty($opportunity_info->opportunity_name)) echo $opportunity_info->opportunity_name; ?>
                        </p>
                    </div>
                </div>
            <?php endif ?>
            
            <?php
            if (!empty($task_details->leads_id)) :
                $leads_info = $this->db->where('leads_id', $task_details->leads_id)->get('tbl_leads')->row();
                ?>
                <div class="form-group  col-sm-10">
                    <label class="control-label col-sm-3 "><strong
                                class="mr-sm"><?= lang('leads_name') ?></strong></label>
                    <div class="col-sm-8 " style="margin-left: -5px;">
                        <p class="form-control-static">
                            <?php if (!empty($leads_info->lead_name)) echo $leads_info->lead_name; ?></p>
                    </div>
                </div>
            <?php endif ?>
            
            <?php
            if (!empty($task_details->bug_id)) :
                $bugs_info = $this->db->where('bug_id', $task_details->bug_id)->get('tbl_bug')->row();
                ?>
                <div class="form-group  col-sm-10">
                    <label class="control-label col-sm-3 "><strong
                                class="mr-sm"><?= lang('bug_title') ?></strong></label>
                    <div class="col-sm-8 " style="margin-left: -5px;">
                        <p class="form-control-static">
                            <?php if (!empty($bugs_info->bug_title)) echo $bugs_info->bug_title; ?></p>
                    </div>
                </div>
            <?php endif ?>
            <?php
            if (!empty($task_details->goal_tracking_id)) :
                $goal_tracking_info = $this->db->where('goal_tracking_id', $task_details->goal_tracking_id)->get('tbl_goal_tracking')->row();
                ?>
                <div class="form-group  col-sm-10">
                    <label class="control-label col-sm-3 "><strong
                                class="mr-sm"><?= lang('goal_tracking') ?></strong></label>
                    <div class="col-sm-8 " style="margin-left: -5px;">
                        <p class="form-control-static">
                            <?php if (!empty($goal_tracking_info->subject)) echo $goal_tracking_info->subject; ?>
                        </p>
                    </div>
                </div>
            <?php endif ?>
            <?php
            if (!empty($task_details->sub_task_id)) :
                $sub_task = $this->db->where('task_id', $task_details->sub_task_id)->get('tbl_task')->row();
                ?>
                <div class="form-group  col-sm-10">
                    <label class="control-label col-sm-3 "><strong
                                class="mr-sm"><?= lang('sub_tasks') ?></strong></label>
                    <div class="col-sm-8 " style="margin-left: -5px;">
                        <p class="form-control-static">
                            <?php if (!empty($sub_task->task_name)) echo $sub_task->task_name; ?></p>
                    </div>
                </div>
            <?php endif ?>
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('start_date') ?>
                        :</strong></label>
                <div class="col-sm-7 ">
                    <p class="form-control-static"><?php
                        if (!empty($task_details->task_start_date)) {
                            echo strftime(config_item('date_format'), strtotime($task_details->task_start_date));
                        }
                        ?></p>
                </div>
            </div>
            <div class="form-group  col-sm-6">
                <?php
                $due_date = $task_details->due_date;
                $due_time = strtotime($due_date);
                $current_time = strtotime(date('Y-m-d'));
                if ($current_time > $due_time) {
                    $text = 'text-danger';
                } else {
                    $text = null;
                }
                ?>
                
                <label class="control-label col-sm-4"><strong
                            class="<?= $text ?>"><?= lang('due_date') ?>
                        :</strong></label>
                <div class="col-sm-8 ">
                    <p class="form-control-static"><?php
                        if (!empty($task_details->due_date)) {
                            echo strftime(config_item('date_format'), strtotime($task_details->due_date));
                        }
                        ?></p>
                
                </div>
            </div>
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('created_by') ?>
                        :</strong></label>
                <div class="col-sm-7 ">
                    <p class="form-control-static"><?php
                        if (!empty($task_details->created_by)) {
                            echo $this->db->where('user_id', $task_details->created_by)->get('tbl_account_details')->row()->fullname;
                        }
                        ?></p>
                
                </div>
            </div>
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-4"><strong><?= lang('created_date') ?>
                        :</strong></label>
                <div class="col-sm-8 ">
                    <p class="form-control-static"><?php
                        if (!empty($task_details->due_date)) {
                            echo strftime(config_item('date_format'), strtotime($task_details->task_created_date));
                        }
                        ?></p>
                
                </div>
            </div>
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('project_hourly_rate') ?>
                        :</strong></label>
                <div class="col-sm-7 ">
                    <p class="form-control-static"><?php
                        if (!empty($task_details->hourly_rate)) {
                            echo $task_details->hourly_rate;
                        }
                        ?></p>
                </div>
            </div>
            
            <?php $show_custom_fields = custom_form_label(3, $task_details->task_id);
            
            if (!empty($show_custom_fields)) {
                foreach ($show_custom_fields as $c_label => $v_fields) {
                    if (!empty($v_fields)) {
                        if (count(array($v_fields)) == 1) {
                            $col = 'col-sm-10';
                            $sub_col = 'col-sm-3';
                            $style = 'padding-left:8px';
                        } else {
                            $col = 'col-sm-6';
                            $sub_col = 'col-sm-5';
                            $style = null;
                        }
                        
                        ?>
                        <div class="form-group  <?= $col ?>" style="<?= $style ?>">
                            <label class="control-label <?= $sub_col ?>"><strong><?= $c_label ?>
                                    :</strong></label>
                            <div class="col-sm-7 ">
                                <p class="form-control-static">
                                    <strong><?= $v_fields ?></strong>
                                </p>
                            </div>
                        </div>
                    <?php }
                }
            }
            ?>
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('estimated_hour') ?>
                        :</strong></label>
                <div class="col-sm-7 ">
                    <p class="form-control-static">
                        <strong><?php if (!empty($task_details->task_hour)) echo $task_details->task_hour; ?>
                            <?= lang('hours') ?></strong>
                    </p>
                </div>
            </div>
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-5"><strong><?= lang('billable') ?>
                        :</strong></label>
                <div class="col-sm-7 ">
                    <p class="form-control-static">
                        <?php if (!empty($task_details->billable)) {
                            if ($task_details->billable == 'Yes') {
                                $billable = 'success';
                                $text = lang('yes');
                            } else {
                                $billable = 'danger';
                                $text = lang('no');
                            };
                        } else {
                            $billable = '';
                            $text = '-';
                        }; ?>
                        <strong class="label label-<?= $billable ?>">
                            <?= $text ?>
                        </strong>
                    </p>
                </div>
            </div>
            <div class="form-group  col-sm-6">
                <label class="control-label col-sm-4"><strong><?= lang('participants') ?>
                        :</strong></label>
                <div class="col-sm-8 ">
                    <?php
                    if (!empty($task_details->permission) && $task_details->permission != 'all') {
                        $get_permission = json_decode($task_details->permission);
                        if (is_object($get_permission) && !empty($get_permission)) :
                            foreach ($get_permission as $permission => $v_permission) :
                                $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                if (!empty($user_info)) {
                                    if ($user_info->role_id == 1) {
                                        $label = 'circle-danger';
                                    } else {
                                        $label = 'circle-success';
                                    }
                                    $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                    ?>
                                    
                                    
                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                       title="<?= $profile_info->fullname ?>"><img
                                                src="<?= base_url() . $profile_info->avatar ?>"
                                                class="img-circle img-xs" alt="">
                                        <span class="custom-permission circle <?= $label ?>  circle-lg"></span>
                                    </a>
                                    <?php
                                }
                            endforeach;
                        endif;
                    } else { ?>
                    <p class="form-control-static"><strong><?= lang('everyone') ?></strong>
                        <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle"
                           data-toggle="tooltip" data-placement="top"></i>
                        
                        <?php
                        }
                        ?>
                        <?php
                        $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $task_details->task_id));
                        if (!empty($can_edit) && !empty($edited)) {
                        ?>
                        <span data-placement="top" data-toggle="tooltip"
                              title="<?= lang('add_more') ?>">
                                                <a data-toggle="modal" data-target="#myModal"
                                                   href="<?= base_url() ?>admin/tasks/update_users/<?= $task_details->task_id ?>"
                                                   class="text-default ml"><i class="fa fa-plus"></i></a>
                                            </span>
                    </p>
                <?php
                }
                ?>
                
                </div>
            </div>
            
            <div class="form-group  col-sm-10">
                <label class="control-label col-sm-3 "><strong class="mr-sm"><?= lang('completed') ?>
                        :</strong></label>
                <div class="col-sm-9 " style="margin-left: -5px;">
                    <?php
                    if ($task_details->task_progress < 49) {
                        $progress = 'progress-bar-danger';
                    } elseif ($task_details->task_progress > 50 && $task_details->task_progress < 99) {
                        $progress = 'progress-bar-primary';
                    } else {
                        $progress = 'progress-bar-success';
                    }
                    ?>
                    <span class="">
                                        <div class="mt progress progress-striped ">
                                            <div class="progress-bar <?= $progress ?> " data-toggle="tooltip"
                                                 data-original-title="<?= $task_details->task_progress ?>%"
                                                 style="width: <?= $task_details->task_progress ?>%"></div>
                                        </div>
                                    </span>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <?php
                
                $task_time = $this->tasks_model->task_spent_time_by_id($task_details->task_id);
                ?>
                <?= $this->tasks_model->get_time_spent_result($task_time) ?>
                <?php
                if (!empty($task_details->billable) && $task_details->billable == 'Yes') {
                    $total_time = $task_time / 3600;
                    $total_cost = $total_time * $task_details->hourly_rate;
                    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                    ?>
                    <h2 class="text-center"><?= lang('total_bill') ?>
                        : <?= display_money($total_cost, $currency->symbol) ?></h2>
                <?php }
                $estimate_hours = $task_details->task_hour;
                $percentage = $this->tasks_model->get_estime_time($estimate_hours);
                
                if ($task_time < $percentage) {
                    $total_time = $percentage - $task_time;
                    $worked = '<storng style="font-size: 15px;"  class="required">' . lang('left_works') . '</storng>';
                } else {
                    $total_time = $task_time - $percentage;
                    $worked = '<storng style="font-size: 15px" class="required">' . lang('extra_works') . '</storng>';
                }
                
                ?>
                <div class="text-center">
                    <div class="">
                        <?= $worked ?>
                    </div>
                    <div class="">
                        <?= $this->tasks_model->get_spent_time($total_time) ?>
                    </div>
                </div>
            
            </div>
            <div class="col-sm-12">
                <blockquote style="font-size: 12px; margin-top: 5px;word-wrap: break-word;width: 100%">
                    <?php if (!empty($task_details->task_description)) echo $task_details->task_description; ?>
                </blockquote>
            </div>
        <?php } ?>
    
    </div>
</div>