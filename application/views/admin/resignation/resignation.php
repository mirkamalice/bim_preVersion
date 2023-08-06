<?php echo message_box('success'); ?>
<?php echo message_box('error');
$created = can_action('100', 'created');
$edited = can_action('100', 'edited');
$deleted = can_action('100', 'deleted');
?>
<div class="panel panel-custom" style="border: none;" data-collapsed="0">
    <div class="panel-heading">
        <div class="panel-title">
            <?= lang('resignation') ?>
            <?php if (!empty($created)) { ?>
                <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                    <a href="<?= base_url() ?>admin/resignation/new_resignation" class="btn btn-xs btn-info" data-toggle="modal" data-placement="top" data-target="#myModal_lg">
                        <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('resignation') ?></a>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- Table -->
    <div class="panel-body">
        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><?= lang('employee_name') ?></th>
                    <th><?= lang('resignation_date') ?></th>
                    <th><?= lang('last_working_date') ?></th>
                    <th><?= lang('action') ?></th>
                </tr>
            </thead>
            <tbody>
                <script type="text/javascript">
                    $(document).ready(function() {
                        list = base_url + "admin/resignation/resignationList";
                    });
                </script>
            </tbody>
        </table>
    </div>
</div>