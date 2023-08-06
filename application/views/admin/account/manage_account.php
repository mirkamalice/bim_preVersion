<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('36', 'created');
$edited = can_action('36', 'edited');
$deleted = can_action('36', 'deleted');
if (!empty($created) || !empty($edited)) {
$currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
?>


<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                    href="<?= base_url('admin/account/manage_account') ?>"><?= lang('all') . ' ' . lang('account') ?></a>
        </li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                    href="<?= base_url('admin/account/create_account') ?>"><?= lang('new_account') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <?php } else { ?>
            <div class="panel panel-custom">
                <header class="panel-heading ">
                    <div class="panel-title"><strong><?= lang('all') . ' ' . lang('account') ?></strong></div>
                </header>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped DataTables " id="DataTables" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('account') ?></th>
                                <th><?= lang('description') ?></th>
                                <th><?= lang('account_number') ?></th>
                                <th><?= lang('phone') ?></th>
                                <th><?= lang('balance') ?></th>
                                <?php $show_custom_fields = custom_form_table(21, null);
                                if (!empty($show_custom_fields)) {
                                    foreach ($show_custom_fields as $c_label => $v_fields) {
                                        if (!empty($c_label)) {
                                ?>
                                <th><?= $c_label ?> </th>
                                <?php }
                                    }
                                }
                                ?>
                                <th class="col-options no-sort"><?= lang('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <script type="text/javascript">
                        list = base_url + "admin/account/accountList";
                        </script>
                    </table>

                </div>
            </div>


        </div>
    </div>