<?php

$edited = can_action('24', 'edited');
$deleted = can_action('24', 'deleted');
?>

<div class="panel panel-custom">
    <div class="panel-heading">
        <h4 class="panel-title"><?= lang('bank_information') ?>
            <?php if (!empty($edited)) { ?>
                <div class="pull-right hidden-print">
                    <span data-placement="top" data-toggle="tooltip" title="<?= lang('new_bank') ?>">
                        <a data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/user/new_bank/<?= $profile_info->user_id ?>" class="text-default text-sm ml"><?= lang('update') ?></a>
                    </span>
                </div>
            <?php } ?>
        </h4>
    </div>

    <div class="panel-body form-horizontal table-responsive">
        <table class="table table-striped " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><?= lang('bank') ?></th>
                    <th><?= lang('name_of_account') ?></th>
                    <th><?= lang('routing_number') ?></th>
                    <th><?= lang('account_number') ?></th>
                    <th class="hidden-print"><?= lang('action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $all_bank_info = get_result('tbl_employee_bank', array('user_id' => $profile_info->user_id));

                if (!empty($all_bank_info)) {
                    foreach ($all_bank_info as $v_bank_info) { ?>
                        <tr>
                            <td><?= $v_bank_info->bank_name ?></td>
                            <td><?= $v_bank_info->account_name ?></td>
                            <td><?= $v_bank_info->routing_number ?></td>
                            <td><?= $v_bank_info->account_number ?></td>
                            <td class="hidden-print">
                                <?php if (!empty($edited)) { ?>
                                    <?= btn_edit_modal('admin/user/new_bank/' . $v_bank_info->user_id . '/' . $v_bank_info->employee_bank_id) ?>
                                <?php }
                                if (!empty($deleted)) {
                                ?>
                                    <?= btn_delete('admin/user/delete_user_bank/' . $v_bank_info->user_id . '/' . $v_bank_info->employee_bank_id) ?>
                                <?php } ?>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </div>
</div>