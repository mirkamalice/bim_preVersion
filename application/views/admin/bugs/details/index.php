
<?php 
$can_edit = $this->bugs_model->can_action('tbl_bug', 'edit', array('bug_id' => $bug_details->bug_id));
$edited = can_action('58', 'edited');
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
<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?php
                                if (!empty($bug_details->bug_title)) {
                                    echo $bug_details->bug_title;
                                }
                                ?>
            <div class="pull-right ml-sm " style="margin-top: -6px">
                <a data-toggle="tooltip" data-placement="top" title="<?= $title ?>" href="<?= base_url() ?>admin/projects/<?= $url ?>" class="btn btn-<?= $btn ?>"><i class="fa fa-thumb-tack"></i></a>
            </div>
            <span class="btn-xs pull-right">
                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                    <a href="<?= base_url() ?>admin/bugs/create/<?= $bug_details->bug_id ?>"><?= lang('edit') . ' ' . lang('bugs') ?></a>
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
                    <p class="form-control-static"><span class="label label-<?= $label ?>"><?php if (!empty($bug_details->bug_status)) echo lang($bug_details->bug_status); ?></span>
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
                                    <a href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/unconfirmed"><?= lang('unconfirmed') ?></a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/confirmed"><?= lang('confirmed') ?></a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/in_progress"><?= lang('in_progress') ?></a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/resolved"><?= lang('resolved') ?></a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>admin/bugs/change_status/<?= $bug_details->bug_id ?>/verified"><?= lang('verified') ?></a>
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
                        <a href="<?= base_url() ?>admin/user/user_details/<?= $user_info->user_id ?>"> <span class="badge btn-<?= $badge ?> "><?= $users_info->fullname ?></span></a>
                    <?php } ?>
                </p>
            </div>

        </div>
        <?php
        if (!empty($bug_details->project_id)) :
            $project_info = $this->db->where('project_id', $bug_details->project_id)->get('tbl_project')->row();
        ?>
            <div class="form-group  col-sm-10">
                <label class="control-label col-sm-3 "><strong class="mr-sm"><?= lang('project_name') ?></strong></label>
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
                <label class="control-label col-sm-3 "><strong class="mr-sm"><?= lang('opportunity_name') ?></strong></label>
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
                    <span class="badge btn-<?= $label ?>"><?php if (!empty($bug_details->priority)) echo lang($bug_details->priority); ?></span>
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
                    <span class="badge btn-<?= $label ?>"><?php if (!empty($bug_details->severity)) echo lang($bug_details->severity); ?></span>
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


                            <a href="#" data-toggle="tooltip" data-placement="top" title="<?= $profile_info->fullname ?>"><img src="<?= base_url() . $profile_info->avatar ?>" class="img-circle img-xs" alt="">
                                <span class="custom-permission  <?= $label ?>  circle-lg"></span>
                            </a>
                    <?php
                        endforeach;
                    endif;
                } else { ?>
                    <p class="form-control-static"><strong><?= lang('everyone') ?></strong>
                        <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"></i>

                    <?php
                }
                    ?>
                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                        <span data-placement="top" data-toggle="tooltip" title="<?= lang('add_more') ?>">
                            <a data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/bugs/update_users/<?= $bug_details->bug_id ?>" class="text-default ml"><i class="fa fa-plus"></i></a>
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