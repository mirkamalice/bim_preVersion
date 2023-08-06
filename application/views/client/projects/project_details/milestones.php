<?php

$project_settings = json_decode($project_details->project_settings);
if (!empty($project_settings[1]) && $project_settings[1] == 'show_milestones') {
    $all_milestones_info = get_result('tbl_milestones',array('project_id'=>$id));
    
}
?>
<div class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('milestones') ?>
        </div>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table id="table-milestones" class="table table-striped">
                <thead>
                    <tr>
                        <th><?= lang('milestone_name') ?></th>
                        <th class="col-date"><?= lang('start_date') ?></th>
                        <th class="col-date"><?= lang('due_date') ?></th>
                        <th><?= lang('progress') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($all_milestones_info as $key => $v_milestones) {
                        $progress = $this->items_model->calculate_milestone_progress($v_milestones->milestones_id);
                        $all_milestones_task = $this->db->where('milestones_id', $v_milestones->milestones_id)->get('tbl_task')->result();
                    ?>
                        <tr>
                            <td><a class="text-info" href="#" data-original-title="<?= $v_milestones->description ?>" data-toggle="tooltip" data-placement="top" title=""><?= $v_milestones->milestone_name ?></a></td>
                            <td><?= strftime(config_item('date_format'), strtotime($v_milestones->start_date)) ?></td>
                            <td><?php
                                $due_date = $v_milestones->end_date;
                                $due_time = strtotime($due_date);
                                $current_time = strtotime(date('Y-m-d'));
                                ?>
                                <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                <?php if ($current_time > $due_time && $progress < 100) { ?>
                                    <span class="label label-danger"><?= lang('overdue') ?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <div class="inline ">
                                    <div class="easypiechart text-success" style="margin: 0px;" data-percent="<?= $progress ?>" data-line-width="5" data-track-Color="#f0f0f0" data-bar-color="#<?php
                                                                                                                                                                                                if ($progress >= 100) {
                                                                                                                                                                                                    echo '8ec165';
                                                                                                                                                                                                } else {
                                                                                                                                                                                                    echo 'fb6b5b';
                                                                                                                                                                                                }
                                                                                                                                                                                                ?>" data-rotate="270" data-scale-Color="false" data-size="50" data-animate="2000">
                                        <span class="small text-muted"><?= $progress ?>
                                            %</span>
                                    </div>
                                </div>

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