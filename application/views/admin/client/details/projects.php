<section class="panel panel-custom">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('project') ?>
            <?php
            $pro_created = can_action('57', 'created');
            $pro_edited = can_action('57', 'edited');
            if (!empty($pro_created) || !empty($pro_edited)) {
                ?>
                <a href="<?= base_url() ?>admin/projects/create/client_project/<?= $client_details->client_id ?>"
                   class="btn-sm pull-right"><?= lang('new_project') ?></a>
            <?php } ?>
        </div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped " cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th><?= lang('project_name') ?></th>
                    <th><?= lang('end_date') ?></th>
                    <th><?= lang('status') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_project = get_order_by('tbl_project', array('client_id' => $client_details->client_id), 'project_id');
                if (!empty($all_project)) : foreach ($all_project as $v_project) :
                    $progress = $this->items_model->get_project_progress($v_project->project_id);
                    ?>
                    <tr>
                        <td><a class="text-info"
                               href="<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>"><?= $v_project->project_name ?></a>
                            <?php if (strtotime(date('Y-m-d')) > strtotime($v_project->end_date) && $progress < 100) { ?>
                                <span class="label label-danger pull-right"><?= lang('overdue') ?></span>
                            <?php } ?>
                            
                            <div class="progress progress-xs progress-striped active">
                                <div class="progress-bar progress-bar-<?php echo ($progress >= 100) ? 'success' : 'primary'; ?>"
                                     data-toggle="tooltip" data-original-title="<?= $progress ?>%"
                                     style="width: <?= $progress; ?>%"></div>
                            </div>
                        
                        </td>
                        <td><?= strftime(config_item('date_format'), strtotime($v_project->end_date)) ?>
                        </td>
                        
                        <td><?php
                            if (!empty($v_project->project_status)) {
                                if ($v_project->project_status == 'completed') {
                                    $status = "<span class='label label-success'>" . lang($v_project->project_status) . "</span>";
                                } elseif ($v_project->project_status == 'in_progress') {
                                    $status = "<span class='label label-primary'>" . lang($v_project->project_status) . "</span>";
                                } elseif ($v_project->project_status == 'cancel') {
                                    $status = "<span class='label label-danger'>" . lang($v_project->project_status) . "</span>";
                                } else {
                                    $status = "<span class='label label-warning'>" . lang($v_project->project_status) . "</span>";
                                }
                                echo $status;
                            }
                            ?> </td>
                    </tr>
                <?php
                endforeach;
                endif;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</section>