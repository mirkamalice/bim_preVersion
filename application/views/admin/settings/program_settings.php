<?php echo message_box('success') ?>

<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">
        
        <!-- <form method="post" action="<?php echo base_url() ?>admin/settings/save_award_rule" class="form-horizontal"> -->
        <div class="panel panel-custom">
            <header class="panel-heading "><?= lang('program_rule') . ' ' . lang('setting') ?>
                <div class="pull-right hidden-print" style="padding-top: 0px;padding-bottom: 8px">
                    <a href="<?= base_url() ?>admin/settings/new_program" class="btn btn-xs btn-info"
                       data-toggle="modal" data-placement="top" data-target="#myModal_large">
                        <i class="fa fa-plus "></i> <?= ' ' . lang('new') . ' ' . lang('program') ?></a>
                </div>
            </header>
            <div class="panel-body">
                <div class="panel-body">
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th><?= lang('program_name') ?></th>
                            <th><?= lang('client_name') ?></th>
                            <th><?= lang('award_name') ?></th>
                            <th><?= lang('start_date') ?></th>
                            <th><?= lang('end_date') ?></th>
                            <th><?= lang('status') ?></th>
                            
                            
                            <th><?= lang('action') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <script type="text/javascript">
                            $(document).ready(function () {
                                list = base_url + "admin/settings/awardprogramlist";
                            });
                        </script>
                        </tbody>
                    </table>
                </div>
            </div>