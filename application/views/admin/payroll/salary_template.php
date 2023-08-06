<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('94', 'created');
$edited = can_action('94', 'edited');
$deleted = can_action('94', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/payroll/salary_template') ?>"><?= lang('salary_template_list') ?></a>
        </li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/payroll/create_salarytemplate') ?>"><?= lang('new_template') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <?php } else { ?>
            <div class="panel panel-custom">
                <header class="panel-heading ">
                    <div class="panel-title"><strong><?= lang('salary_template_list') ?></strong></div>
                </header>
                <?php } ?>
                <table class="table table-striped " id="DataTables" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="col-sm-1"><?= lang('sl') ?></th>
                            <th><?= lang('salary_grade') ?></th>
                            <th><?= lang('basic_salary') ?></th>
                            <th><?= lang('overtime') ?>
                                <small>(<?= lang('per_hour') ?>)</small>
                            </th>
                            <th class="col-sm-2"><?= lang('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <script type="text/javascript">
                        $(document).ready(function() {
                            list = base_url + "admin/payroll/salary_templateList";
                        });
                        </script>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>