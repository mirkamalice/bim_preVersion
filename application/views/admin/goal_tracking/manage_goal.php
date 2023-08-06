<style type="text/css">
.progress-bar-purple {
    background-color: #564aa3 !important
}
</style>
<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('69', 'created');
$edited = can_action('69', 'edited');
$deleted = can_action('69', 'deleted');
if (!empty($created) || !empty($edited)) {
    $goal_type = $this->db->get('tbl_goal_type')->result();
    $is_department_head = is_department_head();
    if ($this->session->userdata('user_type') == 1 || !empty($is_department_head)) { ?>
<div class="btn-group pull-right btn-with-tooltip-group filtered" data-toggle="tooltip"
    data-title="<?php echo lang('filter_by'); ?>">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa fa-filter" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-left group" style="width:300px;">
        <li class="filter_by"><a href="#"><?php echo lang('all'); ?></a></li>
        <li class="divider"></li>
        <?php if (count(array($goal_type)) > 0) { ?>
        <?php foreach ($goal_type as $v_goal_type) {
                    ?>
        <li class="filter_by" id="<?= $v_goal_type->goal_type_id ?>">
            <a href="#"><?php echo lang($v_goal_type->type_name); ?></a>
        </li>
        <?php }
                    ?>
        <div class="clearfix"></div>
        <?php } ?>
    </ul>
</div>
<?php } ?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/goal_tracking') ?>"><?= lang('goal_tracking') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/goal_tracking/create') ?>"><?= lang('new_goal') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <?php } else { ?>
            <div class="panel panel-custom">
                <header class="panel-heading ">
                    <div class="panel-title"><strong><?= lang('goal_tracking') ?></strong></div>
                </header>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('subject') ?></th>
                                <th><?= lang('type') ?></th>
                                <th><?= lang('achievement') ?></th>
                                <th><?= lang('start_date') ?></th>
                                <th><?= lang('end_date') ?></th>
                                <th class="col-options no-sort"><?= lang('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <script type="text/javascript">
                            $(document).ready(function() {
                                list = base_url + "admin/goal_tracking/goal_trackingList";
                                $('.filtered > .dropdown-toggle').on('click', function() {
                                    if ($('.group').css('display') == 'block') {
                                        $('.group').css('display', 'none');
                                    } else {
                                        $('.group').css('display', 'block')
                                    }
                                });
                                $('.filter_by').on('click', function() {
                                    $('.filter_by').removeClass('active');
                                    $('.group').css('display', 'block');
                                    $(this).addClass('active');
                                    var filter_by = $(this).attr('id');
                                    if (filter_by) {
                                        filter_by = filter_by;
                                    } else {
                                        filter_by = '';
                                    }
                                    table_url(base_url + "admin/goal_tracking/goal_trackingList/" +
                                        filter_by);
                                });
                            });
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- end -->