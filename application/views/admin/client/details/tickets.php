<section class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('tickets') ?>
            <?php if (!empty($edited)) { ?>
                <a href="<?= base_url() ?>admin/tickets/create/edit_tickets/<?= $primary_contact ?>"
                   class="btn-sm pull-right"><?= lang('new_ticket') ?></a>
            <?php } ?>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped " cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= lang('subject') ?></th>
                    <th class="col-date"><?= lang('date') ?></th>
                    <?php if ($this->session->userdata('user_type') == '1') { ?>
                        <th><?= lang('reporter') ?></th>
                    <?php } ?>
                    <th><?= lang('status') ?></th>
                    <th><?= lang('action') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_tickets_info = $this->client_model->getClientData('tbl_tickets', $client_details->client_id);
                if (!empty($all_tickets_info)) {
                    foreach ($all_tickets_info as $v_tickets_info) {
                        if ($v_tickets_info->reporter != 0) {
                            $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                            if (!empty($profile_info->company) && $profile_info->company == $client_details->client_id) {
                                if ($v_tickets_info->status == 'open') {
                                    $s_label = 'danger';
                                } elseif ($v_tickets_info->status == 'closed') {
                                    $s_label = 'success';
                                } else {
                                    $s_label = 'default';
                                }
                                ?>
                                <tr>
                                    <td><a class="text-info"
                                           href="<?= base_url() ?>admin/tickets/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
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
                                                         border: 1px solid #aaa;"
                                                         src="<?= base_url() . $profile_info->avatar ?>"
                                                         class="img-circle">
                                                <?php } ?>
                                                
                                                <?=
                                                ($profile_info->fullname)
                                                ?>
                                            </a>
                                        </td>
                                    
                                    <?php } ?>
                                    <?php
                                    if ($v_tickets_info->status == 'in_progress') {
                                        $status = 'In Progress';
                                    } else {
                                        $status = $v_tickets_info->status;
                                    }
                                    ?>
                                    <td><span class="label label-<?= $s_label ?>"><?= ucfirst($status) ?></span>
                                    </td>
                                    <td>
                                        <?= btn_edit('admin/tickets/create/edit_tickets/' . $v_tickets_info->tickets_id) ?>
                                        <?= btn_delete('admin/tickets/delete/delete_tickets/' . $v_tickets_info->tickets_id) ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>