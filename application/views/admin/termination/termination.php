<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('39', 'created');
$edited = can_action('39', 'edited');
$deleted = can_action('39', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
    <div class="nav-tabs-custom">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">

            <li class="active"><a href="<?= base_url('admin/termination/') ?>"><?= lang('all_termination') ?></a>
            </li>
            <li>
                <a href="<?= base_url() ?>admin/termination/new_termination" data-toggle="modal" data-placement="top" data-target="#myModal_lg">
                    <?= ' ' . lang('new') . ' ' . lang('termination') ?></a>

            </li>
            <li>
                <a href="<?= base_url() ?>admin/termination/termination_type">
                    </i> <?= ' ' . lang('termination_type') ?></a>
            </li>


        </ul>

        <div class="panel-body bg-white">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?= lang('employee_name') ?></th>
                        <th><?= lang('termination_type') ?></th>
                        <th><?= lang('notice_date') ?></th>
                        <th><?= lang('termination_date') ?></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            list = base_url + "admin/termination/terminationList";
                        });
                    </script>
                </tbody>
            </table>
        </div>
    <?php
} ?>