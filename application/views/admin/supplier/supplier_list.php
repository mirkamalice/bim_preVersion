<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('151', 'created');
$edited = can_action('151', 'edited');
$deleted = can_action('151', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?= base_url('admin/supplier/') ?>"><?= lang('supplier') . ' ' . lang('list') ?></a>
        </li>
        <li class=""><a
                href="<?= base_url('admin/supplier/create_supplier') ?>"><?= lang('new') . ' ' . lang('supplier') ?></a>
        </li>

    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane active" id="manage">
            <?php } else { ?>
            <div class="panel panel-custom">
                <header class="panel-heading ">
                    <div class="panel-title"><strong><?= lang('supplier') . ' ' . lang('list') ?></strong></div>
                </header>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped DataTables " id="DataTables" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('name') ?></th>
                                <th class="col-sm-1"><?= lang('mobile') ?></th>
                                <th class="col-sm-1"><?= lang('phone') ?></th>
                                <th class="col-sm-2"><?= lang('email') ?></th>
                                <th class="col-sm-2"><?= lang('address') ?></th>
                                <th class="col-sm-2"><?= lang('vat_number') ?></th>

                                <?php $show_custom_fields = custom_form_table(19, null);
                                if (!empty($show_custom_fields)) {
                                    foreach ($show_custom_fields as $c_label => $v_fields) {
                                        if (!empty($c_label)) {
                                ?>
                                <th><?= $c_label ?> </th>
                                <?php }
                                    }
                                }
                                ?>
                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <th class="col-sm-1"><?= lang('action') ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <script type="text/javascript">
                        list = base_url + "admin/supplier/supplierList";
                        </script>
                    </table>

                </div>
            </div>

        </div>