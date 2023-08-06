<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$all_customer_group = $this->db->where('type', 'client')->order_by('customer_group_id', 'DESC')->get('tbl_customer_group')->result();
$mdate = jdate('Y-m-d');
$last_7_days = jdate('Y-m-d', strtotime('today - 7 days'));
$all_goal_tracking = $this->client_model->get_permission('tbl_goal_tracking');

$all_goal = 0;
$wthout_all_goal = 0;
$direct_complete_achivement = 0;
$without_complete_achivement = 0;
if (!empty($all_goal_tracking)) {
    foreach ($all_goal_tracking as $v_goal_track) {
        $goal_achieve = $this->client_model->get_progress($v_goal_track, true);

        if ($v_goal_track->goal_type_id == 11) {

            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') { // check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') { // check is notify is checked or not check
                            $this->client_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') { // check is notify is checked or not check
                            $this->client_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                        }
                    }
                }
            }
            $all_goal += $v_goal_track->achievement;
            $direct_complete_achivement += $goal_achieve['achievement'];
        }
        if ($v_goal_track->goal_type_id == 10) {

            if ($v_goal_track->end_date <= $mdate) { // check today is last date or not

                if ($v_goal_track->email_send == 'no') { // check mail are send or not
                    if ($v_goal_track->achievement <= $goal_achieve['achievement']) {
                        if ($v_goal_track->notify_goal_achive == 'on') { // check is notify is checked or not check
                            $this->client_model->send_goal_mail('goal_achieve', $v_goal_track);
                        }
                    } else {
                        if ($v_goal_track->notify_goal_not_achive == 'on') { // check is notify is checked or not check
                            $this->client_model->send_goal_mail('goal_not_achieve', $v_goal_track);
                        }
                    }
                }
            }
            $wthout_all_goal += $v_goal_track->achievement;
            $without_complete_achivement += $goal_achieve['achievement'];
        }
    }
}
// 30 days before

for ($iDay = 7; $iDay >= 0; $iDay--) {
    $date = jdate('Y-m-d', strtotime('today - ' . $iDay . 'days'));
    $where = array('date_added >=' => $date . " 00:00:00", 'date_added <=' => $date . " 23:59:59");
    $invoice_result[$date] = count($this->db->where($where)->get('tbl_client')->result());
}

$all_terget_achievement = $this->db->where(array('goal_type_id' => 11, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate))->get('tbl_goal_tracking')->result();
$without_terget_achievement = $this->db->where(array('goal_type_id' => 10, 'start_date >=' => $last_7_days, 'end_date <=' => $mdate))->get('tbl_goal_tracking')->result();
if (!empty($all_terget_achievement)) {
    $all_terget_achievement = $all_terget_achievement;
} else {
    $all_terget_achievement = array();
}
if (!empty($without_terget_achievement)) {
    $without_terget_achievement = $without_terget_achievement;
} else {
    $without_terget_achievement = array();
}
$terget_achievement = array_merge($all_terget_achievement, $without_terget_achievement);
$total_terget = 0;
if (!empty($terget_achievement)) {
    foreach ($terget_achievement as $v_terget) {
        $total_terget += $v_terget->achievement;
    }
}

$curency = $this->client_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

if ($this->session->userdata('user_type') == 1) {
    $margin = 'margin-bottom:15px';
    ?>
    <div class="col-sm-12 bg-white p0 report_menu" style="<?= $margin ?>">
        <div class="col-md-4">
            <div class="row row-table pv-lg">
                <div class="col-xs-6">
                    <p class="m0 lead"><?= ($all_goal) ?></p>
                    <p class="m0">
                        <small><?= lang('without_converted') ?></small>
                    </p>
                </div>
                <div class="col-xs-6">
                    <p class="m0 lead"><?= ($direct_complete_achivement) ?></p>
                    <p class="m0">
                        <small><?= lang('completed') . ' ' . lang('achievements') ?></small>
                    </p>
                </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="row row-table pv-lg">

                <div class="col-xs-6 ">
                    <p class="m0 lead"><?= ($wthout_all_goal) ?></p>
                    <p class="m0">
                        <small><?= lang('converted_client') ?></small>
                    </p>
                </div>
                <div class="col-xs-6">
                    <p class="m0 lead">

                        <?= $without_complete_achivement ?></p>
                    <p class="m0">
                        <small><?= lang('completed') . ' ' . lang('achievements') ?></small>
                    </p>
                </div>

            </div>

        </div>
        <div class="col-md-4">
            <div class="row row-table ">

                <div class="col-xs-6 pt">
                    <div data-sparkline="" data-bar-color="#23b7e5" data-height="60" data-bar-width="8"
                         data-bar-spacing="6"
                         data-chart-range-min="0"
                         values="<?php
                         if (!empty($invoice_result)) {
                             foreach ($invoice_result as $v_invoice_result) {
                                 echo $v_invoice_result . ',';
                             }
                         }
                         ?>">
                    </div>
                    <p class="m0">
                        <small>
                            <?php
                            if (!empty($invoice_result)) {
                                foreach ($invoice_result as $date => $v_invoice_result) {
                                    echo jdate('d', strtotime($date)) . ' ';
                                }
                            }
                            ?>
                        </small>
                    </p>

                </div>
                <?php
                $total_goal = $all_goal + $wthout_all_goal;
                $complete_achivement = $direct_complete_achivement + $without_complete_achivement;
                if (!empty($tolal_goal)) {
                    if ($tolal_goal <= $complete_achivement) {
                        $total_progress = 100;
                    } else {
                        $progress = ($complete_achivement / $tolal_goal) * 100;
                        $total_progress = round($progress);
                    }
                } else {
                    $total_progress = 0;
                }
                ?>
                <div class="col-xs-6 text-center pt">
                    <div class="inline ">
                        <div class="easypiechart text-success" data-percent="<?= $total_progress ?>" data-line-width="5"
                             data-track-Color="#f0f0f0"
                             data-bar-color="#<?php
                             if ($total_progress == 100) {
                                 echo '8ec165';
                             } elseif ($total_progress >= 40 && $total_progress <= 50) {
                                 echo '5d9cec';
                             } elseif ($total_progress >= 51 && $total_progress <= 99) {
                                 echo '7266ba';
                             } else {
                                 echo 'fb6b5b';
                             }
                             ?>"
                             data-rotate="270" data-scale-Color="false" data-size="50" data-animate="2000">
                        <span class="small "><?= $total_progress ?>
                            %</span>
                            <span class="easypie-text"><strong><?= lang('done') ?></strong></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php }

