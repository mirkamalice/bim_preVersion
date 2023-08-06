<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">

        <div class="panel panel-custom">
            <header class="panel-heading "><?= lang('Staff') . ' ' . lang('points') ?>
                <div class="pull-right">
                    <span style="color:red">Total Point :</span> <?php

                                                                    echo display_money($this->user_model->get_staff_point_byid($profile_info->user_id));
                                                                    ?>
                </div>
            </header>
            <div class="panel-body">
                <div class="panel-body">
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('client_name') ?></th>
                                <th><?= lang('invoices_no') ?></th>
                                <th><?= lang('date') ?></th>
                                <th><?= lang('award_point') ?></th>



                            </tr>
                        </thead>
                        <tbody>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    list = base_url + "admin/user/userawardpointslist/<?= $profile_info->user_id ?>";
                                });
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>