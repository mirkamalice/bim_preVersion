<?php include_once 'asset/admin-ajax.php';
$created = can_action('72', 'created');
$edited = can_action('72', 'edited');
$deleted = can_action('72', 'deleted');
$office_hours = config_item('office_hours');

?>
<?= message_box('success'); ?>
<?= message_box('error'); ?>
<div class=" mt-lg">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="pending_approval <?= $active == 1 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/leave_management') ?>"><?= lang('pending') . ' ' . lang('approval') ?></a>
            </li>

            <li class=" my_leave <?= $active == 2 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/leave_management/my_leave') ?>"><?= lang('my_leave') ?></a>
            </li>

            <?php if (!empty(admin_head())) { ?>
            <li class="all_leave <?= $active == 3 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/leave_management/all_leave') ?>"><?= lang('all_leave') ?></a>
            </li>
            <?php } ?>
            <li class="<?= $active == 4 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/leave_management/leave_report') ?>"><?= lang('leave_report') ?></a>
            </li>
            <li class="pull-right">
                <a href="<?= base_url() ?>admin/leave_management/apply_leave" class="bg-info" data-toggle="modal"
                    data-placement="top" data-target="#myModal_extra_lg">
                    <i class="fa fa-plus "></i> <?= lang('apply') . ' ' . lang('leave') ?>
                </a>
            </li>
        </ul>

        <div class="tab-content" style="border: 0;padding:0;">
            <div class="tab-pane <?= $active == 3 ? 'active' : '' ?>" style="position: relative;">
                <div class="panel panel-custom">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped pending_approval_" id="DataTables" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('leave_category') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('duration') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php $show_custom_fields = custom_form_table(17, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                        ?>
                                        <th><?= $c_label ?> </th>
                                        <?php }
                                            }
                                        }
                                        ?>
                                        <?php if (!empty(admin_head())) { ?>
                                        <th class="col-sm-2"><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <script type="text/javascript">
                                    $(document).ready(function() {
                                        // list = base_url + "admin/leave_management/pending_approvalList";


                                        list = base_url + "admin/leave_management/all_leaveList";
                                    });
                                    </script>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>