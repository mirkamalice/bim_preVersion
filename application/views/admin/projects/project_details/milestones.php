<?php
$edited = can_action('57', 'edited');

$miles_active = 1;
$task_timer_id = $this->uri->segment(6);
if ($task_timer_id) {
    $miles_active = 2;
    $milestones_info = get_row('tbl_milestones', array('milestones_id' => $task_timer_id));
}
?>
<div class="box" style="border: none; " data-collapsed="0">
    <?php
    $kanban = $this->session->userdata('milestone_kanban');
    $uri_segment = $this->uri->segment(6);
    if (!empty($kanban)) {
        $k_milestone = 'kanban';
    } elseif ($uri_segment == 'kanban') {
        $k_milestone = 'kanban';
    } else {
        $k_milestone = 'list';
    }
    if ($k_milestone == 'kanban') {
        $text = 'list';
        $btn = 'purple';
    } else {
        $text = 'kanban';
        $btn = 'danger';
    }
    ?>
    <div class="mb-lg ">
        <div class="pr-lg">
            <a href="<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/milestones/<?= $text ?>"
               class="btn btn-xs btn-<?= $btn ?> " data-toggle="tooltip" data-placement="top"
               title="<?= lang('switch_to_' . $text) ?>">
                <i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_' . $text) ?>
            </a>
        </div>
    </div>
    <?php if ($k_milestone == 'kanban') { ?>
        <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/kanban/kan-app.css"/>
        <div class="app-wrapper">
            <p class="total-card-counter" id="totalCards"></p>
            <div class="board" id="board"></div>
        </div>
        <?php include_once 'assets/plugins/kanban/milestone_kan-app.php'; ?>
    <?php } else { ?>
        <div class="panel panel-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $miles_active == 1 ? 'active' : ''; ?>"><a href="#manage_milestone"
                                                                          data-toggle="tab"><?= lang('milestones') ?></a>
                </li>
                <?php if (!empty($edited)) { ?>
                    <li class="<?= $miles_active == 2 ? 'active' : ''; ?>"><a href="#create_milestone"
                                                                              data-toggle="tab"><?= lang('add_milestone') ?></a>
                    </li>
                <?php } ?>
            </ul>
            <div class="tab-content bg-white">
                <!-- ************** general *************-->
                <div class="tab-pane <?= $miles_active == 1 ? 'active' : ''; ?>" id="manage_milestone">
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('milestone_name') ?></th>
                                <th class="col-date"><?= lang('start_date') ?></th>
                                <th class="col-date"><?= lang('due_date') ?></th>
                                <th><?= lang('progress') ?></th>
                                <th><?= lang('action') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $all_milestones_info = get_result('tbl_milestones', array('project_id' => $project_details->project_id));
                            if (!empty($all_milestones_info)) {
                                foreach ($all_milestones_info as $key => $v_milestones) {
                                    $progress = $this->items_model->calculate_milestone_progress($v_milestones->milestones_id);
                                    
                                    ?>
                                    <tr id="table-milestones-<?= $v_milestones->milestones_id ?>">
                                        <td><a class="text-info" href="#"
                                               data-original-title="<?= $v_milestones->description ?>"
                                               data-toggle="tooltip" data-placement="top"
                                               title=""><?= $v_milestones->milestone_name ?></a></td>
                                        <td><?= strftime(config_item('date_format'), strtotime($v_milestones->start_date)) ?>
                                        </td>
                                        <td><?php
                                            $due_date = $v_milestones->end_date;
                                            $due_time = strtotime($due_date);
                                            $current_time = strtotime(date('Y-m-d'));
                                            ?>
                                            <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                            <?php if ($current_time > $due_time && $progress < 100) { ?>
                                                <span class="label label-danger"><?= lang('overdue') ?></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="inline ">
                                                <div class="easypiechart text-success" style="margin: 0px;"
                                                     data-percent="<?= $progress ?>" data-line-width="5"
                                                     data-track-Color="#f0f0f0" data-bar-color="#<?php
                                                if ($progress >= 100) {
                                                    echo '8ec165';
                                                } else {
                                                    echo 'fb6b5b';
                                                }
                                                ?>" data-rotate="270" data-scale-Color="false" data-size="50"
                                                     data-animate="2000">
                                                        <span class="small text-muted"><?= $progress ?>
                                                            %</span>
                                                </div>
                                            </div>
                                        
                                        </td>
                                        <td>
                                            <?php echo btn_edit('admin/projects/project_details/' . $v_milestones->project_id . '/milestones/' . $v_milestones->milestones_id) ?>
                                            <?php echo ajax_anchor(base_url("admin/projects/delete_milestones/" . $v_milestones->project_id . '/' . $v_milestones->milestones_id), "<i class='btn btn-danger btn-xs fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table-milestones-" . $v_milestones->milestones_id)); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane <?= $miles_active == 2 ? 'active' : ''; ?>" id="create_milestone">
                    <form role="form" enctype="multipart/form-data" id="form"
                          action="<?php echo base_url(); ?>admin/projects/save_milestones/<?php
                          if (!empty($milestones_info)) {
                              echo $milestones_info->milestones_id;
                          }
                          ?>" method="post" class="form-horizontal  ">
                        
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('milestone_name') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="hidden" class="form-control" value="<?= $project_details->project_id ?>"
                                       name="project_id">
                                <input type="text" class="form-control" value="<?php
                                if (!empty($milestones_info)) {
                                    echo $milestones_info->milestone_name;
                                }
                                ?>" placeholder="<?= lang('milestone_name') ?>" name="milestone_name" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('description') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <textarea name="description" class="form-control"
                                          placeholder="<?= lang('description') ?>" required><?php
                                    if (!empty($milestones_info->description)) {
                                        echo $milestones_info->description;
                                    }
                                    ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('start_date') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="text" name="start_date" class="form-control start_date" value="<?php
                                    if (!empty($milestones_info->start_date)) {
                                        echo $milestones_info->start_date;
                                    }
                                    ?>" data-date-format="<?= config_item('date_picker_format'); ?>" required>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('end_date') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <input type="text" name="end_date" class="form-control end_date" value="<?php
                                    if (!empty($milestones_info->end_date)) {
                                        echo $milestones_info->end_date;
                                    }
                                    ?>" data-date-format="<?= config_item('date_picker_format'); ?>" required="">
                                    <div class="input-group-addon">
                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="project_id" value="<?php
                        if (!empty($project_details->project_id)) {
                            echo $project_details->project_id;
                        }
                        ?>" class="form-control">
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('responsible') ?> <span
                                        class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <select name="user_id" style="width: 100%" class="select_box" required="">
                                    <optgroup label="<?= lang('admin_staff') ?>">
                                        <?php
                                        $user_info = $this->items_model->allowed_user('57');
                                        if (!empty($user_info)) {
                                            foreach ($user_info as $key => $v_user) {
                                                ?>
                                                <option value="<?= $v_user->user_id ?>" <?php
                                                if (!empty($milestones_info->user_id)) {
                                                    echo $v_user->user_id == $milestones_info->user_id ? 'selected' : '';
                                                }
                                                ?>>
                                                    <?= ucfirst($v_user->username) ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="field-1" class="col-sm-3 control-label"><?= lang('visible_to_client') ?>
                                <span class="required">*</span></label>
                            <div class="col-sm-6">
                                <input data-toggle="toggle" name="client_visible" value="Yes" <?php
                                if (!empty($milestones_info) && $milestones_info->client_visible == 'Yes') {
                                    echo 'checked';
                                }
                                ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>" data-onstyle="success"
                                       data-offstyle="danger" type="checkbox">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <button type="submit"
                                        class="btn btn-sm btn-primary"><?= lang('add_milestone') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>