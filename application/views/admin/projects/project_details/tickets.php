<?php
    $edited = can_action('57', 'edited');
    $deleted = can_action('57', 'deleted');
?>
<div class="box" style="border: none; " data-collapsed="0">
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 'tickets' ? 'active' : ''; ?>"><a href="#manage_tickets" data-toggle="tab"><?= lang('tickets') ?></a>
            </li>
            <?php if (!empty($edited)) { ?>
                <li class=""><a href="<?= base_url() ?>admin/tickets/create/project_tickets/0/<?= $project_details->project_id ?>"><?= lang('new_ticket') ?></a>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->
            <div class="tab-pane <?= $active == 'tickets' ? 'active' : ''; ?>" id="manage_tickets">
                <div class="table-responsive">
                    <table id="table-tickets" class="table table-striped ">
                        <thead>
                            <tr>
                                <th><?= lang('ticket_code') ?></th>
                                <th><?= lang('subject') ?></th>
                                <th class="col-date"><?= lang('date') ?></th>
                                <?php if ($this->session->userdata('user_type') == '1') { ?>
                                    <th><?= lang('reporter') ?></th>
                                <?php } ?>
                                <th><?= lang('department') ?></th>
                                <th><?= lang('status') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $all_tickets_info = get_result('tbl_tickets', array('project_id' => $project_details->project_id));

                            if (!empty($all_tickets_info)) {
                                foreach ($all_tickets_info as $v_tickets_info) {
                                    $can_edit = $this->items_model->can_action('tbl_tickets', 'edit', array('tickets_id' => $v_tickets_info->tickets_id));
                                    $can_delete = $this->items_model->can_action('tbl_tickets', 'delete', array('tickets_id' => $v_tickets_info->tickets_id));
                                    if ($v_tickets_info->status == 'open') {
                                        $s_label = 'danger';
                                    } elseif ($v_tickets_info->status == 'closed') {
                                        $s_label = 'success';
                                    } else {
                                        $s_label = 'default';
                                    }
                                    $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                                    $dept_info = $this->db->where(array('departments_id' => $v_tickets_info->departments_id))->get('tbl_departments')->row();
                                    if (!empty($dept_info)) {
                                        $dept_name = $dept_info->deptname;
                                    } else {
                                        $dept_name = '-';
                                    } ?>

                                    <tr>

                                        <td>
                                            <span class="label label-success"><?= $v_tickets_info->ticket_code ?></span>
                                        </td>
                                        <td><a class="text-info" href="<?= base_url() ?>admin/tickets/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
                                        </td>
                                        <td><?= strftime(config_item('date_format'), strtotime($v_tickets_info->created)); ?>
                                        </td>
                                        <?php if ($this->session->userdata('user_type') == '1') { ?>

                                            <td>
                                                <a class="pull-left recect_task  ">
                                                    <?php if (!empty($profile_info)) {
                                                    ?>
                                                        <img style="width: 30px;margin-left: 18px;
                                                         height: 29px;
                                                         border: 1px solid #aaa;" src="<?= base_url() . $profile_info->avatar ?>" class="img-circle">


                                                        <?=
                                                        ($profile_info->fullname)
                                                        ?>
                                                    <?php } else {
                                                        echo '-';
                                                    } ?>
                                                </a>
                                            </td>

                                        <?php } ?>
                                        <td><?= $dept_name ?></td>
                                        <?php
                                        if ($v_tickets_info->status == 'in_progress') {
                                            $status = 'In Progress';
                                        } else {
                                            $status = $v_tickets_info->status;
                                        }
                                        ?>
                                        <td><span class="label label-<?= $s_label ?>"><?= ucfirst($status) ?></span>
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
            <!-- End Tasks Management-->

        </div>
    </div>
</div>