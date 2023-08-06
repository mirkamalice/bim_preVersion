<?php echo message_box('success') ?>

<div class="row">
    <!-- Start Form test -->
    <div class="col-lg-12">

        <div class="panel panel-custom">
            <header class="panel-heading ">
                <div class="panel-title"><strong><?= lang('client_award') . ' ' . lang('points') ?></strong></div>

            </header>
            <div class="panel-body">
                <div class="panel-body">
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('name') ?></th>
                                <th><?= lang('award_point') ?></th>



                            </tr>
                        </thead>
                        <tbody>
                            <script type="text/javascript">
                            $(document).ready(function() {
                                list = base_url + "admin/invoice/clientawardpointslist";
                            });
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>