<?= message_box('success'); ?>

<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="<?= base_url('admin/quotations/quotations_form') ?>"><?= lang('quotations_form') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="<?= base_url('admin/quotations/new_quotations_form') ?>"><?= lang('new_quotations_form') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th><?= lang('title') ?></th>
                        <th><?= lang('created_by') ?></th>
                        <th><?= lang('created_date') ?></th>
                        <th><?= lang('status') ?></th>
                        <th><?= lang('action') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            list = base_url + "admin/quotations/quotationsformList";
                        });
                    </script>
                </tbody>
            </table>
        </div>
    </div>
</div>