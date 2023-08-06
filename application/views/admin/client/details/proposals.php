<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('all_proposals') ?>
            
            <div class="pull-right">
                <?php
                $prop_created = can_action('140', 'created');
                $prop_edited = can_action('140', 'edited');
                if (!empty($prop_created) || !empty($prop_edited)) {
                    ?>
                    <a href="<?= base_url() ?>admin/proposals/index/client/<?= $client_details->client_id ?>"
                       class="btn btn-purple btn-xs"><?= lang('create_proposal') ?></a>
                <?php } ?>
                <a data-toggle="modal" data-target="#myModal"
                   href="<?= base_url() ?>admin/invoice/zipped/proposal/<?= $client_details->client_id ?>"
                   class="btn btn-success btn-xs"><?= lang('zip_proposal') ?></a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped " id="datatable_action" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= lang('proposal') ?> #</th>
                    <th><?= lang('proposal_date') ?></th>
                    <th><?= lang('expire_date') ?></th>
                    <th><?= lang('status') ?></th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th class="col-sm-3 hidden_print"><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_proposals_info = get_order_by('tbl_proposals', array('module' => 'client', 'module_id' => $client_details->client_id), 'proposals_id');
                
                if (!empty($all_proposals_info)) {
                    foreach ($all_proposals_info as $v_proposals) {
                        $can_edit = $this->client_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $v_proposals->proposals_id));
                        $can_delete = $this->client_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $v_proposals->proposals_id));
                        
                        if ($v_proposals->status == 'pending') {
                            $label = "info";
                        } elseif ($v_proposals->status == 'accepted') {
                            $label = "success";
                        } else {
                            $label = "danger";
                        }
                        ?>
                        <tr>
                            <td>
                                <a class="text-info"
                                   href="<?= base_url() ?>admin/proposals/index/proposals_details/<?= $v_proposals->proposals_id ?>"><?= $v_proposals->reference_no ?></a>
                                <?php if ($v_proposals->convert == 'Yes') {
                                    if ($v_proposals->convert_module == 'invoice') {
                                        $c_url = base_url() . 'admin/invoice/manage_invoice/invoice_details/' . $v_proposals->convert_module_id;
                                        $text = lang('invoiced');
                                    } else {
                                        $text = lang('estimated');
                                        $c_url = base_url() . 'admin/estimates/create/estimates_details/' . $v_proposals->convert_module_id;
                                    }
                                    if (!empty($c_url)) { ?>
                                        <p class="text-sm m0 p0">
                                            <a class="text-success" href="<?= $c_url ?>">
                                                <?= $text ?>
                                            </a>
                                        </p>
                                    <?php }
                                } ?>
                            </td>
                            <td><?= strftime(config_item('date_format'), strtotime($v_proposals->proposal_date)) ?>
                            </td>
                            <td><?= strftime(config_item('date_format'), strtotime($v_proposals->due_date)) ?>
                                <?php
                                if (strtotime($v_proposals->due_date) < strtotime(date('Y-m-d')) && $v_proposals->status == 'pending' || strtotime($v_proposals->due_date) < strtotime(date('Y-m-d')) && $v_proposals->status == ('draft')) { ?>
                                    <span class="label label-danger "><?= lang('expired') ?></span>
                                <?php }
                                ?>
                            </td>
                            <?php ?>
                            <td><span class="label label-<?= $label ?>"><?= lang($v_proposals->status) ?></span>
                            </td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                        <?= btn_edit('admin/proposals/index/edit_proposals/' . $v_proposals->proposals_id) ?>
                                    <?php }
                                    if (!empty($can_delete) && !empty($deleted)) {
                                        ?>
                                        <?= btn_delete('admin/proposals/delete/delete_proposals/' . $v_proposals->proposals_id) ?>
                                    <?php } ?>
                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-default dropdown-toggle"
                                                    data-toggle="dropdown">
                                                <?= lang('change_status') ?>
                                                <span class="caret"></span></button>
                                            <ul class="dropdown-menu animated zoomIn">
                                                <li>
                                                    <a href="<?= base_url() ?>admin/proposals/index/email_proposals/<?= $v_proposals->proposals_id ?>"><?= lang('send_email') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/proposals/index/proposals_details/<?= $v_proposals->proposals_id ?>"><?= lang('view_details') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/proposals/index/proposals_history/<?= $v_proposals->proposals_id ?>"><?= lang('history') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/proposals/change_status/declined/<?= $v_proposals->proposals_id ?>"><?= lang('declined') ?></a>
                                                </li>
                                                <li>
                                                    <a href="<?= base_url() ?>admin/proposals/change_status/accepted/<?= $v_proposals->proposals_id ?>"><?= lang('accepted') ?></a>
                                                </li>
                                            
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>