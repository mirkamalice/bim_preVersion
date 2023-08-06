<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('95', 'created');
$edited = can_action('95', 'edited');
$deleted = can_action('95', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/payroll/hourly_rate') ?>"><?= lang('hourly_rate_list') ?></a>
        </li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/payroll/create_hourlyrate') ?>"><?= lang('set_hourly_grade') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <?php } else { ?>
            <div class="panel panel-custom">
                <header class="panel-heading ">
                    <div class="panel-title"><strong><?= lang('hourly_rate_list') ?></strong></div>
                </header>
                <?php } ?>
                <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="col-sm-1"><?= lang('sl') ?></th>
                            <th><?= lang('hourly_grade') ?></th>
                            <th><?= lang('hourly_rates') ?></th>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                            <th class="col-sm-2"><?= lang('action') ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <script type="text/javascript">
                        $(document).ready(function() {
                            list = base_url + "admin/payroll/hourly_rateList";
                        });
                        </script>

                    </tbody>
                </table>
            </div>

        </div>
    </div>