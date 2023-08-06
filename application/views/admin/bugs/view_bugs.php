<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<style>
.note-editor .note-editable {
    height: 150px;
}
</style>
<?php
$edited = can_action('58', 'edited');
$can_edit = $this->bugs_model->can_action('tbl_bug', 'edit', array('bug_id' => $bug_details->bug_id));
$comment_details = $this->db->where(array('bug_id' => $bug_details->bug_id, 'comments_reply_id' => '0', 'attachments_id' => '0', 'uploaded_files_id' => '0'))->order_by('comment_datetime', 'DESC')->get('tbl_task_comment')->result();
$activities_info = $this->db->where(array('module' => 'bugs', 'module_field_id' => $bug_details->bug_id))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();
$all_task_info = $this->db->where('bug_id', $bug_details->bug_id)->order_by('task_id', 'DESC')->get('tbl_task')->result();
$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $bug_details->bug_id, 'module_name' => 'bugs');
$check_existing = $this->bugs_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $title = lang('remove_todo');
} else {
    $url = 'add_todo_list/bugs/' . $bug_details->bug_id;
    $btn = 'warning';
    $title = lang('add_todo_list');
}
?>
<div class="row mt-lg">
    <div class="col-sm-2">
        <!-- <ul class="nav nav-pills nav-stacked navbar-custom-nav">
            <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#task_details"
                    data-toggle="tab"><?= lang('details') ?></a></li>
            <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#task_comments"
                    data-toggle="tab"><?= lang('comments') ?><strong
                        class="pull-right"><?= (!empty($comment_details) ? count(array($comment_details)) : null) ?></strong></a>
            </li>
            <li class="<?= $active == 3 ? 'active' : '' ?>"><a href="#task_attachments"
                    data-toggle="tab"><?= lang('attachment') ?><strong
                        class="pull-right"><?= (!empty($project_files_info) ? count(array($project_files_info)) : null) ?></strong></a>
            </li>
            <li class="<?= $active == 6 ? 'active' : '' ?>"><a href="#tasks"
                    data-toggle="tab"><?= lang('tasks') ?><strong
                        class="pull-right"><?= (!empty($all_task_info) ? count(array($all_task_info)) : null) ?></strong></a>
            </li>
            <li class="<?= $active == 4 ? 'active' : '' ?>"><a href="#task_notes"
                    data-toggle="tab"><?= lang('notes') ?></a></li>
            <li class="<?= $active == 5 ? 'active' : '' ?>"><a href="#activities"
                    data-toggle="tab"><?= lang('activities') ?><strong
                        class="pull-right"><?= (!empty($activities_info) ? count(array($activities_info)) : null) ?></strong></a>
        </ul> -->
    </div>
    <div class="col-sm-10">
        <div class="tab-content" style="border: 0;padding:0;">
            <!-- Task Details tab Starts -->
            <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_details" style="position: relative;">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php
                                                if (!empty($bug_details->bug_title)) {
                                                    echo $bug_details->bug_title;
                                                }
                                                ?>
                            <div class="pull-right ml-sm " style="margin-top: -6px">
                                <a data-toggle="tooltip" data-placement="top" title="<?= $title ?>"
                                    href="<?= base_url() ?>admin/projects/<?= $url ?>" class="btn btn-<?= $btn ?>"><i
                                        class="fa fa-thumb-tack"></i></a>
                            </div>
                            <span class="btn-xs pull-right">
                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                <a
                                    href="<?= base_url() ?>admin/bugs/create/<?= $bug_details->bug_id ?>"><?= lang('edit') . ' ' . lang('bugs') ?></a>
                                <?php } ?>
                            </span>
                        </h3>
                    </div>
                    <div class="panel-body row form-horizontal task_details">
                        <div class="form-group col-sm-12">
                            <div class="col-sm-6">
                                <label class="control-label col-sm-4"><strong><?= lang('issue_#') ?> :</strong>
                                </label>
                                <p class="form-control-static">
                                    <?php if (!empty($bug_details->issue_no)) echo $bug_details->issue_no; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label col-sm-4"><strong><?= lang('bug_title') ?> :</strong>
                                </label>
                                <p class="form-control-static">
                                    <?php if (!empty($bug_details->bug_title)) echo $bug_details->bug_title; ?></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label col-sm-4"><strong><?= lang('bug_status') ?>
                                        :</strong></label>
                                <div class="pull-left">
                                    <?php

                                    if ($bug_details->bug_status == 'unconfirmed') {
                                        $label = 'warning';
                                    } elseif ($bug_details->bug_status == 'confirmed') {
                                        $label = 'info';
                                    } elseif ($bug_details->bug_status == 'in_progress') {
                                        $label = 'primary';
                                    } elseif ($bug_details->bug_status == 'resolved') {
                                        $label = 'purple';
                                    } else {
                                        $label = 'success';
                                    }
                                    $user_info = $this->db->where('user_id', $bug_details->reporter)->get('tbl_users')->row();
                                    ?>
                                    <p class="form-control-static"><span
                                            class="label label-<?= $label ?>"><?php if (!empty($bug_details->bug_status)) echo lang($bug_details->bug_status); ?></span>
                                    </p>
                                </div>
                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                <div class="col-sm-1 mt">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
                                            <?= lang('change') ?>
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu animated zoomIn">

                                            <li>
                                                <a
                                                    href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/unconfirmed"><?= lang('unconfirmed') ?></a>
                                            </li>
                                            <li>
                                                <a
                                                    href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/confirmed"><?= lang('confirmed') ?></a>
                                            </li>
                                            <li>
                                                <a
                                                    href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/in_progress"><?= lang('in_progress') ?></a>
                                            </li>
                                            <li>
                                                <a
                                                    href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/resolved"><?= lang('resolved') ?></a>
                                            </li>
                                            <li>
                                                <a
                                                    href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/verified"><?= lang('verified') ?></a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label col-sm-4"><strong><?= lang('reporter') ?>
                                        : </strong></label>
                                <p class="form-control-static">
                                    <?php if (!empty($bug_details->reporter)) {
                                        $users_info = $this->db->where('user_id', $bug_details->reporter)->get('tbl_account_details')->row();
                                        if ($user_info->role_id == '1') {
                                            $badge = 'danger';
                                        } elseif ($user_info->role_id == '2') {
                                            $badge = 'info';
                                        } else {
                                            $badge = 'primary';
                                        } ?>
                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $user_info->user_id ?>"> <span
                                            class="badge btn-<?= $badge ?> "><?= $users_info->fullname ?></span></a>
                                    <?php } ?>
                                </p>
                            </div>

                        </div>
                        <?php
                        if (!empty($bug_details->project_id)) :
                            $project_info = $this->db->where('project_id', $bug_details->project_id)->get('tbl_project')->row();
                        ?>
                        <div class="form-group  col-sm-10">
                            <label class="control-label col-sm-3 "><strong
                                    class="mr-sm"><?= lang('project_name') ?></strong></label>
                            <div class="col-sm-8 " style="margin-left: -5px;">
                                <p class="form-control-static">
                                    <?php if (!empty($project_info->project_name)) echo $project_info->project_name; ?>
                                </p>
                            </div>
                        </div>
                        <?php endif ?>

                        <?php
                        if (!empty($bug_details->opportunities_id)) :
                            $opportunity_info = $this->db->where('opportunities_id', $bug_details->opportunities_id)->get('tbl_opportunities')->row();
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

                        <div class="form-group col-sm-12">


                            <div class="col-sm-6">
                                <label class="control-label col-sm-4"><strong><?= lang('priority') ?>
                                        :</strong>
                                </label>
                                <?php
                                if ($bug_details->priority == 'High') {
                                    $label = 'danger';
                                } elseif ($bug_details->priority == 'Medium') {
                                    $label = 'info';
                                } else {
                                    $label = 'primary';
                                }
                                ?>
                                <p class="form-control-static">
                                    <span
                                        class="badge btn-<?= $label ?>"><?php if (!empty($bug_details->priority)) echo lang($bug_details->priority); ?></span>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label col-sm-4"><strong><?= lang('severity') ?>
                                        :</strong>
                                </label>
                                <?php
                                if ($bug_details->severity == 'must_be_fixed') {
                                    $label = 'danger';
                                } elseif ($bug_details->priority == 'major') {
                                    $label = 'warning';
                                } elseif ($bug_details->priority == 'minor') {
                                    $label = 'info';
                                } else {
                                    $label = 'primary';
                                }
                                ?>
                                <p class="form-control-static">
                                    <span
                                        class="badge btn-<?= $label ?>"><?php if (!empty($bug_details->severity)) echo lang($bug_details->severity); ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <div class="col-sm-6">
                                <label class="control-label col-sm-4"><strong><?= lang('update_on') ?>
                                        : </strong></label>
                                <p class="form-control-static">
                                    <?= strftime(config_item('date_format'), strtotime($bug_details->update_time)) . ' ' . display_time($bug_details->update_time) ?>
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label col-sm-4"><strong><?= lang('created_date') ?> :</strong>
                                </label>

                                <p class="form-control-static">
                                    <?= strftime(config_item('date_format'), strtotime($bug_details->created_time)) . ' ' . display_time($bug_details->created_time) ?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group  col-sm-12">
                            <label class="control-label col-sm-2"><strong><?= lang('participants') ?>
                                    :</strong></label>
                            <div class="col-sm-8 ">

                                <?php
                                if ($bug_details->permission != 'all') {
                                    $get_permission = json_decode($bug_details->permission);
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
                                        src="<?= base_url() . $profile_info->avatar ?>" class="img-circle img-xs"
                                        alt="">
                                    <span class="custom-permission  <?= $label ?>  circle-lg"></span>
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
                                            href="<?= base_url() ?>admin/bugs/update_users/<?= $bug_details->bug_id ?>"
                                            class="text-default ml"><i class="fa fa-plus"></i></a>
                                    </span>
                                </p>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <?php $show_custom_fields = custom_form_label(6, $bug_details->bug_id);

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
                                    <strong><?php
                                                        if (!empty(json_decode($v_fields))) {
                                                            echo implode('<br/>', json_decode($v_fields));
                                                        } else {
                                                            echo $v_fields;
                                                        } ?></strong>
                                </p>
                            </div>
                        </div>
                        <?php }
                            }
                        }
                        ?>
                        <div class="form-group col-sm-12">
                            <div class="col-sm-12">
                                <blockquote style="font-size: 12px;word-wrap: break-word;"><?php
                                                                                            if (!empty($bug_details->bug_description)) {
                                                                                                echo $bug_details->bug_description;
                                                                                            }
                                                                                            ?></blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Task Details tab Ends -->

            <?php $comment_type = 'bugs'; ?>
            <!-- Task Comments Panel Starts --->
           
            <div class="tab-pane <?= $active == 6 ? 'active' : '' ?>" id="tasks" style="position: relative;">
                <div class="nav-tabs-custom ">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#manageTasks" data-toggle="tab"><?= lang('all_task') ?></a>
                        </li>
                        <?php if (!empty($edited)) { ?>
                        <li class=""><a
                                href="<?= base_url() ?>admin/tasks/create/bugs/<?= $bug_details->bug_id ?>"><?= lang('new_task') ?></a>
                        </li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content bg-white">
                        <!-- ************** general *************-->
                        <div class="tab-pane active" id="manageTasks" style="position: relative;">

                            <div class="box" style="border: none; padding-top: 15px;" data-collapsed="0">
                                <div class="box-body">
                                    <table class="table table-hover" id="">
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
                                            if (!empty($all_task_info)) : foreach ($all_task_info as $key => $v_task) :
                                            ?>
                                            <tr>
                                                <td class="col-sm-1">
                                                    <div class="is_complete checkbox c-checkbox">
                                                        <label>
                                                            <input type="checkbox" data-id="<?= $v_task->task_id ?>"
                                                                style="position: absolute;"
                                                                <?php
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
                                                                        ?>"
                                                        href="<?= base_url() ?>admin/tasks/details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                </td>
                                                <td><?php
                                                            $due_date = $v_task->due_date;
                                                            $due_time = strtotime($due_date);
                                                            ?>
                                                    <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                                    <?php if (strtotime(date('Y-m-d')) > $due_time && $v_task->task_progress < 100) { ?>
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
                                                    <span
                                                        class="label label-<?= $label ?>"><?= lang($v_task->task_status) ?>
                                                    </span>
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
                                                                                                                                                                                                                                            ?>"
                                                            data-rotate="270" data-scale-Color="false" data-size="50"
                                                            data-animate="2000">
                                                            <span class="small text-muted"><?= $v_task->task_progress ?>
                                                                %</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php echo btn_delete('admin/tasks/delete_task/' . $v_task->task_id) ?>
                                                    <?php echo btn_edit('admin/tasks/create/' . $v_task->task_id) ?>
                                                    <?php

                                                            if ($v_task->timer_status == 'on') { ?>
                                                    <a class="btn btn-xs btn-danger"
                                                        href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $v_task->task_id ?>"><?= lang('stop_timer') ?>
                                                    </a>

                                                    <?php } else { ?>
                                                    <a class="btn btn-xs btn-success"
                                                        href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $v_task->task_id ?>"><?= lang('start_timer') ?>
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
                        </div>
                    </div>
                </div>

            </div>
            <!-- Task Attachment Panel Ends --->
            <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="task_notes" style="position: relative;">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= lang('notes') ?></h3>
                    </div>
                    <div class="panel-body">

                        <form action="<?= base_url() ?>admin/bugs/save_bugs_notes/<?php
                                                                                    if (!empty($bug_details)) {
                                                                                        echo $bug_details->bug_id;
                                                                                    }
                                                                                    ?>" enctype="multipart/form-data"
                            method="post" id="form" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-lg-12">
                                    <textarea class="form-control textarea"
                                        name="notes"><?= $bug_details->notes ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <button type="submit" id="sbtn"
                                        class="btn btn-primary"><?= lang('updates') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane <?= $active == 7 ? 'active' : '' ?>" id="activities" style="position: relative;">
                <div class="tab-pane " id="activities">
                    <div class="panel panel-custom">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?= lang('activities') ?>
                                <?php
                                $role = $this->session->userdata('user_type');
                                if ($role == 1) {
                                ?>
                                <span class="btn-xs pull-right">
                                    <a
                                        href="<?= base_url() ?>admin/tasks/claer_activities/bugs/<?= $bug_details->bug_id ?>"><?= lang('clear') . ' ' . lang('activities') ?></a>
                                </span>
                                <?php } ?>
                            </h3>
                        </div>
                        <div class="panel-body " id="chat-box">
                            <?php
                            if (!empty($activities_info)) {
                                foreach ($activities_info as $v_activities) {
                                    $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                                    $user_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_users')->row();
                            ?>
                            <div class="timeline-2">
                                <div class="time-item">
                                    <div class="item-info">
                                        <small data-toggle="tooltip" data-placement="top"
                                            title="<?= display_datetime($v_activities->activity_date) ?>"
                                            class="text-muted"><?= time_ago($v_activities->activity_date); ?></small>

                                        <p><strong>
                                                <?php if (!empty($profile_info)) {
                                                        ?>
                                                <a href="<?= base_url() ?>admin/user/user_details/<?= $profile_info->user_id ?>"
                                                    class="text-info"><?= $profile_info->fullname ?></a>
                                                <?php } ?>
                                            </strong> <?= sprintf(lang($v_activities->activity)) ?>
                                            <strong><?= $v_activities->value1 ?></strong>
                                            <?php if (!empty($v_activities->value2)) { ?>
                                        <p class="m0 p0"><strong><?= $v_activities->value2 ?></strong></p>
                                        <?php } ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>