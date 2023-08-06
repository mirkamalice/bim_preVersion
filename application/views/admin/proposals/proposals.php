<form name="myform" role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
    action="<?php echo base_url(); ?>admin/proposals/save_proposals/<?php
                                                                                                                                                                                if (!empty($proposals_info)) {
                                                                                                                                                                                    echo $proposals_info->proposals_id;
                                                                                                                                                                                }
                                                                                                                                                                                ?>" method="post" class="form-horizontal  ">

    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
    <?php include_once 'assets/admin-ajax.php'; ?>
    <?php include_once 'assets/js/sales.php'; ?>
    <?= message_box('success'); ?>
    <?= message_box('error');
    $created = can_action('140', 'created');
    $edited = can_action('140', 'edited');
    $deleted = can_action('140', 'deleted');
    if (!empty($created) || !empty($edited)) {
    ?>

    <?php
        $curency = $this->proposal_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        ?>
    <div id="proposals_state_report_div"> <?php //$this->load->view("admin/proposals/proposals_state_report"); 
                                                ?></div>
    <?php
        $type = $this->uri->segment(5);

        if (empty($type)) {
            $type = '_' . jdate('Y');
        }
        ?>
    <div class="btn-group mb-lg pull-left mr">
        <button class=" btn btn-xs btn-white dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-search"></i>

            <?php
                echo lang('filter_by'); ?>
            <span id="showed_result">
                <?php if (!empty($type) && !is_numeric($type)) {
                        $ex = explode('_', $type);
                        if (!empty($ex)) {
                            if (!empty($ex[1]) && is_numeric($ex[1])) {
                                echo ' : ' . $ex[1];
                            } else {
                                echo ' : ' . lang($type);
                            }
                        } else {
                            echo ' : ' . lang($type);
                        }
                    } ?>
            </span>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu animated zoomIn">
            <li id="all" class="filter_by_type" search-type="<?= lang('all'); ?>"><a href="#"><?= lang('all'); ?></a>
            </li>
            <?php
                $invoiceFilter = $this->proposal_model->get_invoice_filter();
                if (!empty($invoiceFilter)) {
                    foreach ($invoiceFilter as $v_Filter) {
                ?>
            <li class="filter_by_type" search-type="<?= $v_Filter['name'] ?>" id="<?= $v_Filter['value'] ?>">
                <a href="#"><?= $v_Filter['name'] ?></a>
            </li>
            <?php
                    }
                }
                ?>
        </ul>
    </div>
    <?php
        if ($this->session->userdata('user_type') == 1) {
            $type = 'proposal';
            if ($h_s == 'block') {
                $title = lang('hide_quick_state');
                $url = 'hide';
                $icon = 'fa fa-eye-slash';
            } else {
                $title = lang('view_quick_state');
                $url = 'show';
                $icon = 'fa fa-eye';
            }
        ?>
    <div onclick="slideToggle('#state_report')" id="quick_state" data-toggle="tooltip" data-placement="top"
        title="<?= $title ?>" class="btn-xs btn btn-purple pull-left">
        <i class="fa fa-bar-chart"></i>
    </div>
    <div class="btn-xs btn btn-white pull-left ml ">
        <a class="text-dark" id="change_report"
            href="<?= base_url() ?>admin/dashboard/change_report/<?= $url . '/' . $type ?>"><i class="<?= $icon ?>"></i>
            <span><?= ' ' . lang('quick_state') . ' ' . lang($url) . ' ' . lang('always') ?></span></a>
    </div>
    <?php }

        ?>
    <a data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/invoice/zipped/proposal"
        class="btn btn-success btn-xs ml-lg"><?= lang('zip_proposal') ?></a>
    <div class="row">
        <div class="col-sm-12">
            <?php $is_department_head = is_department_head();
                if ($this->session->userdata('user_type') == 1 || !empty($is_department_head)) { ?>
            <div class="ml-lg btn-group pull-right btn-with-tooltip-group _filter_data filtered" data-toggle="tooltip"
                data-title="<?php echo lang('filter_by'); ?>">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu group animated zoomIn" style="width:300px;">
                    <li class="filter_by all_filter"><a href="#"><?php echo lang('all'); ?></a></li>
                    <li class="divider"></li>
                    <li class="dropdown-submenu pull-left  " id="from_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('invoices'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left from_account" style="">
                            <?php
                                    $invoice_info = $this->invoice_model->get_permission('tbl_invoices');
                                    if (!empty($invoice_info)) {
                                        foreach ($invoice_info as $v_invoice) {
                                    ?>
                            <li class="filter_by" id="<?= $v_invoice->invoices_id ?>" search-type="by_invoice">
                                <a href="#"><?php echo $v_invoice->reference_no; ?></a>
                            </li>
                            <?php }
                                    }
                                    ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left  " id="from_reporter">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('estimate'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left from_reporter" style="">
                            <?php
                                    $all_estimates = $this->invoice_model->get_permission('tbl_estimates');
                                    if (!empty($all_estimates)) {
                                        foreach ($all_estimates as $v_estimates) {
                                    ?>
                            <li class="filter_by" id="<?= $v_estimates->estimates_id ?>" search-type="by_estimates">
                                <a href="#"><?php echo $v_estimates->reference_no; ?></a>
                            </li>
                            <?php }
                                    }
                                    ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left " id="to_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('agent'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left to_account" style="">
                            <?php
                                    $all_agent = $this->db->where('role_id != ', 2)->get('tbl_users')->result();
                                    if (!empty($all_agent)) {
                                        foreach ($all_agent as $v_agent) {
                                    ?>
                            <li class="filter_by" id="<?= $v_agent->user_id ?>" search-type="by_agent">
                                <a href="#"><?php echo fullname($v_agent->user_id); ?></a>
                            </li>
                            <?php }
                                    }
                                    ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <?php } ?>
            <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="<?= base_url('admin/proposals') ?>"><?= lang('all_proposals') ?></a>
                    </li>
                    <li class=""><a
                            href="<?= base_url('admin/proposals/createproposal') ?>"><?= lang('create_proposal') ?></a>
                    </li>


                </ul>
                <div class="tab-content bg-white">
                    <!-- ************** general *************-->
                    <div class="tab-pane active" id="manage">
                        <?php } else { ?>
                        <div class="panel panel-custom">
                            <header class="panel-heading ">
                                <div class="panel-title"><strong><?= lang('all_proposals') ?></strong></div>
                            </header>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-striped DataTables " id="DataTables" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th><?= lang('proposal') ?> #</th>
                                            <th><?= lang('proposal_date') ?></th>
                                            <th><?= lang('expire_date') ?></th>
                                            <th><?= strtoupper(lang('TO')) ?></th>
                                            <th><?= lang('amount') ?></th>
                                            <th><?= lang('status') ?></th>
                                            <th><?= lang('tags') ?></th>
                                            <?php $show_custom_fields = custom_form_table(11, null);
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
                                            <th class="hidden-print"><?= lang('action') ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <script type="text/javascript">
                                        $(document).ready(function() {
                                            list = base_url + "admin/proposals/proposalsList";
                                            <?php if (admin_head()) { ?>
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
                                                $('.from_reporter').removeAttr("style");
                                            });
                                            $('.from_account li').on('click', function() {
                                                if ($('.to_account').css('display') == 'block') {
                                                    $('.to_account').removeAttr("style");
                                                    $('.from_reporter').removeAttr("style");
                                                    $('.from_account').css('display', 'block');
                                                } else if ($('.from_reporter').css('display') ==
                                                    'block') {
                                                    $('.to_account').removeAttr("style");
                                                    $('.from_reporter').removeAttr("style");
                                                    $('.from_account').css('display', 'block');
                                                } else {
                                                    $('.from_account').css('display', 'block')
                                                }
                                            });

                                            $('.to_account li').on('click', function() {
                                                if ($('.from_account').css('display') == 'block') {
                                                    $('.from_account').removeAttr("style");
                                                    $('.from_reporter').removeAttr("style");
                                                    $('.to_account').css('display', 'block');
                                                } else if ($('.from_reporter').css('display') ==
                                                    'block') {
                                                    $('.from_reporter').removeAttr("style");
                                                    $('.from_account').removeAttr("style");
                                                    $('.to_account').css('display', 'block');
                                                } else {
                                                    $('.to_account').css('display', 'block');
                                                }
                                            });
                                            $('.from_reporter li').on('click', function() {
                                                if ($('.to_account').css('display') == 'block') {
                                                    $('.to_account').removeAttr("style");
                                                    $('.to_account').removeAttr("style");
                                                    $('.from_reporter').css('display', 'block');
                                                } else if ($('.from_account').css('display') ==
                                                    'block') {
                                                    $('.to_account').removeAttr("style");
                                                    $('.from_account').removeAttr("style");
                                                    $('.from_reporter').css('display', 'block');
                                                } else {
                                                    $('.from_reporter').css('display', 'block');
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
                                                table_url(base_url + "admin/proposals/proposalsList/" +
                                                    filter_by + search_type);
                                            });
                                            <?php } ?>

                                            $('.filter_by_type').on('click', function() {
                                                $('.filter_by_type').removeClass('active');
                                                $('#showed_result').html($(this).attr('search-type'));
                                                $(this).addClass('active');
                                                var filter_by = $(this).attr('id');
                                                if (filter_by) {
                                                    filter_by = filter_by;
                                                } else {
                                                    filter_by = '';
                                                }
                                                table_url(base_url + "admin/proposals/proposalsList/" +
                                                    filter_by);
                                            });
                                        });
                                        </script>
                                    </tbody>
                                </table>
                            </div>
                        </div>

</form>

</div>
<script type="text/javascript">
function slideToggle($id) {
    $('#quick_state').attr('data-original-title', '<?= lang('view_quick_state') ?>');
    $($id).slideToggle("slow");
}

$(document).ready(function() {
    init_items_sortable();
});
</script>
<script>
$(document).ready(function() {
    ins_data(base_url + 'admin/proposals/proposals_state_report')
});
</script>