$id = $this->uri->segment(5);
$search_by = $this->uri->segment(4);
$created = can_action('4', 'created');
$edited = can_action('4', 'edited');
$deleted = can_action('4', 'deleted');
?>
<div class="row">
    <div class="col-sm-12">
        <?php
        $is_department_head = is_department_head();
        if ($this->session->userdata('user_type') == 1 || !empty($is_department_head)) { ?>
            <div class="btn-group pull-right btn-with-tooltip-group _filter_data filtered" data-toggle="tooltip"
                 data-title="<?php echo lang('filter_by'); ?>">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-left group" style="width:300px;">
                    <li class="filter_by"><a href="#"><?php echo lang('all'); ?></a></li>
                    <li class="divider"></li>
                    <?php if (count(array($all_customer_group)) > 0) { ?>
                        <?php foreach ($all_customer_group as $group) {
                            ?>
                            <li class="filter_by" id="<?= $group->customer_group_id ?>">
                                <a href="#"><?php echo $group->customer_group; ?></a>
                            </li>
                        <?php }
                        ?>
                        <div class="clearfix"></div>
                    <?php } ?>
                </ul>
            </div>
            <?php
        }
        if (!empty($created) || !empty($edited)) { ?>
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : '' ?>"><a
                            href="<?= base_url('admin/client/manage_client') ?>"><?= lang('client_list') ?></a></li>
                <li class="<?= $active == 2 ? 'active' : '' ?>"><a
                            href="<?= base_url('admin/client/create_client') ?>"><?= lang('new_client') ?></a></li>
                <li><a class="import"
                       href="<?= base_url() ?>admin/client/import"><?= lang('import') . ' ' . lang('client') ?></a>
                </li>
            </ul>
            <style type="text/css">
                .custom-bulk-button {
                    display: initial;
                }
            </style>
            <div class="tab-content bg-white">
                <!-- Stock Category List tab Starts -->
                <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="client_list" style="position: relative;">
                    <?php } else { ?>
                    <div class="panel panel-custom">
                        <header class="panel-heading ">
                            <div class="panel-title"><strong><?= lang('client_list') ?></strong></div>
                        </header>
                        <?php } ?>
                        <div class="box">
                            <table class="table table-striped DataTables bulk_table " id="DataTables" cellspacing="0"
                                   width="100%">
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
                                    <th><?= lang('name') ?> </th>
                                    <th><?= lang('contacts') ?></th>
                                    <th class="hidden-sm"><?= lang('primary_contact') ?></th>
                                    <th><?= lang('projects') ?> </th>
                                    <th><?= lang('due_amount') ?> </th>
                                    <th><?= lang('received_amount') ?> </th>
                                    <th><?= lang('expense') ?> </th>
                                    <th><?= lang('group') ?> </th>
                                    <?php $show_custom_fields = custom_form_table(12, null);
                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($c_label)) {
                                                ?>
                                                <th><?= $c_label ?> </th>
                                            <?php }
                                        }
                                    }
                                    ?>
                                    <th class="hidden-print"><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        list = base_url + "admin/client/clientList";
                                        bulk_url = base_url + 'admin/client/bulk_delete';
                                        $('.filtered > .dropdown-toggle').on('click', function () {
                                            if ($('.group').css('display') == 'block') {
                                                $('.group').css('display', 'none');
                                            } else {
                                                $('.group').css('display', 'block')
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
                                            table_url(base_url + "admin/client/clientList/" +
                                                filter_by);
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
</div>
<script type="text/javascript">
    function fetch_lat_long_from_google_cprofile() {
        var data = {};
        data.address = $('textarea[name="address"]').val();
        data.city = $('input[name="city"]').val();
        data.country = $('select[name="country"] option:selected').text();
        console.log(data);
        $('#gmaps-search-icon').removeClass('fa-google').addClass('fa-spinner fa-spin');
        $.post('<?= base_url() ?>admin/common/fetch_address_info_gmaps', data).done(function (data) {
            data = JSON.parse(data);
            $('#gmaps-search-icon').removeClass('fa-spinner fa-spin').addClass('fa-google');
            if (data.response.status == 'OK') {
                $('input[name="latitude"]').val(data.lat);
                $('input[name="longitude"]').val(data.lng);
            } else {
                if (data.response.status == 'ZERO_RESULTS') {
                    toastr.warning("<?php echo lang('g_search_address_not_found'); ?>");
                } else {
                    toastr.warning(data.response.status);
                }
            }
        });
    }
</script>