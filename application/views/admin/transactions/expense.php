<?= message_box('success'); ?>
<?= message_box('error');
?>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>

<div id="transactions_state_report_div">

</div>


<?php
$created = can_action('31', 'created');
$edited = can_action('31', 'edited');
$deleted = can_action('31', 'deleted');
$expense_category = $this->db->get('tbl_expense_category')->result();
$id = $this->uri->segment(5);
if (!empty($created) || !empty($edited)) {
?>
<div class="row">
    <div class="col-sm-12">
        <?php $is_department_head = is_department_head();
            if ($this->session->userdata('user_type') == 1 || !empty($is_department_head)) { ?>
        <div class="btn-group pull-right btn-with-tooltip-group _filter_data filtered" data-toggle="tooltip"
            data-title="<?php echo lang('filter_by'); ?>">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-filter" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu group animated zoomIn" style="width:300px;">
                <li class="filter_by all_filter"><a href="#"><?php echo lang('all'); ?></a></li>
                <li class="divider"></li>

                <li class="dropdown-submenu pull-left  " id="from_account">
                    <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('account'); ?></a>
                    <ul class="dropdown-menu dropdown-menu-left from_account" style="">
                        <?php
                                $account_info = $this->db->order_by('account_id', 'DESC')->get('tbl_accounts')->result();
                                if (!empty($account_info)) {
                                    foreach ($account_info as $v_account) {
                                ?>
                        <li class="filter_by" id="<?= $v_account->account_id ?>" search-type="by_account">
                            <a href="#"><?php echo $v_account->account_name; ?></a>
                        </li>
                        <?php }
                                }
                                ?>
                    </ul>
                </li>
                <div class="clearfix"></div>
                <li class="dropdown-submenu pull-left " id="to_account">
                    <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('categories'); ?></a>
                    <ul class="dropdown-menu dropdown-menu-left to_account" style="">
                        <?php
                                $income_category = $this->db->get('tbl_expense_category')->result();
                                if (count(array($income_category)) > 0) { ?>
                        <?php foreach ($income_category as $v_category) {
                                    ?>
                        <li class="filter_by" id="<?= $v_category->expense_category_id ?>" search-type="by_category">
                            <a href="#"><?php echo $v_category->expense_category; ?></a>
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
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : ''; ?>"><a
                        href="<?= base_url('admin/transactions/expense') ?>"><?= lang('all_expense') ?></a>
                </li>
                <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                        href="<?= base_url('admin/transactions/create_expense') ?>"><?= lang('new_expense') ?></a>
                </li>
                <li><a class="import"
                        href="<?= base_url() ?>admin/transactions/import/Expense"><?= lang('import') . ' ' . lang('expense') ?></a>
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
                            <div class="panel-title"><strong><?= lang('all_expense') ?></strong></div>
                        </header>
                        <?php } ?>
                        <div class="table-responsive">
                            <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0"
                                width="100%">
                                <thead>
                                    <tr>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                        <th data-orderable="false">
                                            <div class="checkbox c-checkbox">
                                                <label class="needsclick">
                                                    <input id="select_all" type="checkbox">
                                                    <span class="fa fa-check"></span></label>
                                            </div>
                                        </th>
                                        <?php } ?>
                                        <th><?= lang('expense') . ' ' . lang('prefix') ?></th>
                                        <th><?= lang('name') . '/' . lang('title') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('account_name') ?></th>
                                        <th class="col-currency"><?= lang('amount') ?></th>
                                        <th><?= lang('tags') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <?php $show_custom_fields = custom_form_table(2, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                        ?>
                                        <th><?= $c_label ?> </th>
                                        <?php }
                                            }
                                        }
                                        ?>
                                        <th><?= lang('attachment') ?></th>
                                        <th><?= lang('action') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <script type="text/javascript">
                                    $(document).ready(function() {
                                        list = base_url + "admin/transactions/expenseList";
                                        bulk_url = base_url + "admin/transactions/bulk_delete_expense";
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
                                        $('.from_account li').on('click', function() {
                                            if ($('.to_account').css('display') == 'block') {
                                                $('.to_account').removeAttr("style");
                                                $('.from_account').css('display', 'block');
                                            } else {
                                                $('.from_account').css('display', 'block')
                                            }
                                        });

                                        $('.to_account li').on('click', function() {
                                            if ($('.from_account').css('display') == 'block') {
                                                $('.from_account').removeAttr("style");
                                                $('.to_account').css('display', 'block');
                                            } else {
                                                $('.to_account').css('display', 'block');
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
                                            table_url(base_url + "admin/transactions/expenseList/" +
                                                filter_by + search_type);
                                        });
                                    });
                                    </script>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
    $('#repeat_every').on('change', function() {
        if ($('input[name="billable"]').prop('checked') == true) {
            $('.billable_recurring_options').removeClass('hide');
        } else {
            $('.billable_recurring_options').addClass('hide');
        }
    });
    // hide invoice recurring options on page load
    $('#repeat_every').trigger('change');
    </script>

    <script>
    $(document).ready(function() {
        ins_data(base_url + 'admin/transactions/transactions_state_report')
    });
    </script>