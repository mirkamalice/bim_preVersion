<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$open_tasks = null;
$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $project_details->project_id, 'module_name' => 'project');
$check_existing = $this->items_model->check_by($where, 'tbl_pinaction');
if (!empty($check_existing)) {
    $url = 'remove_todo/' . $check_existing->pinaction_id;
    $btn = 'danger';
    $title = lang('remove_todo');
} else {
    $url = 'add_todo_list/project/' . $project_details->project_id;
    $btn = 'warning';
    $title = lang('add_todo_list');
}

$this->load->helper('date');
$totalDays = round((human_to_unix($project_details->end_date . ' 00:00') - human_to_unix($project_details->start_date . ' 00:00')) / 3600 / 24);
$TotalGone = $totalDays;
$tprogress = 100;
if (human_to_unix($project_details->start_date . ' 00:00') < time() && human_to_unix($project_details->end_date . ' 00:00') > time()) {
    $TotalGone = round((human_to_unix($project_details->end_date . ' 00:00') - time()) / 3600 / 24);
    $tprogress = $TotalGone / $totalDays * 100;
}
if (human_to_unix($project_details->end_date . ' 00:00') < time()) {
    $TotalGone = 0;
    $tprogress = 0;
}
if (strtotime(date('Y-m-d')) > strtotime($project_details->end_date . '00:00')) {
    $lang = lang('days_gone');
} else {
    $lang = lang('days_left');
}

$edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
$can_edit = $this->items_model->can_action('tbl_project', 'edit', array('project_id' => $project_details->project_id));
?>
    <div class="row">
        <div class="col-sm-2">
            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                <span data-placement="top" data-toggle="tooltip" title="<?= lang('generate_invoice') ?>">
                <a data-toggle="modal" data-target="#myModal"
                   href="<?= base_url() ?>admin/projects/invoice/<?= $project_details->project_id ?>"
                   class="mr-lg btn btn-info"><i class="fa fa-money"></i> <?= lang('invoice') ?>
                </a>
            </span>
            
            <?php } ?>
            <?php if (timer_status('projects', $project_details->project_id, 'on')) { ?>
                <a data-toggle="tooltip" data-placement="top" title="<?= lang('stop_timer') ?>" class="btn btn-danger "
                   href="<?= base_url() ?>admin/projects/tasks_timer/off/<?= $project_details->project_id ?>"><i
                            class="fa fa-clock-o fa-spin"></i></a>
            <?php } else {
                ?>
                <a data-toggle="tooltip" data-placement="top" title="<?= lang('start_timer') ?>" class="btn btn-success"
                   href="<?= base_url() ?>admin/projects/tasks_timer/on/<?= $project_details->project_id ?>"><i
                            class="fa fa-clock-o"></i></a>
            <?php }
            ?>
            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                <a data-toggle="modal" data-target="#myModal" title="<?= lang('clone_project') ?>"
                   href="<?= base_url() ?>admin/projects/clone_project/<?= $project_details->project_id ?>"
                   class="btn btn-purple pull-right"><i class="fa fa-copy"></i></a>
            <?php } ?>
            
            <a data-toggle="tooltip" data-placement="top" title="<?= $title ?>"
               href="<?= base_url() ?>admin/projects/<?= $url ?>" class="btn btn-<?= $btn ?>"><i
                        class="fa fa-thumb-tack"></i></a>
        
        
        </div>
        <div class="col-sm-10">
            <a data-toggle="tooltip" data-placement="top" title="<?= lang('export_report') ?>"
               href="<?= base_url() ?>admin/projects/export_project/<?= $project_details->project_id ?>"
               class="btn btn-danger"><i class="fa fa-file-pdf-o"></i></a>
            <!-- Tabs within a box -->
            <div class="tab-content mt" style="border: 0;padding:0;">
            
            </div>
        </div>
    </div>
<?php
$this->load->view('admin/common/tabs');
?>