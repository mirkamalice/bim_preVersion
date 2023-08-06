 <?php
    echo message_box('success');
    $comment_details = get_result('tbl_task_comment', array('module' => 'goal_tracking', 'module_field_id' => $goal_info->goal_tracking_id));    
    $all_task_info = $this->db->where('goal_tracking_id', $goal_info->goal_tracking_id)->order_by('task_id', 'DESC')->get('tbl_task')->result();
    $activities_info = $this->db->where(array('module' => 'goal_tracking', 'module_field_id' => $goal_info->goal_tracking_id))->order_by('activity_date', 'desc')->get('tbl_activities')->result();
    $goal_type_info = $this->db->where('goal_type_id', $goal_info->goal_type_id)->get('tbl_goal_type')->row();

    $progress = $this->items_model->get_progress($goal_info);

    $can_edit = $this->items_model->can_action('tbl_goal_tracking', 'edit', array('goal_tracking_id' => $goal_info->goal_tracking_id));

    $end_date = $goal_info->end_date;
    $due_time = strtotime($end_date);
    $current_time = strtotime(date('Y-m-d'));
    if ($current_time > $due_time) {
        $text = 'text-danger';
        $ribbon = 'danger';
    } else {
        $text = null;
    }

    if ($progress['progress'] == 100) {
        $prgs = '8ec165';
        $p_text = 'success';
        $ribbon = 'success';
        $text = null;
        $status = lang('achieved');
    } elseif ($progress['progress'] >= 40 && $progress['progress'] <= 50) {
        $prgs = '5d9cec';
        $p_text = 'primary';
    } elseif ($progress['progress'] >= 51 && $progress['progress'] <= 99) {
        $prgs = '7266ba';
        $p_text = 'purple';
    } else {
        $prgs = 'fb6b5b';
        $p_text = 'primary';
    }
    $edited = can_action('69', 'edited');

    ?>

 <div class="panel panel-custom">
     <div class="panel-heading">
         <div class="panel-title"><strong><?= $goal_info->subject ?> - <?= lang('details') ?> </strong>
             <?php if (!empty($can_edit) && !empty($edited)) { ?>
                 <div class="col-sm-2 pull-right">
                     <a href="<?php echo base_url() ?>admin/goal_tracking/create/<?= $goal_info->goal_tracking_id ?>" class="btn-xs "><i class="fa fa-edit"></i> <?= lang('edit') ?></a>
                 </div>
             <?php } ?>
         </div>
     </div>
     <div class="panel-body row form-horizontal task_details">

         <div class="ribbon <?php
                            if (!empty($ribbon)) {
                                echo $ribbon;
                            } else {
                                echo 'primary';
                            }
                            ?>"><span><?php
                                        if (!empty($text)) {
                                            echo lang('failed');
                                        } elseif (!empty($status)) {
                                            echo $status;
                                        } else {
                                            echo lang('ongoing');
                                        }
                                        ?></span></div>
         <!-- Details START -->
         <div class="form-group  col-sm-6">
             <label class="control-label col-sm-4"><strong><?= lang('subject') ?>
                     :</strong></label>
             <div class="col-sm-8 ">
                 <p class="form-control-static"><?= $goal_info->subject ?></p>

             </div>
         </div>
         <div class="form-group  col-sm-6">
             <label class="control-label col-sm-4"><strong><?= lang('type') ?>:</strong></label>
             <div class="col-sm-8 ">
                 <p class="form-control-static"><span data-toggle="tooltip" data-placement="top" title="<?= $goal_type_info->description ?>"><?= lang($goal_type_info->type_name) ?></span>
                 </p>

             </div>
         </div>
         <div class="form-group  col-sm-6">
             <label class="control-label col-sm-4"><strong><?= lang('start_date') ?>
                     :</strong></label>
             <div class="col-sm-8 ">
                 <p class="form-control-static">
                     <?= strftime(config_item('date_format'), strtotime($goal_info->start_date)); ?>
                 </p>
             </div>
         </div>

         <div class="form-group  col-sm-6">
             <label class="control-label col-sm-4 <?= $text ?>"><strong><?= lang('end_date') ?>
                     :</strong></label>
             <div class="col-sm-8 ">
                 <p class="form-control-static <?= $text ?>">
                     <?= strftime(config_item('date_format'), strtotime($goal_info->end_date)); ?>
                 </p>
             </div>
         </div>
         <div class="form-group  col-sm-6">
             <label class="control-label col-sm-4 <?php if (!empty($text)) {
                                                        echo $text;
                                                    } else {
                                                        echo 'text-' . $p_text;
                                                    } ?>"><strong><?= lang('status') ?>
                     :</strong></label>
             <div class="col-sm-8 ">
                 <p class="form-control-static <?php if (!empty($text)) {
                                                    echo $text;
                                                } else {
                                                    echo 'text-' . $p_text;
                                                } ?>">
                     <?php
                        if (!empty($text)) {
                            echo lang('failed');
                        } elseif (!empty($status)) {
                            echo $status;
                        } else {
                            echo lang('ongoing');
                        }
                        ?>
                     <span class="pull-right">
                         <?php
                            if (!empty($text)) { ?>
                             <a class="btn btn-danger" href="<?= base_url() ?>admin/goal_tracking/send_notifier/<?= $goal_info->goal_tracking_id; ?>/field"><?= lang('send_notifier') ?></a>
                         <?php } elseif (!empty($status)) { ?>
                             <a class="btn btn-success" href="<?= base_url() ?>admin/goal_tracking/send_notifier/<?= $goal_info->goal_tracking_id; ?>/success"><?= lang('send_notifier') ?></a>
                         <?php } ?>

                     </span>
                 </p>
             </div>
         </div>
         <div class="form-group  col-sm-6">
             <label class="control-label col-sm-4"><strong><?= lang('participants') ?>
                     :</strong></label>
             <div class="col-sm-8 ">
                 <?php
                    if ($goal_info->permission != 'all') {
                        $get_permission = json_decode($goal_info->permission);
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
                     <p class="form-control-static"><strong><?= lang('everyone') ?></strong>
                         <i title="<?= lang('permission_for_all') ?>" class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"></i>

                     <?php
                    }
                        ?>
                     <?php if (!empty($can_edit) && !empty($edited)) { ?>
                         <span data-placement="top" data-toggle="tooltip" title="<?= lang('add_more') ?>">
                             <a data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/goal_tracking/update_users/<?= $goal_info->goal_tracking_id ?>" class="text-default ml"><i class="fa fa-plus"></i></a>
                         </span>
                     </p>
                 <?php
                        }
                    ?>
             </div>
         </div>
         <div class="form-group  col-sm-12 text-center  mt-lg">
             <h4>
                 <small> <?= lang('completed') . ' ' . lang('achievements') ?> :</small>
                 <?= $progress['achievement'] ?>
             </h4>
             <small class="text-center block">
                 <?= lang('achievement') ?>:
                 <?= $goal_info->achievement ?>

             </small>
             <div class="text-center block mt-lg">
                 <div style="display: inline-block">
                     <div id="easypie3" data-percent="<?= $progress['progress'] ?>" data-bar-color="#<?= $prgs ?>" class="easypie-chart">
                         <span class="h2"><?= $progress['progress'] ?>%</span>
                         <div class="easypie-text"><?= lang('done') ?></div>
                     </div>
                 </div>
             </div>

         </div>
         <div class="form-group col-sm-12">
             <div class="col-sm-12">
                 <blockquote style="font-size: 12px; height: 100px;"><?php
                                                                        if (!empty($goal_info->description)) {
                                                                            echo $goal_info->description;
                                                                        }
                                                                        ?></blockquote>
             </div>
         </div>

         <!-- Details END -->
     </div>
 </div>