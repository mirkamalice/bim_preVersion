<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action_by_label('workplace', 'created');
$edited = can_action_by_label('workplace', 'edited');
$deleted = can_action_by_label('workplace', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="<?= base_url('admin/attendance/workplace') ?>"><?= lang('manage_workplace') ?></a></li>
            <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="<?= base_url('admin/attendance/new_workplace') ?>"><?= lang('new_workplace') ?></a></li>
        </ul>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->
            <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <?php } else { ?>
                <div class="panel panel-custom">
                    <header class="panel-heading ">
                        <div class="panel-title"><strong><?= lang('manage_workplace') ?></strong></div>
                    </header>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped DataTables " id="DataTables" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('employee') ?></th>
                                <th><?= lang('location_name') ?></th>
                                <th><?= lang('workplace_details') ?></th>
                                <th><?= lang('workplace_location') ?></th>
                                <th><?= lang('allowed_radius') ?></th>
                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                    <th><?= lang('action') ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <script type="text/javascript">
                            list = base_url + "admin/attendance/attendancetList";
                        </script>
                    </table>
                </div>
                </div>