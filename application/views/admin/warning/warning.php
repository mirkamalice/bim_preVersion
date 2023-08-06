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

            <li class="active"><a href="<?= base_url('admin/warning/') ?>"><?= lang('all_warning') ?></a>
            </li>
            <li>
                <a href="<?= base_url() ?>admin/warning/new_warning" data-toggle="modal" data-placement="top" data-target="#myModal_lg">
                    <?= ' ' . lang('new') . ' ' . lang('warning') ?></a>

            </li>
            <li>
                <a href="<?= base_url() ?>admin/warning/warning_type">
                    </i> <?= ' ' . lang('warning_type') ?></a>
            </li>


        </ul>

        <div class="panel-body bg-white">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?= lang('warning_by') ?></th>
                        <th><?= lang('warning_to') ?></th>
                        <th><?= lang('warning_type') ?></th>
                        <th><?= lang('subject') ?></th>
                        <th><?= lang('warning_date') ?></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            list = base_url + "admin/warning/warningList";
                        });
                    </script>
                </tbody>
            </table>
        </div>
    <?php
} ?>