<section class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('bugs') ?>
            <?php
            $bugs_created = can_action('58', 'created');
            $bugs_edited = can_action('58', 'edited');
            if (!empty($bugs_created) || !empty($bugs_edited)) {
                ?>
                <a href="<?= base_url() ?>admin/bugs/create/<?= $client_details->primary_contact ?>"
                   class="btn-sm pull-right"><?= lang('new_bugs') ?></a>
            <?php } ?>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped " cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= lang('bug_title') ?></th>
                    <th><?= lang('status') ?></th>
                    <th><?= lang('priority') ?></th>
                    <?php if ($this->session->userdata('user_type') == '1') { ?>
                        <th><?= lang('reporter') ?></th>
                    <?php } ?>
                    <th><?= lang('assigned_to') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_bug_info = $this->client_model->getClientData('tbl_bug', $client_details->client_id);
                if (!empty($all_bug_info)) {
                    foreach ($all_bug_info as $v_bugs) {
                        $profile = $this->db->where(array('user_id' => $v_bugs->reporter))->get('tbl_account_details')->row();
                        if (!empty($profile->company) && $profile->company == $client_details->client_id) {
                            $total_bugs += count($v_bugs->bug_id);
                            $reporter = $this->db->where('user_id', $v_bugs->reporter)->get('tbl_users')->row();
                            if ($reporter->role_id == '1') {
                                $badge = 'danger';
                            } elseif ($reporter->role_id == '2') {
                                $badge = 'info';
                            } else {
                                $badge = 'primary';
                            }
                            
                            if ($v_bugs->bug_status == 'unconfirmed') {
                                $label = 'warning';
                            } elseif ($v_bugs->bug_status == 'confirmed') {
                                $label = 'info';
                            } elseif ($v_bugs->bug_status == 'in_progress') {
                                $label = 'primary';
                            } elseif ($v_bugs->bug_status == 'resolved') {
                                $label = 'purple';
                            } else {
                                $label = 'success';
                            }
                            ?>
                            <tr>
                                <td><a class="text-info" style="<?php
                                    if ($v_bugs->bug_status == 'resolve') {
                                        echo 'text-decoration: line-through;';
                                    }
                                    ?>"
                                       href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bugs->bug_id ?>"><?php echo $v_bugs->bug_title; ?></a>
                                </td>
                                </td>
                                <td>
                                    <span class="label label-<?= $label ?>"><?= lang("$v_bugs->bug_status") ?></span>
                                </td>
                                <td>
                                    <?php
                                    if ($v_bugs->priority == 'High') {
                                        $plabel = 'danger';
                                    } elseif ($v_bugs->priority == 'Medium') {
                                        $plabel = 'info';
                                    } else {
                                        $plabel = 'primary';
                                    }
                                    ?>
                                    <span class="badge btn-<?= $plabel ?>"><?= ucfirst($v_bugs->priority) ?></span>
                                </td>
                                <td>
                                    <span class="badge btn-<?= $badge ?> "><?= $reporter->username ?></span>
                                </td>
                                <td>
                                    <?php
                                    
                                    if ($v_bugs->permission != 'all') {
                                        $get_permission = json_decode($v_bugs->permission);
                                        
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
                                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                                        <span data-placement="top" data-toggle="tooltip"
                                              title="<?= lang('add_more') ?>">
                                                                <a data-toggle="modal" data-target="#myModal"
                                                                   href="<?= base_url() ?>admin/bugs/update_users/<?= $v_bugs->bug_id ?>"
                                                                   class="text-default ml"><i
                                                                            class="fa fa-plus"></i></a>
                                                            </span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>