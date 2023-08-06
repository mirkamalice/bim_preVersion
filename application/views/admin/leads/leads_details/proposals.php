<?php
$can_edit = $this->items_model->can_action('tbl_leads', 'edit', array('leads_id' => $leads_details->leads_id));
$can_delete = $this->items_model->can_action('tbl_leads', 'delete', array('leads_id' => $leads_details->leads_id));

$edited = can_action('55', 'edited');
$deleted = can_action('55', 'deleted');

?>

<div class="nav-tabs-custom ">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 'proposals' ? 'active' : ''; ?>"><a href="#manageProposals" data-toggle="tab"><?= lang('all_proposals') ?></a>
        </li>
        <?php if (!empty($edited)) { ?>
            <li class=""><a href="<?= base_url() ?>admin/proposals/createproposal/<?= $leads_details->leads_id ?>"><?= lang('create_proposal') ?></a>
            </li>
        <?php } ?>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 'proposals' ? 'active' : ''; ?>" id="manageProposals" style="position: relative;">

            <div class="box" style="border: none; padding-top: 15px;" data-collapsed="0">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped " cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?= lang('proposal') ?> #</th>
                                    <th><?= lang('proposal_date') ?></th>
                                    <th><?= lang('expire_date') ?></th>
                                    <th><?= lang('status') ?></th>
                                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                                        <th class="hidden-print"><?= lang('action') ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $all_proposals_info = get_result('tbl_proposals', array('module_id' => $leads_details->leads_id));
                                if (!empty($all_proposals_info)) {
                                    foreach ($all_proposals_info as $v_proposals) {
                                        $can_edit = $this->items_model->can_action('tbl_proposals', 'edit', array('proposals_id' => $v_proposals->proposals_id));
                                        $can_delete = $this->items_model->can_action('tbl_proposals', 'delete', array('proposals_id' => $v_proposals->proposals_id));

                                        if ($v_proposals->status == 'pending') {
                                            $label = "info";
                                        } elseif ($v_proposals->status == 'accepted') {
                                            $label = "success";
                                        } else {
                                            $label = "danger";
                                        }
                                ?>
                                        <tr id="leads_proposals_<?= $v_proposals->proposals_id ?>">
                                            <td>
                                                <a class="text-info" href="<?= base_url() ?>admin/proposals/index/proposals_details/<?= $v_proposals->proposals_id ?>"><?= $v_proposals->reference_no ?></a>
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
                                                        <?= btn_edit('admin/proposals/createproposal/' . $v_proposals->proposals_id) ?>
                                                    <?php }
                                                    if (!empty($can_delete) && !empty($deleted)) {
                                                    ?>
                                                        <?php echo ajax_anchor(base_url("admin/proposals/delete/delete_proposals/" . $v_proposals->proposals_id), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#leads_proposals_" . $v_proposals->proposals_id)); ?>
                                                    <?php } ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <div class="btn-group">
                                                            <button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
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
        </div>
    </div>
</div>