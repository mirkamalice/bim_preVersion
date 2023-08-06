<?php
$project_cost = $this->items_model->calculate_project('project_cost', $project_details->project_id);
$all_task_info = total_rows('tbl_task', array('project_id' => $project_details->project_id));
$all_expense_info = get_result('tbl_transactions', array('project_id' => $project_details->project_id, 'type' => 'Expense'));
$total_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense'))->get('tbl_transactions')->row();
$billable_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense', 'billable' => 'Yes'))->get('tbl_transactions')->row();
$not_billable_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense', 'billable' => 'No'))->get('tbl_transactions')->row();

$project_hours = $this->items_model->calculate_project('project_hours', $project_details->project_id);

if ($project_details->billing_type == 'tasks_hours' || $project_details->billing_type == 'tasks_and_project_hours' || $project_details->billing_type == 'tasks_sub_tasks_and_project_hours') {
    $tasks_hours = $this->items_model->total_project_hours($project_details->project_id, '', true);
    $open_tasks = true;
}
$comment_type = 'projects';
$can_edit = $this->items_model->can_action('tbl_project', 'edit', array('project_id' => $project_details->project_id));
if (!empty($project_details->client_id)) {
    $currency = $this->items_model->client_currency_symbol($project_details->client_id);
} else {
    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
}
$edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
$this->load->helper('date');
$totalDays = round((human_to_unix($project_details->end_date . ' 00:00') - human_to_unix($project_details->start_date . ' 00:00')) / 3600 / 24);
$TotalGone = $totalDays;
$tprogress = 100;
if (human_to_unix($project_details->start_date . ' 00:00') < time() && human_to_unix($project_details->end_date . ' 00:00') > time()) {
    $TotalGone = round((human_to_unix($project_details->end_date . ' 00:00') - time()) / 3600 / 24);
    $tprogress = $TotalGone / $totalDays * 100;
}
if (human_to_unix($project_details->end_date . ' 00:00') < time()) {
    $TotalGone = 0;
    $tprogress = 0;
}
if (strtotime(date('Y-m-d')) > strtotime($project_details->end_date . '00:00')) {
    $lang = lang('days_gone');
} else {
    $lang = lang('days_left');
}
$project_settings = json_decode($project_details->project_settings);
if (empty(admin())) {
    if (!empty($project_settings) && in_array('show_staff_finance_overview', $project_settings)) {
        $staff_finance = true;
    } else {
        $staff_finance = false;
    }
} else {
    $staff_finance = true;
}
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?php if (!empty($project_details->project_name)) echo $project_details->project_name; ?>
            <div class="pull-right text-sm">
                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                    <a href="<?= base_url() ?>admin/projects/create/<?= $project_details->project_id ?>"><?= lang('edit') . ' ' . lang('project') ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="panel-body form-horizontal task_details">
        <?php
        $client_info = $this->db->where('client_id', $project_details->client_id)->get('tbl_client')->row();
        if (!empty($client_info)) {
            $name = $client_info->name;
        } else {
            $name = '-';
        }
        $p_category = $this->db->where('customer_group_id', $project_details->category_id)->get('tbl_customer_group')->row();
        if (!empty($p_category)) {
            $pc_name = $p_category->customer_group;
        } else {
            $pc_name = '-';
        }
        ?>
        <?php $project_details_view = config_item('project_details_view');
        if (!empty($project_details_view) && $project_details_view == '2') {
            ?>
            <div class="row">
                <div class="col-md-3 br">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('project_no') ?> :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                if (!empty($project_details->project_no)) {
                                    echo $project_details->project_no;
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
                            <div class="col-sm-4"><strong><?= lang('project_name') ?> :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                if (!empty($project_details->project_name)) {
                                    echo $project_details->project_name;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('tags') ?> :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                echo get_tags($project_details->tags, true);
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('client') ?> :</strong></div>
                            <div class="col-sm-8">
                                <strong><?php echo $name; ?></strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('start_date') ?> :</strong></div>
                            <div class="col-sm-8">
                                <strong><?= strftime(config_item('date_format'), strtotime($project_details->start_date)) ?></strong>
                            </div>
                        </div>
                        <?php
                        $text = '';
                        if ($project_details->project_status != 'completed') {
                            if ($totalDays < 0) {
                                $overdueDays = $totalDays . ' ' . lang('days_gone');
                                $text = 'text-danger';
                            }
                        }
                        ?>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('end_date') ?> :</strong></div>
                            <div class="col-sm-8 <?= $text ?>">
                                <strong><?= strftime(config_item('date_format'), strtotime($project_details->end_date)) ?>
                                    <?php if (!empty($overdueDays)) {
                                        echo lang('overdue') . ' ' . $overdueDays;
                                    } ?></strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('demo_url') ?> :</strong></div>
                            <div class="col-sm-8">
                                <strong><?php
                                    if (!empty($project_details->demo_url)) {
                                        ?>
                                        <a href="<?php echo $project_details->demo_url; ?>"
                                           target="_blank"><?php echo $project_details->demo_url ?></a>
                                        <?php
                                    } else {
                                        echo '-';
                                    }
                                    ?></strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('status') ?>
                                    :</strong></div>
                            <div class="col-sm-8">
                                <?php
                                $disabled = null;
                                if (!empty($project_details->project_status)) {
                                    if ($project_details->project_status == 'completed') {
                                        $status = "<div class='label label-success'>" . lang($project_details->project_status) . "</div>";
                                        $disabled = 'disabled';
                                    } elseif ($project_details->project_status == 'in_progress') {
                                        $status = "<div class='label label-primary'>" . lang($project_details->project_status) . "</div>";
                                    } elseif ($project_details->project_status == 'cancel') {
                                        $status = "<div class='label label-danger'>" . lang($project_details->project_status) . "</div>";
                                    } else {
                                        $status = "<div class='label label-warning'>" . lang($project_details->project_status) . "</div>";
                                    } ?>
                                    <?= $status; ?>
                                <?php }
                                ?>
                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
                                            <?= lang('change') ?>
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu animated zoomIn">
                                            <li>
                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/started' ?>"><?= lang('started') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/in_progress' ?>"><?= lang('in_progress') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/on_hold' ?>"><?= lang('on_hold') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/cancel' ?>"><?= lang('cancel') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/completed' ?>"><?= lang('completed') ?></a>
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
                                <?php if (timer_status('projects', $project_details->project_id, 'on')) { ?>
                                    
                                    <span class="label label-success"><?= lang('on') ?></span>
                                    <a class="btn btn-xs btn-danger "
                                       href="<?= base_url() ?>admin/projects/tasks_timer/off/<?= $project_details->project_id ?>"><?= lang('stop_timer') ?>
                                    </a>
                                <?php } else {
                                    ?>
                                    <span class="label label-danger"><?= lang('off') ?></span>
                                    <?php $this_permission = $this->items_model->can_action('tbl_project', 'view', array('project_id' => $project_details->project_id), true);
                                    if (!empty($this_permission)) { ?>
                                        <a class="btn btn-xs btn-success <?= $disabled ?>"
                                           href="<?= base_url() ?>admin/projects/tasks_timer/on/<?= $project_details->project_id ?>"><?= lang('start_timer') ?>
                                        </a>
                                    <?php }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('billing_type') ?> :</strong></div>
                            <div class="col-sm-8">
                                <strong><?= lang($project_details->billing_type); ?></strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <small><?= lang('estimate_hours') ?> :</small>
                            </div>
                            <div class="col-sm-8">
                                <?= ($project_details->estimate_hours); ?> m
                                <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_sub_tasks_and_project_hours') { ?>
                                <small class="small text-muted">
                                    <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                                    <?php } ?>
                                </small>
                            </div>
                        </div>
                        <?php if (!empty($staff_finance)) { ?>
                            <div class="form-group">
                                <div class="col-sm-4"><strong><?= lang('project_cost') ?> :</strong>
                                </div>
                                <div class="col-sm-8">
                                    <strong><?= display_money($project_cost, $currency->symbol); ?></strong>
                                    <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_sub_tasks_and_project_hours') { ?>
                                    <small class="small text-muted">
                                        <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                                        <?php } ?>
                                    </small>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('participants') ?>
                                    :</strong></div>
                            <div class="col-sm-8">
                                <div class="col-sm-8 ">
                                    <?php
                                    if ($project_details->permission != 'all') {
                                        $get_permission = json_decode($project_details->permission);
                                        if (!empty($get_permission)) :
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
                                    } else { ?>
                                        <strong><?= lang('everyone') ?></strong>
                                        <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle"
                                           data-toggle="tooltip" data-placement="top"></i>
                                        
                                        <?php
                                    }
                                    ?>
                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                        <span data-placement="top" data-toggle="tooltip"
                                              title="<?= lang('add_more') ?>">
                                                            <a data-toggle="modal" data-target="#myModal"
                                                               href="<?= base_url() ?>admin/projects/update_users/<?= $project_details->project_id ?>"
                                                               class="text-default ml"><i class="fa fa-plus"></i></a>
                                                        </span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php $show_custom_fields = custom_form_label(4, $project_details->project_id);
                        
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
                    </form>
                </div>
                <?php if (!empty($staff_finance)) { ?>
                    <div class="col-md-3 br">
                        <?php
                        $paid_expense = 0;
                        foreach ($all_expense_info as $v_expenses) {
                            if ($v_expenses->invoices_id != 0) {
                                $paid_expense += $this->invoice_model->calculate_to('paid_amount', $v_expenses->invoices_id);
                            }
                        }
                        ?>
                        <p class="lead bb"></p>
                        <form class="form-horizontal p-20">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <strong><?= lang('total') . ' ' . lang('expense') ?></strong>:
                                </div>
                                <div class="col-sm-8">
                                    <strong><?= display_money($total_expense->amount, $currency->symbol) ?></strong>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <strong><?= lang('billable') . ' ' . lang('expense') ?></strong>:
                                </div>
                                <div class="col-sm-8">
                                    <strong><?= display_money($billable_expense->amount, $currency->symbol) ?></strong>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <strong><?= lang('not_billable') . ' ' . lang('expense') ?></strong>:
                                </div>
                                <div class="col-sm-8">
                                    <strong><?= display_money($not_billable_expense->amount, $currency->symbol) ?></strong>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <strong><?= lang('billed') . ' ' . lang('expense') ?></strong>:
                                </div>
                                <div class="col-sm-8">
                                    <strong><?= display_money($paid_expense, $currency->symbol) ?></strong>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <strong><?= lang('unbilled') . ' ' . lang('expense') ?></strong>:
                                </div>
                                <div class="col-sm-8">
                                    <strong><?= display_money($billable_expense->amount - $paid_expense, $currency->symbol) ?></strong>
                                </div>
                            </div>
                        
                        </form>
                    </div>
                <?php } ?>
                <div class="col-md-3">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        <?php
                        $estimate_hours = $project_details->estimate_hours;
                        $percentage = $this->items_model->get_estime_time($estimate_hours);
                        $logged_hour = $this->items_model->calculate_project('project_hours', $project_details->project_id);
                        if (!empty($tasks_hours)) {
                            $logged_tasks_hours = $tasks_hours;
                        } else {
                            $logged_tasks_hours = 0;
                        }
                        $total_logged_hours = $logged_hour + $logged_tasks_hours;
                        
                        if ($total_logged_hours < $percentage) {
                            $total_time = $percentage - $total_logged_hours;
                            $worked = '<storng style="font-size: 15px;"  class="required">' . lang('left_works') . '</storng>';
                        } else {
                            $total_time = $total_logged_hours - $percentage;
                            $worked = '<storng style="font-size: 15px" class="required">' . lang('extra_works') . '</storng>';
                        }
                        
                        $completed = total_rows('tbl_task', array('project_id' => $project_details->project_id, 'task_status' => 'completed'));
                        if (!empty($total_task)) {
                            if ($total_task != 0) {
                                $task_progress = $completed / $total_task * 100;
                            }
                            if ($task_progress > 100) {
                                $task_progress = 100;
                            }
                            if ($tprogress > 50) {
                                $p_bar = 'bar-success';
                            } else {
                                $p_bar = 'bar-danger';
                            }
                            if ($task_progress < 49) {
                                $t_bar = 'bar-danger';
                            } elseif ($task_progress < 79) {
                                $t_bar = 'bar-warning';
                            } else {
                                $t_bar = 'bar-success';
                            }
                        } else {
                            $p_bar = 'bar-danger';
                            $t_bar = 'bar-success';
                            $task_progress = 0;
                        }
                        if (!empty($open_tasks)) {
                            $col_ = 'col-sm-6';
                        } else {
                            $col_ = '';
                        }
                        ?>
                        <div class="<?= $col_ ?>">
                            <?php if (!empty($col_)) { ?>
                            <div class="panel panel-custom">
                                <div class="panel-heading">
                                    <div class="panel-title"><?= lang('project_hours') ?></div>
                                </div>
                                <?php } ?>
                                <?= $this->items_model->get_time_spent_result($project_hours); ?>
                                
                                <?php if ($project_details->billing_type == 'tasks_and_project_hours') {
                                    $total_hours = $project_hours + $tasks_hours;
                                    ?>
                                    <h2 style="font-size: 22px"><?= lang('total') ?>
                                        <span style="font-size: 20px">:
                                                            <?= $this->items_model->get_spent_time($total_hours); ?></span>
                                    </h2>
                                
                                <?php } ?>
                                <?php if (!empty($col_)) { ?>
                            </div>
                        
                        <?php } ?>
                        </div>
                        <div class="text-center">
                            <div class="">
                                <?= $worked ?>
                            </div>
                            <div class="">
                                <?= $this->items_model->get_spent_time($total_time) ?>
                            </div>
                        </div>
                        <div class="<?= $col_ ?>">
                            <?php if (!empty($col_)) { ?>
                            <div class="panel panel-custom mb-lg">
                                <div class="panel-heading">
                                    <div class="panel-title"><?= lang('task_hours') ?></div>
                                </div>
                                <?= $this->items_model->get_time_spent_result($tasks_hours); ?>
                                <div class="ml-lg">
                                    <p class="p0 m0">
                                        <strong><?= lang('billable') ?></strong>:
                                        <?= $this->items_model->get_spent_time($tasks_hours) ?>
                                    </p>
                                    <p class="p0 m0"><strong><?= lang('not_billable') ?></strong>:
                                        <?php
                                        $non_billable_time = 0;
                                        foreach ($all_task_info as $v_n_tasks) {
                                            if (!empty($v_n_tasks->billable) && $v_n_tasks->billable == 'No') {
                                                $non_billable_time += $this->items_model->task_spent_time_by_id($v_n_tasks->task_id);
                                            }
                                        }
                                        echo $this->items_model->get_spent_time($non_billable_time);
                                        ?>
                                    </p>
                                </div>
                                <?php } ?>
                                <?php if (!empty($staff_finance)) { ?>
                                    <h2 class="text-center mt"><?= lang('total_bill') ?>
                                        : <?= display_money($project_cost, $currency->symbol) ?></h2>
                                <?php } ?>
                                <?php if (!empty($col_)) { ?>
                            </div>
                        <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 br ">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        <blockquote style="font-size: 12px;word-wrap: break-word;"><?php
                            if (!empty($project_details->description)) {
                                echo $project_details->description;
                            }
                            ?></blockquote>
                    </form>
                </div>
                <div class="col-md-6">
                    <p class="lead bb"></p>
                    <form class="form-horizontal p-20">
                        <div class="col-sm-12">
                            <strong><?= $TotalGone . ' / ' . $totalDays . ' ' . $lang . ' (' . round($tprogress, 2) . '% )'; ?></strong>
                        </div>
                        <div class="col-sm-12">
                            <div class="mt progress progress-striped progress-xs">
                                <div class="progress-bar progress-<?= $p_bar ?> " data-toggle="tooltip"
                                     data-original-title="<?= round($tprogress, 2) ?>%"
                                     style="width: <?= round($tprogress, 2) ?>%"></div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <strong><?= $completed . ' / ' . $total_task . ' ' . lang('open') . ' ' . lang('tasks') . ' (' . round($task_progress, 2) . '% )'; ?>
                            </strong>
                        </div>
                        <div class="col-sm-12">
                            <div class="mt progress progress-striped progress-xs">
                                <div class="progress-bar progress-<?= $t_bar ?> " data-toggle="tooltip"
                                     data-original-title="<?= $task_progress ?>%"
                                     style="width: <?= $task_progress ?>%"></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <strong><?= lang('completed') ?>:</strong>
                        </div>
                        <div class="col-sm-12">
                            <?php
                            $progress = $this->items_model->get_project_progress($project_details->project_id);
                            
                            if ($progress < 49) {
                                $progress_b = 'progress-bar-danger';
                            } elseif ($progress > 50 && $progress < 99) {
                                $progress_b = 'progress-bar-primary';
                            } else {
                                $progress_b = 'progress-bar-success';
                            }
                            ?>
                            <span class="">
                                                <div class="mt progress progress-striped progress-xs">
                                                    <div class="progress-bar <?= $progress_b ?> " data-toggle="tooltip"
                                                         data-original-title="<?= $progress ?>%"
                                                         style="width: <?= $progress ?>%"></div>
                                                </div>
                                            </span>
                        </div>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('project_no') ?>
                            :</strong></label>
                    <p class="form-control-static">
                        <strong><?php
                            if (!empty($project_details->project_no)) {
                                echo $project_details->project_no;
                            }
                            ?></strong>
                    </p>
                </div>
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('categories') ?>
                            :</strong></label>
                    <p class="form-control-static">
                        <strong><?php echo $pc_name; ?></strong>
                    </p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('project_name') ?> :</strong>
                    </label>
                    <p class="form-control-static">
                        <?php
                        if (!empty($project_details->project_name)) {
                            echo $project_details->project_name;
                        }
                        ?>
                    </p>
                </div>
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('tags') ?> :</strong>
                    </label>
                    <p class="form-control-static">
                        <?php
                        echo get_tags($project_details->tags, true);
                        ?>
                    </p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('client') ?>
                            :</strong></label>
                    <p class="form-control-static">
                        <strong><?php echo $name; ?></strong>
                    </p>
                </div>
                <?php
                $disabled = null;
                if (!empty($project_details->project_status)) {
                    if ($project_details->project_status == 'completed') {
                        $disabled = 'disabled';
                    }
                }
                ?>
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('timer_status') ?>
                            :</strong></label>
                    <div class="col-sm-8 mt">
                        <?php if ($project_details->timer_status == 'on') { ?>
                            
                            <span class="label label-success"><?= lang('on') ?></span>
                            <a class="btn btn-xs btn-danger "
                               href="<?= base_url() ?>admin/projects/tasks_timer/off/<?= $project_details->project_id ?>"><?= lang('stop_timer') ?>
                            </a>
                        <?php } else {
                            ?>
                            <span class="label label-danger"><?= lang('off') ?></span>
                            <?php $this_permission = $this->items_model->can_action('tbl_project', 'view', array('project_id' => $project_details->project_id), true);
                            if (!empty($this_permission)) { ?>
                                <a class="btn btn-xs btn-success <?= $disabled ?>"
                                   href="<?= base_url() ?>admin/projects/tasks_timer/on/<?= $project_details->project_id ?>"><?= lang('start_timer') ?>
                                </a>
                            <?php }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('start_date') ?> :</strong>
                    </label>
                    <p class="form-control-static">
                        <?= strftime(config_item('date_format'), strtotime($project_details->start_date)) ?>
                    </p>
                </div>
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('end_date') ?>
                            :</strong></label>
                    <?php
                    $text = '';
                    if ($project_details->project_status != 'completed') {
                        if ($totalDays < 0) {
                            $overdueDays = $totalDays . ' ' . lang('days_gone');
                            $text = 'text-danger';
                        }
                    }
                    ?>
                    <p class="form-control-static <?= $text ?>">
                        <?= strftime(config_item('date_format'), strtotime($project_details->end_date)) ?>
                        <?php if (!empty($overdueDays)) {
                            echo lang('overdue') . ' ' . $overdueDays;
                        } ?>
                    </p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('billing_type') ?> :</strong>
                    </label>
                    <p class="form-control-static">
                        <strong><?= lang($project_details->billing_type); ?></strong>
                    </p>
                </div>
                <div class="col-sm-6">
                    <label class="control-label col-sm-4">
                        <small><?= lang('estimate_hours') ?> :</small>
                    </label>
                    <p class="form-control-static">
                        <strong><?= ($project_details->estimate_hours); ?> m
                        </strong>
                        <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_sub_tasks_and_project_hours') { ?>
                        <small class="small text-muted">
                            <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                            <?php } ?>
                        </small>
                    </p>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('demo_url') ?> :</strong>
                    </label>
                    <p class="form-control-static" style="overflow: hidden;">
                        <?php
                        if (!empty($project_details->demo_url)) {
                            ?>
                            <a href="<?php echo $project_details->demo_url; ?>"
                               target="_blank"><?php echo $project_details->demo_url ?></a>
                            <?php
                        } else {
                            echo '-';
                        }
                        ?>
                    </p>
                </div>
                <?php if (!empty($staff_finance)) { ?>
                    <div class="col-sm-6">
                        <label class="control-label col-sm-4"><strong><?= lang('project_cost') ?>
                                :</strong>
                        </label>
                        <p class="form-control-static">
                            <strong><?php echo display_money($project_cost, $currency->symbol); ?></strong>
                            <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_sub_tasks_and_project_hours') { ?>
                            <small class="small text-muted">
                                <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                                <?php } ?>
                            </small>
                        </p>
                    </div>
                <?php } ?>
            </div>
            <div class="form-group">
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('status') ?>
                            :</strong></label>
                    <div class="pull-left">
                        <?php
                        $disabled = null;
                        if (!empty($project_details->project_status)) {
                            if ($project_details->project_status == 'completed') {
                                $status = "<span class='label label-success'>" . lang($project_details->project_status) . "</span>";
                                $disabled = 'disabled';
                            } elseif ($project_details->project_status == 'in_progress') {
                                $status = "<span class='label label-primary'>" . lang($project_details->project_status) . "</span>";
                            } elseif ($project_details->project_status == 'cancel') {
                                $status = "<span class='label label-danger'>" . lang($project_details->project_status) . "</span>";
                            } else {
                                $status = "<span class='label label-warning'>" . lang($project_details->project_status) . "</span>";
                            } ?>
                            <p class="form-control-static"><?= $status; ?></p>
                        <?php }
                        ?>
                    </div>
                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                        <div class="col-sm-1 mt">
                            <div class="btn-group">
                                <button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
                                    <?= lang('change') ?>
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu animated zoomIn">
                                    <li>
                                        <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/started' ?>"><?= lang('started') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/in_progress' ?>"><?= lang('in_progress') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/on_hold' ?>"><?= lang('on_hold') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/cancel' ?>"><?= lang('cancel') ?></a>
                                    </li>
                                    <li>
                                        <a href="<?= base_url() ?>admin/projects/change_status/<?= $project_details->project_id . '/completed' ?>"><?= lang('completed') ?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-6">
                    <label class="control-label col-sm-4"><strong><?= lang('participants') ?>
                            :</strong></label>
                    <div class="col-sm-8 ">
                        <?php
                        if ($project_details->permission != 'all') {
                            $get_permission = json_decode($project_details->permission);
                            if (!empty($get_permission)) :
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
                        } else { ?>
                        <p class="form-control-static"><strong><?= lang('everyone') ?></strong>
                            <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle"
                               data-toggle="tooltip" data-placement="top"></i>
                            
                            <?php
                            }
                            ?>
                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                            <span data-placement="top" data-toggle="tooltip" title="<?= lang('add_more') ?>">
                                                    <a data-toggle="modal" data-target="#myModal"
                                                       href="<?= base_url() ?>admin/projects/update_users/<?= $project_details->project_id ?>"
                                                       class="text-default ml"><i class="fa fa-plus"></i></a>
                                                </span>
                        </p>
                    <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
            <?php $show_custom_fields = custom_form_label(4, $project_details->project_id);
            
            if (!empty($show_custom_fields)) {
                foreach ($show_custom_fields as $c_label => $v_fields) {
                    if (!empty($v_fields)) {
                        if (count(array($v_fields)) == 1) {
                            $col = 'col-sm-12';
                            $sub_col = 'col-sm-2';
                            $style = null;
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
            <div class="form-group  col-sm-12 mt">
                <label class="control-label col-sm-2 "><strong class="mr-sm"><?= lang('completed') ?>
                        :</strong></label>
                <div class="col-sm-8 " style="margin-left: -5px;">
                    <?php
                    $progress = $this->items_model->get_project_progress($project_details->project_id);
                    
                    if ($progress < 49) {
                        $progress_b = 'progress-bar-danger';
                    } elseif ($progress > 50 && $progress < 99) {
                        $progress_b = 'progress-bar-primary';
                    } else {
                        $progress_b = 'progress-bar-success';
                    }
                    ?>
                    <span class="">
                                        <div class="mt progress progress-striped ">
                                            <div class="progress-bar <?= $progress_b ?> " data-toggle="tooltip"
                                                 data-original-title="<?= $progress ?>%"
                                                 style="width: <?= $progress ?>%">
                                            </div>
                                        </div>
                                    </span>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <?php
                $estimate_hours = $project_details->estimate_hours;
                $percentage = $this->items_model->get_estime_time($estimate_hours);
                $logged_hour = $this->items_model->calculate_project('project_hours', $project_details->project_id);
                if (!empty($tasks_hours)) {
                    $logged_tasks_hours = $tasks_hours;
                } else {
                    $logged_tasks_hours = 0;
                }
                $total_logged_hours = $logged_hour + $logged_tasks_hours;
                
                if ($total_logged_hours < $percentage) {
                    $total_time = $percentage - $total_logged_hours;
                    $worked = '<storng style="font-size: 15px;"  class="required">' . lang('left_works') . '</storng>';
                } else {
                    $total_time = $total_logged_hours - $percentage;
                    $worked = '<storng style="font-size: 15px" class="required">' . lang('extra_works') . '</storng>';
                }
                
                $completed = total_rows('tbl_task', array('project_id' => $project_details->project_id, 'task_status' => 'completed'));
                
                $total_task = $all_task_info;
                if (!empty($total_task)) {
                    if ($total_task != 0) {
                        $task_progress = $completed / $total_task * 100;
                    }
                    if ($task_progress > 100) {
                        $task_progress = 100;
                    }
                    if ($tprogress > 50) {
                        $p_bar = 'bar-success';
                    } else {
                        $p_bar = 'bar-danger';
                    }
                    if ($task_progress < 49) {
                        $t_bar = 'bar-danger';
                    } elseif ($task_progress < 79) {
                        $t_bar = 'bar-warning';
                    } else {
                        $t_bar = 'bar-success';
                    }
                } else {
                    $p_bar = 'bar-danger';
                    $t_bar = 'bar-success';
                    $task_progress = 0;
                }
                if (!empty($open_tasks)) {
                    $col_ = 'col-sm-6';
                } else {
                    $col_ = '';
                }
                
                ?>
                <div class="<?= $col_ ?>">
                    <?php if (!empty($col_)) { ?>
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <div class="panel-title"><?= lang('project_hours') ?></div>
                        </div>
                        <?php } ?>
                        <?= $this->items_model->get_time_spent_result($project_hours); ?>
                        <?php
                        $paid_expense = 0;
                        foreach ($all_expense_info as $v_expenses) {
                            if ($v_expenses->invoices_id != 0) {
                                $paid_expense += $this->invoice_model->calculate_to('paid_amount', $v_expenses->invoices_id);
                            }
                        }
                        ?>
                        <?php if (!empty($staff_finance)) { ?>
                            <div class="ml-lg mb-lg text-center">
                                <p class="p0 m0">
                                    <strong><?= lang('total') . ' ' . lang('expense') ?></strong>:
                                    <?= display_money($total_expense->amount, $currency->symbol) ?>
                                </p>
                                <p class="p0 m0">
                                    <strong><?= lang('billable') . ' ' . lang('expense') ?></strong>:
                                    <?= display_money($billable_expense->amount, $currency->symbol) ?>
                                </p>
                                <p class="p0 m0">
                                    <strong><?= lang('not_billable') . ' ' . lang('expense') ?></strong>:
                                    <?= display_money($not_billable_expense->amount, $currency->symbol) ?>
                                </p>
                                <p class="p0 m0">
                                    <strong><?= lang('billed') . ' ' . lang('expense') ?></strong>:
                                    <?= display_money($paid_expense, $currency->symbol) ?>
                                </p>
                                <p class="p0 m0">
                                    <strong><?= lang('unbilled') . ' ' . lang('expense') ?></strong>:
                                    <?= display_money($billable_expense->amount - $paid_expense, $currency->symbol) ?>
                                </p>
                            </div>
                        <?php } ?>
                        <?php if ($project_details->billing_type == 'tasks_and_project_hours') {
                            $total_hours = $project_hours + $tasks_hours;
                            ?>
                            <h2 style="font-size: 22px"><?= lang('total') ?>
                                <span style="font-size: 20px">:
                                                    <?= $this->items_model->get_spent_time($total_hours); ?></span>
                            </h2>
                        <?php } ?>
                        <?php if (!empty($col_)) { ?>
                    </div>
                
                <?php } ?>
                </div>
                <div class="<?= $col_ ?>">
                    <?php if (!empty($col_)) { ?>
                    <div class="panel panel-custom mb-lg">
                        <div class="panel-heading">
                            <div class="panel-title"><?= lang('task_hours') ?></div>
                        </div>
                        <?= $this->items_model->get_time_spent_result($tasks_hours); ?>
                        <div class="ml-lg">
                            <p class="p0 m0">
                                <strong><?= lang('billable') ?></strong>:
                                <?= $this->items_model->get_spent_time($tasks_hours) ?>
                            </p>
                            <p class="p0 m0"><strong><?= lang('not_billable') ?></strong>:
                                <?php
                                $non_billable_time = 0;
                                foreach ($all_task_info as $v_n_tasks) {
                                    if (!empty($v_n_tasks->billable) && $v_n_tasks->billable == 'No') {
                                        $non_billable_time += $this->items_model->task_spent_time_by_id($v_n_tasks->task_id);
                                    }
                                }
                                echo $this->items_model->get_spent_time($non_billable_time);
                                ?>
                            </p>
                        </div>
                        <?php } ?>
                        <?php if (!empty($staff_finance)) { ?>
                            <h2 class="text-center mt"><?= lang('total_bill') ?>
                                : <?= display_money($project_cost, $currency->symbol) ?></h2>
                        <?php } ?>
                        <?php if (!empty($col_)) { ?>
                    </div>
                <?php } ?>
                </div>
                <div class="col-sm-12 mt-lg">
                    <div class="col-sm-4">
                        <strong><?= $TotalGone . ' / ' . $totalDays . ' ' . $lang . ' (' . round($tprogress, 2) . '% )'; ?></strong>
                        <div class="mt progress progress-striped progress-xs">
                            <div class="progress-bar progress-<?= $p_bar ?> " data-toggle="tooltip"
                                 data-original-title="<?= round($tprogress, 2) ?>%"
                                 style="width: <?= round($tprogress, 2) ?>%"></div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-center">
                            <div class="">
                                <?= $worked ?>
                            </div>
                            <div class="">
                                <?= $this->items_model->get_spent_time($total_time) ?>
                            </div>
                        </div>
                    
                    </div>
                    
                    <div class="col-sm-4">
                        <strong><?= $completed . ' / ' . $total_task . ' ' . lang('open') . ' ' . lang('tasks') . ' (' . round($task_progress, 2) . '% )'; ?>
                        </strong>
                        <div class="mt progress progress-striped progress-xs">
                            <div class="progress-bar progress-<?= $t_bar ?> " data-toggle="tooltip"
                                 data-original-title="<?= $task_progress ?>%"
                                 style="width: <?= $task_progress ?>%"></div>
                        </div>
                    </div>
                </div>
            
            
            </div>
            
            <div class="form-group col-sm-12">
                <div class="col-sm-12">
                    <blockquote style="font-size: 12px;word-wrap: break-word;"><?php
                        if (!empty($project_details->description)) {
                            echo $project_details->description;
                        }
                        ?></blockquote>
                </div>
            </div>
        <?php } ?>
    </div>
</div>