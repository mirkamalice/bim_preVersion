<?php 
if (!empty($project_details->client_id)) {
    $currency = $this->items_model->client_currency_symbol($project_details->client_id);
} else {
    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
}
$project_settings = json_decode($project_details->project_settings);


$comment_details = $this->db->where(array('module_field_id' => $project_details->module_field_id, 'comments_reply_id' => '0', 'attachments_id' => '0', 'uploaded_files_id' => '0'))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();

$where = array('project_id' => $project_details->project_id, 'client_visible' => 'Yes');

$total_timer = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_tasks_timer')->result();
$all_expense_info = $this->db->where(array('project_id' => $project_details->project_id, 'type' => 'Expense'))->get('tbl_transactions')->result();

$total_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense'))->get('tbl_transactions')->row();
$billable_expense = $this->db->select_sum('amount')->where(array('project_id' => $project_details->project_id, 'type' => 'Expense', 'billable' => 'Yes'))->get('tbl_transactions')->row();


$project_hours = $this->items_model->calculate_project('project_hours', $project_details->project_id);

if ($project_details->billing_type == 'tasks_hours' || $project_details->billing_type == 'tasks_and_project_hours') {
    $tasks_hours = $this->items_model->total_project_hours($project_details->project_id, '', true);
}
$project_cost = $this->items_model->calculate_project('project_cost', $project_details->project_id);
$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $project_details->project_id, 'module_name' => 'project');
$check_existing = $this->items_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $title = lang('remove_todo');
} else {
    $url = 'add_todo_list/project/' . $project_details->project_id;
    $btn = 'warning';
    $title = lang('add_todo_list');
}
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

?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?php if (!empty($project_details->project_name)) echo $project_details->project_name; ?>
        </h3>
    </div>
    <div class="panel-body form-horizontal task_details">
        <?php
        $client_info = $this->db->where('client_id', $project_details->client_id)->get('tbl_client')->row();
        if (!empty($client_info)) {
            $name = $client_info->name;
        } else {
            $name = '-';
        }
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
                                    <a href="<?php echo $project_details->demo_url; ?>" target="_blank"><?php echo $project_details->demo_url ?></a>
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
                            if (!empty($project_details->project_status)) {
                                if ($project_details->project_status == 'completed') {
                                    $status = "<div class='label label-success'>" . lang($project_details->project_status) . "</div>";
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
                            <?php if ($project_details->timer_status == 'on') { ?>
                                <span class="label label-success"><?= lang('on') ?></span>
                            <?php } else {
                            ?>
                                <span class="label label-danger"><?= lang('off') ?></span>
                            <?php
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
                            <strong><?= ($project_details->estimate_hours); ?> m
                            </strong>
                            <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours') { ?>
                                <small class="small text-muted">
                                    <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                                <?php } ?>
                                </small>
                        </div>
                    </div>
                    <?php if (!empty($project_settings[14]) && $project_settings[14] == 'show_finance_overview') { ?>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('project_cost') ?> :</strong></div>
                            <div class="col-sm-8">
                                <strong><?= display_money($project_cost, $currency->symbol); ?></strong>
                                <?php if (!empty($project_details) && $project_details->billing_type == 'project_hours' || !empty($project_details) && $project_details->billing_type == 'tasks_and_project_hours') { ?>
                                    <small class="small text-muted">
                                        <?= $project_details->hourly_rate . "/" . lang('hour') ?>
                                    </small>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($project_settings[0]) && $project_settings[0] == 'show_team_members') { ?>
                        <div class="form-group">
                            <div class="col-sm-4"><strong><?= lang('participants') ?>
                                    :</strong></div>
                            <div class="col-sm-8">
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


                                            <a href="#" data-toggle="tooltip" data-placement="top" title="<?= $profile_info->fullname ?>"><img src="<?= base_url() . $profile_info->avatar ?>" class="img-circle img-xs" alt="">
                                                <span class="custom-permission circle <?= $label ?>  circle-lg"></span>
                                            </a>
                                    <?php
                                        endforeach;
                                    endif;
                                } else { ?>
                                    <strong><?= lang('everyone') ?></strong>
                                    <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"></i>

                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
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
            <div class="col-md-3">
                <p class="lead bb"></p>
                <form class="form-horizontal p-20">
                    <?php if (!empty($project_settings[10]) && $project_settings[10] == 'show_project_hours') { ?>
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

                        $completed = count(array($this->db->where(array('project_id' => $project_details->project_id, 'task_status' => 'completed'))->get('tbl_task')->result()));

                        $total_task = count(array($all_task_info));
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
                        if (!empty($tasks_hours)) {
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
                                        <span style="font-size: 20px">: <?= $this->items_model->get_spent_time($total_hours); ?></span>
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
                                            <strong><?= lang('billable') ?></strong>: <?= $this->items_model->get_spent_time($tasks_hours) ?>
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
                                <?php if (!empty($project_settings[14]) && $project_settings[14] == 'show_finance_overview') { ?>
                                    <h2 class="text-center mt"><?= lang('total_bill') ?>
                                        : <?= display_money($project_cost, $currency->symbol) ?></h2>
                                <?php } ?>
                                <?php if (!empty($col_)) { ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
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

                    <?php if (!empty($project_settings[10]) && $project_settings[10] == 'show_project_hours') { ?>
                        <?php if (empty($completed)) {
                            $completed = 0;
                            $total_task = 0;
                            $task_progress = 0;
                        }
                        if (empty($tprogress)) {
                            $tprogress = 0;
                        }
                        ?>

                        <div class="col-sm-12">
                            <strong><?= $TotalGone . ' / ' . $totalDays . ' ' . $lang . ' (' . round($tprogress, 2) . '% )'; ?></strong>
                        </div>
                        <div class="col-sm-12">
                            <div class="mt progress progress-striped progress-xs">
                                <div class="progress-bar progress-<?= $p_bar ?> " data-toggle="tooltip" data-original-title="<?= round($tprogress, 2) ?>%" style="width: <?= round($tprogress, 2) ?>%"></div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <strong><?= $completed . ' / ' . $total_task . ' ' . lang('open') . ' ' . lang('tasks') . ' (' . round($task_progress, 2) . '% )'; ?> </strong>
                        </div>

                        <div class="col-sm-12">
                            <div class="mt progress progress-striped progress-xs">
                                <div class="progress-bar progress-<?= $t_bar ?> " data-toggle="tooltip" data-original-title="<?= $task_progress ?>%" style="width: <?= $task_progress ?>%"></div>
                            </div>
                        </div>
                    <?php } ?>


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
                        <div class="mt progress progress-striped progress-xs">
                            <div class="progress-bar <?= $progress_b ?> " data-toggle="tooltip" data-original-title="<?= $progress ?>%" style="width: <?= $progress ?>%"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>