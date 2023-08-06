<?php
$edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
?>
<div class="box" style="border: none; " data-collapsed="0">
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 'credit_note' ? 'active' : ''; ?>"><a href="#manage_credit_note" data-toggle="tab"><?= lang('credit_note') ?></a>
            </li>
            <?php if (!empty($edited)) { ?>
                <li class=""><a href="<?= base_url() ?>admin/credit_note/createcreditnote/project/<?= $project_details->project_id ?>"><?= lang('new') . ' ' . lang('credit_note') ?></a>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->
            <div class="tab-pane <?= $active == 'credit_note' ? 'active' : ''; ?>" id="manage_credit_note">
                <div class="table-responsive">
                    <table id="table-credit_note" class="table table-striped ">
                        <thead>
                            <tr>
                                <th><?= lang('credit_note') ?> #</th>
                                <th><?= lang('credit_note') . ' ' . lang('date') ?></th>
                                <th><?= lang('client_name') ?></th>
                                <th><?= lang('status') ?></th>
                                <th><?= lang('amount') ?></th>
                                <th><?= lang('remaining') . ' ' . lang('amount') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $all_credit_note_info = get_result('tbl_credit_note', array('project_id' => $project_details->project_id));

                            foreach ($all_credit_note_info as $v_credit_note) {
                                if ($v_credit_note->status == 'refund') {
                                    $label = "info";
                                } elseif ($v_credit_note->status == 'open') {
                                    $label = "success";
                                } else {
                                    $label = "danger";
                                }
                            ?>
                                <tr>
                                    <td>
                                        <a class="text-info" href="<?= base_url() ?>admin/credit_note/index/credit_note_details/<?= $v_credit_note->credit_note_id ?>"><?= $v_credit_note->reference_no ?></a>
                                    </td>
                                    <td><?= display_date($v_credit_note->credit_note_date) ?></td>
                                    <td><?= client_name($v_credit_note->client_id) ?></td>
                                    <td><span class="label label-<?= $label ?>"><?= lang($v_credit_note->status) ?></span>
                                    </td>
                                    <td><?= display_money($this->credit_note_model->credit_note_calculation('total', $v_credit_note->credit_note_id), client_currency($v_credit_note->client_id)) ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>