<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('150', 'created');
$edited = can_action('150', 'edited');
$deleted = can_action('150', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
<?php include_once 'assets/admin-ajax.php'; ?>
<?php include_once 'assets/js/sales.php'; ?>

<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs">
        <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/return_stock') ?>"><?= lang('manage') . ' ' . lang('return_stock') ?></a>
        </li>
        <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                href="<?= base_url('admin/return_stock/create_returnstock') ?>"><?= lang('new') . ' ' . lang('return_stock') ?></a>
        </li>
    </ul>
    <div class="tab-content bg-white">
        <!-- ************** general *************-->
        <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <?php } else { ?>
            <div class="panel panel-custom">
                <header class="panel-heading ">
                    <div class="panel-title"><strong><?= lang('return_stock') ?></strong></div>
                </header>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped DataTables " id="DataTables" width="100%">
                        <thead>
                            <tr>
                                <th><?= lang('reference_no') ?></th>
                                <th><?= lang('related_to') ?></th>
                                <th><?= lang('return_stock_date') ?></th>
                                <th><?= lang('due_amount') ?></th>
                                <th><?= lang('status') ?></th>
                                <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <th class="col-options no-sort"><?= lang('action') ?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <script type="text/javascript">
                            list = base_url + "admin/return_stock/return_stockList";
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($return_stock_info)) {
                    if (!empty($supplier_id)) {
                        $val = $supplier_id;
                        $name = 'supplier_id';
                    } elseif (!empty($client_id)) {
                        $val = $client_id;
                        $name = 'client_id';
                    } ?>
        var val = '<?= $val ?>';
        var module_id = '<?= $return_stock_info->invoices_id ?>';
        var name = '<?= $name ?>';
        $.get('<?= base_url() ?>admin/return_stock/client_change_data/' + name + '/' + val, function(response) {
            $('#related_result').html(response.related_info);
            init_selectpicker();
            $('.getItemsInfo').selectpicker('val', module_id);
            if (response.related_info != '') {
                $('#related_result').removeClass('hide');
            } else {
                $('#related_result').addClass('hide');
            }
        }, 'json');
        <?php } ?>
        $('body').on('change', 'select[id="related_to"]', function() {
            var val = $(this).val();
            var name = $(this).attr("name");
            $('#related_result').empty();
            if (val == '' || val == '-') {
                return false;
            }
            $.get('<?= base_url() ?>admin/return_stock/client_change_data/' + name + '/' + val,
                function(
                    response) {
                    init_selectpicker();
                    $('#related_result').html(response.related_info);
                    if (response.related_info != '') {
                        $('#related_result').removeClass('hide');
                    } else {
                        $('#related_result').addClass('hide');
                    }
                }, 'json');
        });
    });
    </script>