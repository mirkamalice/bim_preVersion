<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('56', 'created');
$edited = can_action('56', 'edited');
$deleted = can_action('56', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/opportunities') ?>"><?= lang('all_opportunities') ?></a></li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/opportunities/create') ?>"><?= lang('new_opportunities') ?></a></li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <?php } else { ?>
            <div class="panel panel-custom">
                <header class="panel-heading ">
                    <div class="panel-title"><strong><?= lang('all_opportunities') ?></strong></div>
                </header>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('opportunity_name') ?></th>
                                <th><?= lang('stages') ?></th>
                                <th><?= lang('state') ?></th>
                                <th><?= lang('expected_revenue') ?></th>
                                <th><?= lang('next_action') ?></th>
                                <th><?= lang('next_action_date') ?></th>
                                <?php $show_custom_fields = custom_form_table(8, null);
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
                                <th class="col-options no-sort"><?= lang('action') ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <script type="text/javascript">
                        $(document).ready(function() {
                            list = base_url + "admin/opportunities/opportunitiesList";
                        });
                        </script>
                    </table>
                </div>
            </div>

        </div>
    </div>