<section class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <strong>
                <?= lang('estimates') ?>
            </strong>
            <div class="pull-right">
                <?php
                $es_created = can_action('14', 'created');
                $es_edited = can_action('14', 'edited');
                if (!empty($es_created) || !empty($es_edited)) {
                    ?>
                    <a href="<?= base_url() ?>admin/estimates/create/edit_estimates/c_<?= $client_details->client_id ?>"
                       class="btn btn-purple btn-xs"><?= lang('new_estimate') ?></a>
                <?php } ?>
                <a data-toggle="modal" data-target="#myModal"
                   href="<?= base_url() ?>admin/invoice/zipped/estimate/<?= $client_details->client_id ?>"
                   class="btn btn-success btn-xs"><?= lang('zip_estimate') ?></a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <table class="table table-striped " cellspacing="0" id="datatable_action" width="100%">
            <thead>
            <tr>
                <th><?= lang('reference_no') ?></th>
                <th><?= lang('date_issued') ?></th>
                <th><?= lang('due_date') ?> </th>
                <th class="col-currency"><?= lang('amount') ?> </th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total_estimate = 0;
            $client_estimates = get_result('tbl_estimates', array('client_id' => $client_details->client_id));
            if (!empty($client_estimates)) {
                foreach ($client_estimates as $key => $estimate) {
                    $total_estimate += $this->estimates_model->estimate_calculation('estimate_amount', $estimate->estimates_id);
                    ?>
                    <tr>
                        <td><a class="text-info"
                               href="<?= base_url() ?>admin/estimates/create/estimates_details//<?= $estimate->estimates_id ?>"><?= $estimate->reference_no ?></a>
                        </td>
                        <td><?= strftime(config_item('date_format'), strtotime($estimate->date_saved)); ?>
                        </td>
                        <td><?= strftime(config_item('date_format'), strtotime($estimate->due_date)); ?>
                        </td>
                        <td>
                            <?php echo display_money($this->estimates_model->estimate_calculation('estimate_amount', $estimate->estimates_id), client_currency($client_details->client_id)); ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <strong><?= lang('estimate') . ' ' . lang('amount') ?>:</strong> <strong class="label label-success">
            <?= display_money($total_estimate, client_currency($client_details->client_id)); ?>
        </strong>
    </div>
</section>