<?php 
$edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
if (!empty($project_details->client_id)) {
    $currency = $this->items_model->client_currency_sambol($project_details->client_id);
} else {
    $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
}
?>

<div class="box" style="border: none; " data-collapsed="0">
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 'estimates' ? 'active' : ''; ?>"><a href="#manage_estimates" data-toggle="tab"><?= lang('estimates') ?></a>
            </li>
            <?php if (!empty($edited)) { ?>
                <li class=""><a href="<?= base_url() ?>admin/estimates/create/project/<?= $project_details->project_id ?>"><?= lang('new_estimate') ?></a>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->
            <div class="tab-pane <?= $active == 'estimates' ? 'active' : ''; ?>" id="manage_estimates">
                <div class="table-responsive">
                    <table id="table-estimates" class="table table-striped ">
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
                                        <a class="text-info" href="<?= base_url() ?>admin/estimates/create/estimates_details/<?= $v_estimates->estimates_id ?>"><?= $v_estimates->reference_no ?></a>
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
    </div>
</div>