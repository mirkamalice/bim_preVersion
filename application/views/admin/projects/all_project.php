<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>

<div id="all_projects_state_report_div">

</div>

<?= message_box('success'); ?>
<?= message_box('error');
$all_customer_group = $this->db->where('type', 'projects')->order_by('customer_group_id', 'DESC')->get('tbl_customer_group')->result();
$all_client = $this->items_model->get_permission('tbl_client');
$created = can_action('57', 'created');
$edited = can_action('57', 'edited');
$deleted = can_action('57', 'deleted');
if (!empty($created) || !empty($edited)) {
?>
<div class="row">
    <div class="col-sm-12">
        <?php $is_department_head = is_department_head();
        if ($this->session->userdata('user_type') == 1 || !empty($is_department_head)) { ?>
            <div class="btn-group pull-right btn-with-tooltip-group _filter_data filtered" data-toggle="tooltip"
                 data-title="<?php echo lang('filter_by'); ?>">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu group animated zoomIn" style="width:300px;">
                    <li class="filter_by all_filter"><a href="#"><?php echo lang('all'); ?></a></li>
                    <li class="divider"></li>
                    
                    <li class="dropdown-submenu pull-left  " id="from_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('client'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left from_account" style="">
                            <?php
                            if (!empty($all_client)) {
                                foreach ($all_client as $v_client) {
                                    ?>
                                    <li class="filter_by" id="<?= $v_client->client_id ?>" search-type="by_client">
                                        <a href="#"><?php echo $v_client->name; ?></a>
                                    </li>
                                <?php }
                            }
                            ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left " id="to_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('staff'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left to_account" style="">
                            <?php if (admin()) { ?>
                                <li class="filter_by" id="everyone" search-type="by_staff">
                                    <a href="#"><?php echo lang('everyone'); ?></a>
                                </li>
                            <?php } ?>
                            <?php
                            if (!empty($assign_user)) { ?>
                                <?php foreach ($assign_user as $v_staff) {
                                    ?>
                                    <li class="filter_by" id="<?= $v_staff->user_id ?>" search-type="by_staff">
                                        <a href="#"><?php echo fullname($v_staff->user_id); ?></a>
                                    </li>
                                <?php }
                                ?>
                                <div class="clearfix"></div>
                            <?php } ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left " id="by_category">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('categories'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left by_category" style="">
                            <?php
                            if (!empty($all_customer_group)) { ?>
                                <?php foreach ($all_customer_group as $group) {
                                    ?>
                                    <li class="filter_by" id="<?= $group->customer_group_id ?>"
                                        search-type="by_category">
                                        <a href="#"><?php echo $group->customer_group; ?></a>
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
                            href="<?= base_url('admin/projects') ?>"><?= lang('all') . ' ' . lang($tab) ?></a>
                </li>
                
                <li class="<?= $active == 2 ? 'active' : ''; ?>"><a
                            href="<?= base_url('admin/projects/create') ?>"><?= lang('new_project') ?></a>
                </li>
                <li><a class="import"
                       href="<?= base_url() ?>admin/projects/import"><?= lang('import') . ' ' . lang('project') ?></a>
                </li>
            </ul>
            <div class="tab-content bg-white">
                <!-- ************** general *************-->
                <div class="tab-pane <?= $active == 1 || $active == 'overdue' || $active == 'started' || $active == 'on_hold' || $active == 'in_progress' || $active == 'cancel' || $active == 'completed' ? 'active' : ''; ?>"
                     id="manage">
                    <?php } else { ?>
                    <style type="text/css">
                        .pull-right a {
                            font-size: 14px;
                            border: 1px solid #e8e8e8;
                            padding: 4px;
                            margin-left: 10px;
                            color: #656565;
                        }
                    </style>
                    <div class="panel panel-custom">
                        <header class="panel-heading ">
                            <div class="panel-title"><strong><?= lang('all') . ' ' . lang($tab) ?></strong></div>
                        </header>
                        <?php } ?>
                        
                        <style type="text/css">
                            .custom-bulk-button {
                                display: initial;
                            }
                        </style>
                        <div class="table-responsive">
                            <table class="table table-striped bulk_table" id="DataTables" cellspacing="0" width="100%">
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
                                    <th><?= lang('project_name') ?></th>
                                    <th><?= lang('categories') ?></th>
                                    <th><?= lang('tags') ?></th>
                                    <th><?= lang('client') ?></th>
                                    <th><?= lang('end_date') ?></th>
                                    <th><?= lang('assigned_to') ?></th>
                                    <th><?= lang('status') ?></th>
                                    <?php $show_custom_fields = custom_form_table(4, null);
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
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        list = base_url + "admin/projects/projectList";
                                        bulk_url = base_url + "admin/projects/bulk_delete";
                                        <?php if (admin_head()) { ?>
                                        $('.filtered > .dropdown-toggle').on('click', function () {
                                            if ($('.group').css('display') == 'block') {
                                                $('.group').css('display', 'none');
                                            } else {
                                                $('.group').css('display', 'block')
                                            }
                                        });
                                        $('.all_filter').on('click', function () {
                                            $('.to_account').removeAttr("style");
                                            $('.from_account').removeAttr("style");
                                        });
                                        $('.from_account li').on('click', function () {
                                            if ($('.to_account').css('display') == 'block') {
                                                $('.to_account').removeAttr("style");
                                                $('.from_account').css('display', 'block');
                                            } else {
                                                $('.from_account').css('display', 'block')
                                            }
                                        });

                                        $('.to_account li').on('click', function () {
                                            if ($('.from_account').css('display') == 'block') {
                                                $('.from_account').removeAttr("style");
                                                $('.to_account').css('display', 'block');
                                            } else {
                                                $('.to_account').css('display', 'block');
                                            }
                                        });
                                        $('.by_category li').on('click', function () {
                                            if ($('.to_account').css('display') == 'block') {
                                                $('.to_account').removeAttr("style");
                                                $('.from_account').removeAttr("style");
                                                $('.by_category').css('display', 'block');
                                            } else if ($('.from_account').css('display') == 'block') {
                                                $('.to_account').removeAttr("style");
                                                $('.from_account').removeAttr("style");
                                                $('.by_category').css('display', 'block');
                                            } else {
                                                $('.by_category').css('display', 'block');
                                            }
                                        });
                                        $('.filter_by').on('click', function () {
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
                                            table_url(base_url + "admin/projects/projectList/" +
                                                filter_by + search_type);
                                        });
                                        <?php } ?>
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
    
    <script type="text/javascript">
        <?php if (empty($project_info)) { ?>
        $('.hourly_rate').hide();
        <?php } ?>

        function get_billing_value(val) {

            if (val == 'fixed_rate') {
                $('.fixed_rate').show();
                $(".fixed_rate").removeAttr('disabled');
                $('.hourly_rate').hide();
                $(".hourly_rate").attr('disabled', 'disabled');
                $('.based_on_tasks_hour').hide();
            } else if (val == 'tasks_hours') {
                $('.hourly_rate').hide();
                $(".hourly_rate").attr('disabled', 'disabled');
                $('.fixed_rate').hide();
                $(".fixed_rate").attr('disabled', 'disabled');
                $('.based_on_tasks_hour').show();
            } else {
                $('.hourly_rate').show();
                $(".hourly_rate").removeAttr('disabled');
                $('.fixed_rate').hide();
                $(".fixed_rate").attr('disabled', 'disabled');
                $('.based_on_tasks_hour').show();
            }
            if (val == 'project_hours') {
                $('.based_on_tasks_hour').hide();
            }
        }
    </script>
    
    <script>
        $(document).ready(function () {
            ins_data(base_url + 'admin/projects/all_projects_state_report')
        });
    </script>