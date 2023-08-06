<section class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('transactions') ?>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped " cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?= lang('date') ?></th>
                <th><?= lang('account') ?></th>
                <th><?= lang('type') ?> </th>
                <th><?= lang('amount') ?> </th>
                <th><?= lang('action') ?> </th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total_income = 0;
            $total_expense = 0;
            $client_transactions = get_order_by('tbl_transactions', array('paid_by' => $client_details->client_id), 'transactions_id');
            if (!empty($client_transactions)) : foreach ($client_transactions as $v_transactions) :
                $account_info = $this->client_model->check_by(array('account_id' => $v_transactions->account_id), 'tbl_accounts');
                ?>
                <tr>
                    <td><?= strftime(config_item('date_format'), strtotime($v_transactions->date)); ?>
                    </td>
                    <td><?= $account_info->account_name ?></td>
                    <td><?= $v_transactions->type ?></td>
                    <td><?= display_money($v_transactions->amount, default_currency()); ?></td>
                    <td>
                        <?php
                        
                        if ($v_transactions->type == 'Income') {
                            $total_income += $v_transactions->amount;
                            ?>
                            <?= btn_edit('admin/transactions/create_deposit/' . $v_transactions->transactions_id) ?>
                            <?= btn_delete('admin/transactions/delete_deposit/' . $v_transactions->transactions_id) ?>
                            <?php
                        } else {
                            $total_expense += $v_transactions->amount;
                            ?>
                            <?= btn_edit('admin/transactions/create_expense/' . $v_transactions->transactions_id) ?>
                            <?= btn_delete('admin/transactions/delete_expense/' . $v_transactions->transactions_id) ?>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            endforeach;
                ?>
            
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <small><strong><?= lang('total_income') ?>:</strong><strong
                    class="label label-success"><?= display_money($total_income, default_currency()); ?></strong>
        </small>
        <small class="text-danger pull-right">
            <strong><?= lang('total_expense') ?>:</strong>
            <strong class="label label-danger"><?= display_money($total_expense, default_currency()); ?></strong>
        </small>
    </div>
</section>