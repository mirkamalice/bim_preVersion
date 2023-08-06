<?= message_box('success'); ?>
<?= message_box('error');
$created = can_action('39', 'created');
$edited = can_action('39', 'edited');
$deleted = can_action('39', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
    <div class="nav-tabs-custom">
        <?php $is_department_head = is_department_head();
        if ($this->session->userdata('user_type') == 1 || !empty($is_department_head)) { ?>
            <div class="btn-group pull-right btn-with-tooltip-group _filter_data filtered" data-toggle="tooltip" data-title="<?php echo lang('filter_by'); ?>">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu group animated zoomIn" style="width:300px;">
                    <li class="filter_by all_filter"><a href="#"><?php echo lang('all'); ?></a></li>
                    <li class="divider"></li>

                    <li class="dropdown-submenu pull-left  " id="from_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('group'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left from_account" style="">
                            <?php if (!empty($all_customer_group)) { ?>
                                <?php foreach ($all_customer_group as $customer_group_id =>  $customer_group) {
                                ?>
                                    <li class="filter_by" id="<?= $customer_group_id ?>" search-type="by_group">
                                        <a href="#"><?php echo $customer_group; ?></a>
                                    </li>
                                <?php }
                                ?>
                                <div class="clearfix"></div>
                            <?php } ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left " id="to_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('manufacturer'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left to_account" style="">
                            <?php
                            if (!empty($all_manufacturer)) { ?>
                                <?php foreach ($all_manufacturer as $manufacturer_id => $manufacturer) {
                                ?>
                                    <li class="filter_by" id="<?= $manufacturer_id ?>" search-type="by_manufacturer">
                                        <a href="#"><?php echo $manufacturer; ?></a>
                                    </li>
                                <?php }
                                ?>
                                <div class="clearfix"></div>
                            <?php } ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left " id="by_category">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('warehouse'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left by_category" style="">
                            <?php
                            if (!empty($warehouseList)) { ?>
                                <?php foreach ($warehouseList as $warehouseId => $warehouseName) {
                                ?>
                                    <li class="filter_by" id="<?= $warehouseId ?>" search-type="by_warehourse">
                                        <a href="#"><?php echo $warehouseName; ?></a>
                                    </li>
                                <?php }
                                ?>
                                <div class="clearfix"></div>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php } ?>
        <!-- Tabs within a box -->



        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">

            <li class="active"><a href="<?= base_url('admin/items/items_list') ?>"><?= lang('all_items') ?></a>
            </li>
            <li class=""><a href="<?= base_url('admin/items/new_items') ?>"><?= lang('new_items') ?></a>
            </li>
            <li class=""><a href="<?= base_url('admin/items/newitems_group') ?>"><?= lang('group') . ' ' . lang('list') ?></a>
            </li>


            <li class=""><a href="<?= base_url('admin/items/items_manufacturerlist') ?>"><?= lang('manufacturer') . ' ' . lang('list') ?></a>
            </li>
            <li><a class="import" href="<?= base_url() ?>admin/items/import"><?= lang('import') . ' ' . lang('items') ?></a>
            </li>
        </ul>
        <style type="text/css">
            .custom-bulk-button {
                display: initial;
            }
        </style>
        <div class="tab-content bg-white">
            <!-- ************** general *************-->
            <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
            <?php } else { ?>
                <div class="panel panel-custom">
                    <header class="panel-heading ">
                        <div class="panel-title"><strong><?= lang('all_items') ?></strong></div>
                    </header>
                <?php } ?>
                <div class="table-responsive">
                    <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <?php if (!empty($deleted)) { ?>
                                    <th data-orderable="false">
                                        <div class="checkbox c-checkbox">
                                            <label class="needsclick">
                                                <input id="select_all" type="checkbox">
                                                <span class="fa fa-check"></span></label>
                                        </div>
                                    </th>
                                <?php } ?>
                                <th><?= lang('item') ?></th>
                                <?php
                                $invoice_view = config_item('invoice_view');
                                if (!empty($invoice_view) && $invoice_view == '2') {
                                ?>
                                    <th><?= lang('hsn_code') ?></th>
                                <?php } ?>
                                <?php if (admin()) { ?>
                                    <th class="col-sm-1"><?= lang('cost_price') ?></th>
                                <?php } ?>
                                <th class="col-sm-1"><?= lang('unit_price') ?></th>
                                <th class="col-sm-1"><?= lang('unit') . ' ' . lang('type') ?></th>
                                <th class="col-sm-2"><?= lang('tax') ?></th>
                                <th class="col-sm-1"><?= lang('group') ?></th>

                                <?php $show_custom_fields = custom_form_table(18, null);
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
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    list = base_url + "admin/items/itemsList";
                                    bulk_url = base_url + "admin/items/bulk_delete";
                                    $('.filtered > .dropdown-toggle').on('click', function() {
                                        if ($('.group').css('display') == 'block') {
                                            $('.group').css('display', 'none');
                                        } else {
                                            $('.group').css('display', 'block')
                                        }
                                    });
                                    $('.filter_by').on('click', function() {
                                        $('.filter_by').removeClass('active');
                                        $('.group').css('display', 'block');
                                        $(this).addClass('active');
                                        var filter_by = $(this).attr('id');
                                        if (filter_by) {
                                            filter_by = filter_by;
                                        } else {
                                            filter_by = '';
                                        }
                                        var search_type = $(this).attr('search-type');
                                        if (search_type) {
                                            search_type = '/' + search_type;
                                        } else {
                                            search_type = '';
                                        }
                                        table_url(base_url + "admin/items/itemsList/" + filter_by +
                                            search_type);
                                    });
                                });
                            </script>
                        </tbody>
                    </table>
                </div>
                </div>

            </div>