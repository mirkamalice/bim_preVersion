<?php include_once 'asset/admin-ajax.php'; ?>
<?php echo message_box('success'); ?>
<?php echo message_box('error');
$created = can_action('82', 'created');
$edited = can_action('82', 'edited');
$deleted = can_action('82', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="btn-group pull-right btn-with-tooltip-group filtered" data-toggle="tooltip"
    data-title="<?php echo lang('filter_by'); ?>">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa fa-filter" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu group animated zoomIn" style="width:300px;">
        <li class="filter_by all_filter"><a href="#"><?php echo lang('all'); ?></a></li>
        <li class="divider"></li>


        <li class="dropdown-submenu pull-left " id="by_item_name">
            <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('item_name'); ?></a>
            <ul class="dropdown-menu dropdown-menu-left by_item_name" style="">
                <?php
                    $all_items = get_result('tbl_stock');
                    if (!empty($all_items)) { ?>
                <?php foreach ($all_items as $v_items) {
                        ?>
                <li class="filter_by" id="<?= $v_items->stock_id ?>" search-type="by_item_name">
                    <a href="#"><?php echo $v_items->item_name; ?></a>
                </li>
                <?php }
                    }
                    ?>
            </ul>
        </li>
        <div class="clearfix"></div>
        <li class="dropdown-submenu pull-left " id="by_employee">
            <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('employee') . ' ' . lang('name'); ?></a>
            <ul class="dropdown-menu dropdown-menu-left by_employee" style="">
                <?php
                    if (!empty($all_employee)) { ?>
                <?php foreach ($all_employee as $dept_name => $v_all_employee) {
                            if (!empty($v_all_employee)) {
                                foreach ($v_all_employee as $v_employee) {
                        ?>
                <li class="filter_by" id="<?= $v_employee->user_id ?>" search-type="by_employee">
                    <a href="#"><?php echo $v_employee->fullname . ' ( ' . $v_employee->designations . ' )'; ?></a>
                </li>
                <?php }
                            }
                        }
                    }
                    ?>
            </ul>
        </li>
    </ul>


</div>
<div class="nav-tabs-custom">
    <div class="nav nav-tabs" style="margin-top: 1px">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs">
            <li class="<?= $active == 1 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/stock/assign_stock') ?>"><?= lang('assign_stock_list') ?></a>
            </li>
            <li class="<?= $active == 2 ? 'active' : '' ?>"><a
                    href="<?= base_url('admin/stock/create_assignstock') ?>"><?= lang('assign_stock') ?></a>
            </li>
        </ul>
        <div class="tab-content bg-white">
            <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_list" style="position: relative;">
                <?php } else { ?>
                <div class="panel panel-custom">
                    <header class="panel-heading ">
                        <div class="panel-title"><strong><?= lang('assign_stock_list') ?></strong></div>
                    </header>
                    <?php } ?>
                    <div class="table-responsive">
                        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="col-sm-1"><?= lang('sl') ?></th>
                                    <th><?= lang('item_name') ?></th>
                                    <th><?= lang('stock_category') ?></th>
                                    <th><?= lang('assign_quantity') ?></th>
                                    <th><?= lang('assign_date') ?></th>
                                    <th><?= lang('assigned_user') ?></th>
                                    <?php if (!empty($deleted) || !empty($edited)) { ?>
                                    <th class="col-sm-1 hidden-print"><?= lang('action') ?></th>
                                    <?php } ?>

                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                        <script type="text/javascript">
                        $(document).ready(function() {
                            list = base_url + "admin/stock/assign_stockList";
                            $('.filtered > .dropdown-toggle').on('click', function() {
                                if ($('.group').css('display') == 'block') {
                                    $('.group').css('display', 'none');
                                } else {
                                    $('.group').css('display', 'block')
                                }
                            });
                            $('.all_filter').on('click', function() {
                                $('.to_account').removeAttr("style");
                                $('.from_account').removeAttr("style");
                            });
                            $('.by_sub_category li').on('click', function() {
                                if ($('.by_employee').css('display') == 'block') {
                                    $('.by_employee').removeAttr("style");
                                    $('.by_sub_category').css('display', 'block');
                                } else {
                                    $('.by_sub_category').css('display', 'block')
                                }
                                if ($('.by_item_name').css('display') == 'block') {
                                    $('.by_item_name').removeAttr("style");
                                }
                            });

                            $('.by_employee li').on('click', function() {
                                if ($('.by_item_name').css('display') == 'block') {
                                    $('.by_item_name').removeAttr("style");
                                    $('.by_employee').css('display', 'block');
                                } else {
                                    $('.by_employee').css('display', 'block');
                                }

                            });

                            $('.by_item_name li').on('click', function() {
                                if ($('.by_sub_category').css('display') == 'block') {
                                    $('.by_sub_category').removeAttr("style");
                                    $('.by_item_name').css('display', 'block');
                                } else {
                                    $('.by_item_name').css('display', 'block');
                                }
                                if ($('.by_employee').css('display') == 'block') {
                                    $('.by_employee').removeAttr("style");
                                }
                            });

                            $('.filter_by').on('click', function() {
                                $('.filter_by').removeClass('active');
                                $('.group').css('display', 'block');
                                $(this).addClass('active');
                                var filter_by = $(this).attr('id');
                                var search_type = $(this).attr('search-type');
                                if (filter_by) {
                                    filter_by = filter_by;
                                } else {
                                    filter_by = '';
                                }
                                if (search_type) {
                                    search_type = '/' + search_type;
                                } else {
                                    search_type = '';
                                }
                                //                                    alert(base_url + "admin/stock/assign_stockList/" + filter_by + search_type);
                                table_url(base_url + "admin/stock/assign_stockList/" +
                                    filter_by +
                                    search_type);
                            });

                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>