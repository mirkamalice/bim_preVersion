<?php
$edited = can_action('55', 'edited');
$sub_metting = 1;
$task_timer_id = $this->uri->segment(6);
if ($task_timer_id) {
    $sub_metting = 2;
    $mettings_info = get_row('tbl_mettings', array('mettings_id' => $task_timer_id));
}
?>
<div class="nav-tabs-custom ">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $sub_metting == 1 ? 'active' : ''; ?>"><a href="#all_metting" data-toggle="tab"><?= lang('all_metting') ?></a>
        </li>

        <li class="<?= $sub_metting == 2 ? 'active' : ''; ?>"><a href="#new_metting" data-toggle="tab"><?= lang('new_metting') ?></a>
        </li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $sub_metting == 1 ? 'active' : ''; ?>" id="all_metting">

            <div class="table-responsive">
                <table class="table table-striped " cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><?= lang('subject') ?></th>
                            <th><?= lang('end_date') ?></th>
                            <th><?= lang('responsible') ?></th>
                            <th class=" col-options no-sort"><?= lang('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $all_meetings_info = get_result('tbl_mettings', array('leads_id' => $leads_details->leads_id));

                        if (!empty($all_meetings_info)) :
                            foreach ($all_meetings_info as $v_mettings) :
                                $user = $this->items_model->check_by(array('user_id' => $v_mettings->user_id), 'tbl_users');
                        ?>
                                <tr id="leads_meetings_<?= $leads_details->leads_id ?>">
                                    <td>
                                        <a data-toggle="modal" data-target="#myModal" href="<?= base_url('admin/opportunities/meeting_details/' . $v_mettings->mettings_id) ?>"><?= $v_mettings->meeting_subject ?></a>
                                    </td>
                                    <td><?= strftime(config_item('date_format'), ($v_mettings->end_date)) . '<span style="color:#3c8dbc"> at </span>' . display_time($v_mettings->end_date, true) ?>
                                    </td>
                                    <td><?= fullname($v_mettings->user_id) ?></td>
                                    <td>
                                        <a href="<?= base_url('admin/leads/leads_details/' . $v_mettings->mettings_id) ?>" class="btn btn-xs btn-info" data-placement="top" data-toggle="modal" data-target="#myModal">
                                            <i class="fa fa-list "></i></a>
                                        <?= btn_edit('admin/leads/leads_details/' . $leads_details->leads_id . '/mettings/' . $v_mettings->mettings_id) ?>
                                        <?php echo ajax_anchor(base_url("admin/leads/delete_leads_mettings/" . $leads_details->leads_id . '/' . $v_mettings->mettings_id), "<i class='btn btn-danger btn-xs fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table-meeting-" . $v_mettings->mettings_id)); ?>
                                    </td>
                                </tr>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane <?= $sub_metting == 2 ? 'active' : ''; ?>" id="new_metting">
            <form role="form" enctype="multipart/form-data" id="form" action="<?php echo base_url(); ?>admin/leads/saved_metting/<?= $leads_details->leads_id ?>/<?php
                                                                                                                                                                    if (!empty($mettings_info)) {
                                                                                                                                                                        echo $mettings_info->mettings_id;
                                                                                                                                                                    }
                                                                                                                                                                    ?>" method="post" class="form-horizontal  ">
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('metting_subject') ?>
                        <span class="text-danger"> *</span> </label>
                    <div class="col-lg-9">
                        <input type="text" required="" name="meeting_subject" class="form-control" value="<?php
                                                                                                            if (!empty($mettings_info->meeting_subject)) {
                                                                                                                echo $mettings_info->meeting_subject;
                                                                                                            }
                                                                                                            ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('start_date') ?><span class="text-danger"> *</span></label>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <input type="text" required="" name="start_date" class="form-control datepicker" value="<?php
                                                                                                                    if (!empty($mettings_info->start_date)) {
                                                                                                                        echo date('Y-m-d', strftime($mettings_info->start_date));
                                                                                                                    } else {
                                                                                                                        echo date('Y-m-d');
                                                                                                                    }
                                                                                                                    ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                    <label class="col-lg-2 control-label"><?= lang('start_time') ?><span class="text-danger"> *</span></label>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <input type="text" required="" name="start_time" class="form-control timepicker" value="<?php
                                                                                                                    if (!empty($mettings_info->start_date)) {
                                                                                                                        echo display_time($mettings_info->start_date, true);
                                                                                                                    }
                                                                                                                    ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-clock-o"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('end_date') ?><span class="text-danger"> *</span></label>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <input type="text" required="" name="end_date" class="form-control datepicker" value="<?php
                                                                                                                    if (!empty($mettings_info->end_date)) {
                                                                                                                        echo date('Y-m-d', strftime($mettings_info->end_date));
                                                                                                                    } else {
                                                                                                                        echo date('Y-m-d');
                                                                                                                    }
                                                                                                                    ?>" data-date-format="<?= config_item('date_picker_format'); ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-calendar"></i></a>
                            </div>
                        </div>
                    </div>
                    <label class="col-lg-2 control-label"><?= lang('end_time') ?><span class="text-danger"> *</span></label>
                    <div class="col-lg-3">
                        <div class="input-group">
                            <input type="text" required="" name="end_time" class="form-control timepicker" value="<?php
                                                                                                                    if (!empty($mettings_info->end_date)) {
                                                                                                                        echo display_time($mettings_info->end_date, true);
                                                                                                                    }
                                                                                                                    ?>">
                            <div class="input-group-addon">
                                <a href="#"><i class="fa fa-clock-o"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('attendess') ?><span class="text-danger"> *</span></label>
                    <div class="col-lg-5">
                        <select multiple="multiple" name="attendees[]" style="width: 100%" class="select_multi" required="">
                            <option value=""><?= lang('select') . lang('attendess') ?></option>
                            <?php
                            $all_user_attendees = $this->db->get('tbl_users')->result();
                            if (!empty($all_user_attendees)) {
                                foreach ($all_user_attendees as $v_user_attendees) {
                            ?>
                                    <option value="<?= $v_user_attendees->user_id ?>" <?php
                                                                                        if (!empty($mettings_info->attendees)) {
                                                                                            $user_id = unserialize($mettings_info->attendees);
                                                                                            foreach ($user_id['attendees'] as $assding_id) {
                                                                                                echo $v_user_attendees->user_id == $assding_id ? 'selected' : '';
                                                                                            }
                                                                                        }
                                                                                        ?>>
                                        <?= $v_user_attendees->username ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?= lang('responsible') ?><span class="text-danger"> *</span></label>
                    <div class="col-lg-5">
                        <select name="user_id" class="form-control select_box" style="width: 100%" required="">
                            <option value=""><?= lang('admin_staff') ?></option>
                            <?php
                            $responsible_user_info = $this->db->where(array('role_id !=' => '2'))->get('tbl_users')->result();
                            if (!empty($responsible_user_info)) {
                                foreach ($responsible_user_info as $v_responsible_user) {
                            ?>
                                    <option value="<?= $v_responsible_user->user_id ?>" <?php
                                                                                        if (!empty($mettings_info) && $mettings_info->user_id == $v_responsible_user->user_id) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>>
                                        <?= $v_responsible_user->username ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                </div>
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('location') ?><span class="text-danger"> *</span> </label>
                    <div class="col-lg-5">
                        <input type="text" required="" name="location" class="form-control" value="<?php
                                                                                                    if (!empty($mettings_info->location)) {
                                                                                                        echo $mettings_info->location;
                                                                                                    }
                                                                                                    ?>">
                    </div>
                </div>
                <div class="form-group terms">
                    <label class="col-lg-3 control-label"><?= lang('description') ?> </label>
                    <div class="col-lg-8">
                        <textarea name="description" class="form-control"><?php
                                                                            if (!empty($mettings_info)) {
                                                                                echo $mettings_info->description;
                                                                            }
                                                                            ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"></label>
                    <div class="col-lg-5">
                        <button type="submit" class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>