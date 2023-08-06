<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('estimates') ?></h3>
    </div>
    <div class="panel-body">

        <div class="table-responsive">
            <table id="table-milestones" class="table table-striped ">
                <thead>
                    <tr>
                        <th><?= lang('estimate') ?></th>
                        <th><?= lang('due_date') ?></th>
                        <th><?= lang('amount') ?></th>
                        <th><?= lang('status') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $all_estimates_info = get_result('tbl_estimates', array('project_id' => $project_details->project_id));

                    foreach ($all_estimates_info as $v_estimates) {
                        if ($v_estimates->status == 'Pending') {
                            $label = "info";
                        } elseif ($v_estimates->status == 'Accepted') {
                            $label = "success";
                        } else {
                            $label = "danger";
                        }
                    ?>
                        <tr>
                            <td>
                                <a class="text-info" href="<?= base_url() ?>client/estimates/index/estimates_details/<?= $v_estimates->estimates_id ?>"><?= $v_estimates->reference_no ?></a>
                            </td>
                            <td><?= strftime(config_item('date_format'), strtotime($v_estimates->due_date)) ?>
                                <?php
                                if (strtotime($v_estimates->due_date) < strtotime(date('Y-m-d')) && $v_estimates->status == 'Pending') { ?>
                                    <span class="label label-danger "><?= lang('expired') ?></span>
                                <?php }
                                ?>
                            </td>
                            <td>
                                <?= display_money($this->estimates_model->estimate_calculation('estimate_amount', $v_estimates->estimates_id), $currency->symbol); ?>
                            </td>
                            <td><span class="label label-<?= $label ?>"><?= lang(strtolower($v_estimates->status)) ?></span>
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