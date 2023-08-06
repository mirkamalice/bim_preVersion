<?php echo message_box('success');

$edited = can_action('204', 'edited');
$deleted = can_action('204', 'deleted');
?>

<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">
        
        <!-- <form method="post" action="<?php echo base_url() ?>admin/settings/save_award_rule" class="form-horizontal"> -->
        <div class="panel panel-custom">
            <header class="panel-heading "><?= lang('award_rule') . ' ' . lang('setting') ?>
                <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                    <a href="<?= base_url() ?>admin/settings/new_rule" class="btn btn-xs btn-info" data-toggle="modal"
                       data-placement="top" data-target="#myModal_large">
                        <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('rule') ?></a>
                </div>
            </header>
            <div class="panel-body">
                <div class="panel-body">
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><?= lang('name') ?></th>
                            <th><?= lang('client_name') ?></th>
                            <th><?= lang('award_point_from') ?></th>
                            <th><?= lang('award_point_to') ?></th>
                            <th><?= lang('card') ?></th>
                            <th><?= lang('date_create') ?></th>
                            <?php $show_custom_fields = custom_form_table(15, null);
                            if (!empty($show_custom_fields)) {
                                foreach ($show_custom_fields as $c_label => $v_fields) {
                                    if (!empty($c_label)) {
                                        ?>
                                        <th><?= $c_label ?> </th>
                                    <?php }
                                }
                            }
                            ?>
                            
                            <?php if (!empty($created) || !empty($deleted)) { ?>
                                
                                <th><?= lang('action') ?></th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                list = base_url + "admin/settings/awardrulelist";
                            });
                        </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>