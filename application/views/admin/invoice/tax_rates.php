<?= message_box('success');
$created = can_action('16', 'created');
$edited = can_action('16', 'edited');
$deleted = can_action('16', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?= base_url('admin/invoice/tax_rates') ?>"><?= lang('tax_rates') ?></a>
        </li>
        <li class=""><a href="<?= base_url('admin/invoice/createtax_rates') ?>"><?= lang('new_tax_rate') ?></a>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane active" id="manage">
            <?php } else { ?>
            <div class="panel panel-custom">
                <header class="panel-heading ">
                    <div class="panel-title"><strong><?= lang('tax_rates') ?></strong></div>
                </header>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('tax_rate_name') ?></th>
                                <th><?= lang('tax_rate_percent') ?></th>
                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <th class="hidden-print"><?= lang('action') ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <script type="text/javascript">
                            $(document).ready(function() {
                                list = base_url + "admin/invoice/taxList";
                            });
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>


            </form>

        </div>
    </div>
</div>