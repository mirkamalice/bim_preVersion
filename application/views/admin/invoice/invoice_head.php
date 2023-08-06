<form name="myform" role="form" data-parsley-validate="" novalidate="" enctype="multipart/form-data" id="form"
    action="<?php echo base_url(); ?>admin/invoice/save_invoice/<?php
                                                                                                                                                                            if (!empty($invoice_info)) {
                                                                                                                                                                                echo $invoice_info->invoices_id;
                                                                                                                                                                            }
                                                                                                                                                                            ?>" method="post" class="form-horizontal  ">
    <div class="<?php if (!isset($invoice_info) || (isset($invoice_info) && !empty($invoices_to_merge) && count(array($invoices_to_merge)) == 0)) {
                    echo ' hide';
                } ?>" id="invoice_top_info">
        <div class="panel-body">
            <div class="row">
                <div id="merge" class="col-md-8">
                    <?php if (isset($invoice_info) && !empty($invoices_to_merge)) {
                        $this->load->view('admin/invoice/merge_invoice', array('invoices_to_merge' => $invoices_to_merge));
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
    <?php include_once 'assets/admin-ajax.php'; ?>
    <?php include_once 'assets/js/sales.php'; ?>
    <?php echo message_box('success'); ?>
    <?php echo message_box('error'); ?>

    <div id="invoice_state_report_div">

        <?php //$this->load->view("admin/invoice/invo_data_2"); 
        ?>

    </div>

    <?php
    $type = $this->uri->segment(5);
    if (!empty($type) && !is_numeric($type)) {
        $ex = explode('_', $type);
        if ($ex[0] == 'c') {
            $c_id = $ex[1];
            $type = '_' . date('Y');
        }
    }
    if (empty($type)) {
        $type = '_' . date('Y');
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
            <li><a id="all" search-type="<?= ('all'); ?>" class="filter_by_type" href="#"><?= lang('all'); ?></a></li>
            <?php
            $invoiceFilter = $this->invoice_model->get_invoice_filter();
            if (!empty($invoiceFilter)) {
                foreach ($invoiceFilter as $v_Filter) {
            ?>
            <li class="filter_by_type" search-type="<?= $v_Filter['name'] ?>" id="<?= $v_Filter['value'] ?>"
                <?php if ($v_Filter['value'] == $type) {
                                                                                                                            echo 'class="active"';
                                                                                                                        } ?>>
                <a href="#"><?= $v_Filter['name'] ?></a>
            </li>
            <?php
                }
            }
            ?>
        </ul>
    </div>
    <?php
    $h_s = config_item('invoice_state');
    if ($this->session->userdata('user_type') == 1) {
        if ($h_s == 'block') { ?>
    <!--<script>
            $(document).ready(function () { ins_data(base_url+'admin/invoice/invoice_state_report'); });
            </script>-->
    <?php

            $title = lang('hide_quick_state');
            $url = 'hide';
            $icon = 'fa fa-eye-slash';
        } else {
            $title = lang('view_quick_state');
            $url = 'show';
            $icon = 'fa fa-eye';
        ?>
    <!--    <script>
                    $(document).ready(function () {  $("#quick_state").on("click", function(){
                        if($('#state_report').length){ } else{
                        ins_data(base_url+'admin/invoice/invoice_state_report');}
                    }); });
                </script>-->
    <?php
        }
        ?>
    <div onclick="slideToggle('#state_report')" id="quick_state" data-toggle="tooltip" data-placement="top"
        title="<?= $title ?>" class="btn-xs btn btn-purple pull-left">
        <i class="fa fa-bar-chart"></i>
    </div>
    <div class="btn-xs btn btn-white pull-left ml ">
        <a class="text-dark" id="change_report" href="<?= base_url() ?>admin/dashboard/change_report/<?= $url ?>"><i
                class="<?= $icon ?>"></i>
            <span><?= ' ' . lang('quick_state') . ' ' . lang($url) . ' ' . lang('always') ?></span></a>
    </div>
    <?php
    }
    $created = can_action('13', 'created');
    $edited = can_action('13', 'edited');
    $deleted = can_action('13', 'deleted');
    if (!empty($created) || !empty($edited)) {
    ?>
    <a data-toggle="modal" data-target="#myModal" href="<?= base_url() ?>admin/invoice/zipped/invoice"
        class="btn btn-success btn-xs ml-lg"><?= lang('zip_invoice') ?></a>

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
                    <li class="filter_by all_filter" id="all"><a href="#"><?php echo lang('all'); ?></a></li>
                    <li class="divider"></li>
                    <li class="dropdown-submenu pull-left  " id="from_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('project'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left from_account" style="">
                            <?php
                                    $all_project = $this->invoice_model->get_permission('tbl_project');
                                    if (!empty($all_project)) {
                                        foreach ($all_project as $v_project) {
                                    ?>
                            <li class="filter_by" id="<?= $v_project->project_id ?>" search-type="by_project">
                                <a href="#"><?php echo $v_project->project_name; ?></a>
                            </li>
                            <?php }
                                    }
                                    ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left  " id="from_reporter">
                        <a href="#"
                            tabindex="-1"><?php echo lang('by') . ' ' . lang('sales') . ' ' . lang('agent'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left from_reporter" style="">
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
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left " id="to_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('client'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left to_account" style="">
                            <?php
                                    if (count(array($all_client)) > 0) { ?>
                            <?php foreach ($all_client as $v_client) {
                                        ?>
                            <li class="filter_by" id="<?= $v_client->client_id ?>" search-type="by_client">
                                <a href="#"><?php echo $v_client->name; ?></a>
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