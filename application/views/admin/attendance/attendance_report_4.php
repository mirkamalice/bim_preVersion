<style type="text/css" media="print">
    @media print {
        .accordion {
            overflow: visible !important;
        }

        .accordionPanelContent {
            display: block !important;
            overflow: visible !important;
            height: auto !important;
        }

        @page {
            size: landscape
        }

        .panel-collapse,
        .panel-collapse .collapse {
            display: block !important;
            overflow: visible !important;
            height: auto !important;
        }

        .collapse {
            display: block !important;
            overflow: visible !important;
            height: auto !important;
        }

        .bt {
            border-top: 1px solid #eee !important;
        }
    }
</style>
<style type="text/css">
    .panel .table {
        border: 1px !important;
    }
</style>
<?php $this->load->view('admin/attendance/attendance_report_search'); ?>
<?php
if ((!empty($date)) && !empty($attendace_info)) : ?>
    <div class="" id="printableArea">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom ">
                <div class="panel-heading" style="">
                    <h4 class="panel-title"><strong><?= lang('works_hours_deatils') . ' ' ?><?php echo $month; ?></strong>
                        <div class="show_print">
                            <?= lang('department') . ' : ' . $dept_name->deptname ?>
                        </div>
                        <div class="pull-right hidden-print">
                            <!-- <a href="<?= base_url() ?>admin/attendance/attendance_pdf/4/<?= $departments_id . '/' . $date; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="<?= lang('pdf') ?>"><span><i class="fa fa-file-pdf-o"></i></span></a> -->
                            <a href="" onclick="printEmp_report('printableArea')" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="<?= lang('print') ?>"><span><i class="fa fa-print"></i></span></a>
                        </div>
                    </h4>
                </div>
                <div class="panel-group" id="accordion" style="margin:8px 5px" role="tablist" aria-multiselectable="true">
                    <?php
                    foreach ($attendace_info as $name => $v_attendace_info) :
                    ?>
                        <div class="panel panel-default" style="border-radius: 0px ">
                            <div class="panel-heading" style="border-radius: 0px;border: none" role="tab">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $name ?>" aria-expanded="true" aria-controls="collapseOne">
                                        <?php echo $users[$name]->fullname ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="<?php echo $name ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <?php
                                    $attendace_info_chunk = array_chunk($v_attendace_info, 2, true);
                                    foreach ($attendace_info_chunk as $attendace_row) {
                                    ?>
                                        <div class="row">
                                            <?php

                                            foreach ($attendace_row as $date => $v_attendace) {
                                                $subHide = 0;
                                                $total_seconds = 0;
                                            ?>
                                                <div class="col-sm-6 mb">
                                                    <table class="table table-bordered table-hover">
                                                        <thead class="bt">
                                                            <tr>
                                                                <th><?= lang('clock_in_time') ?></th>
                                                                <th><?= lang('clock_out_time') ?></th>
                                                                <th><?= lang('hours') ?></th>
                                                                <th><?= lang('location') ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php
                                                            $total_hh = 0;
                                                            $total_mm = 0;
                                                            if (!empty($v_attendace)) :
                                                                $hourly_leave = null;
                                                                foreach ($v_attendace as $ddd =>  $v_mytime) :

                                                                    // foreach ($v_mytime as $v_mytime) {
                                                                    if ($v_mytime->attendance_status == 1) {
                                                                        if (!empty($v_mytime->leave_application_id)) { // check leave type is hours
                                                                            $is_hours = get_row('tbl_leave_application', array('leave_application_id' => $v_mytime->leave_application_id));
                                                                            if (!empty($is_hours) && $is_hours->leave_type == 'hours') {
                                                                                $hourly_leave = "<small class='label label-pink text-sm' data-toggle='tooltip' data-placement='top'  title='" . lang('hourly') . ' ' . lang('leave') . ": " . $is_hours->hours . ":00" . ' ' . lang('hour') . "'>" . lang('hourly') . ' ' . lang('leave') . "</small>";
                                                                            }
                                                                        }
                                                            ?>
                                                                        <tr>
                                                                            <td><?php echo display_time($v_mytime->clockin_time); ?></td>
                                                                            <td><?php
                                                                                if (empty($v_mytime->clockout_time)) {
                                                                                    echo '<span class="text-danger">' . lang('currently_clock_in') . '<span>';
                                                                                } else {
                                                                                    if (!empty($v_mytime->comments)) {
                                                                                        $comments = ' <small> (' . $v_mytime->comments . ')</small>';
                                                                                    } else {
                                                                                        $comments = '';
                                                                                    }
                                                                                    echo display_time($v_mytime->clockout_time) . $comments;
                                                                                } ?>
                                                                            </td>
                                                                           

                                                                            <td><?php
                                                                                if (!empty($v_mytime->clockout_time)) {
                                                                                    // calculate the start timestamp
                                                                                    $startdatetime = strtotime($v_mytime->date_in . " " . $v_mytime->clockin_time);
                                                                                    // calculate the end timestamp
                                                                                    $enddatetime = strtotime($v_mytime->date_out . " " . $v_mytime->clockout_time);
                                                                                    // calulate the difference in seconds
                                                                                    $difference = $enddatetime - $startdatetime;
                                                                                    $total_seconds += $difference;
                                                                                    echo $this->attendance_model->get_time_spent_pain_result($difference);


                                                                                    // $years = abs(floor($difference / 31536000));
                                                                                    // $days = abs(floor(($difference - ($years * 31536000)) / 86400));
                                                                                    // $hours = abs(floor(($difference - ($years * 31536000) - ($days * 86400)) / 3600));
                                                                                    // $mins = abs(floor(($difference - ($years * 31536000) - ($days * 86400) - ($hours * 3600)) / 60)); #floor($difference / 60);
                                                                                    // $total_mm += $mins;
                                                                                    // $total_hh += $hours;
                                                                                    // echo $hours . " : " . $mins . " m";

                                                                                    // output the result
                                                                                } ?>
                                                                            </td>
                                                                            <td><?php echo $v_mytime->location ?></td>

                                                                            <?php if (!empty($hourly_leave)) { ?>
                                                                        <tr>
                                                                            <td colspan="4" style="background: rgba(233, 237, 228, 0.73);font-weight: bold">
                                                                                <span class="pull-right"><?= $hourly_leave ?></span>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php
                                                                    } elseif ($v_mytime->attendance_status == 'H') {
                                                                        $subHide = 1;
                                                                ?>
                                                                    <tr>
                                                                        <td colspan="4" style="text-align: center">
                                                                            <span style="padding:5px 109px; font-size: 12px;" class="label label-info std_p"><?= lang('holiday') ?></span>
                                                                        </td>
                                                                    </tr>
                                                                <?php } elseif ($v_mytime->attendance_status == '3') {
                                                                        $subHide = 1;
                                                                ?>
                                                                    <tr>
                                                                        <td colspan="4" style="text-align: center">
                                                                            <span style="padding:5px 109px; font-size: 12px;" class="label label-warning std_p"><?= lang('on_leave') ?></span>
                                                                        </td>
                                                                    </tr>
                                                                <?php } elseif ($v_mytime->attendance_status == '0') {
                                                                        $subHide = 1;
                                                                ?>
                                                                    <tr style="">
                                                                        <td colspan="3" style="text-align: center">
                                                                            <span style="padding:5px 109px; font-size: 12px;" class="label label-danger std_p"><?= lang('absent') ?></span>
                                                                        </td>
                                                                    </tr>
                                                                <?php }
                                                                    $total_overtime = $v_attendace['total_overtime'];
                                                                    $totalTime = $total_overtime + $total_seconds;
                                                                ?>
                                                                </tr>
                                                            <?php
                                                                    $hourly_leave = null;
                                                                endforeach;
                                                                if ($totalTime > 0) {
                                                            ?>

                                                                <tr class="text-success">
                                                                    <td class="text-right ">
                                                                        <strong style="margin-right: 10px; "><?= lang('total_working_hour') . ' of ' . $date . ' is ' ?>
                                                                            : </strong>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        echo $this->attendance_model->get_time_spent_pain_result($total_seconds); ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if ($total_overtime > 0) {
                                                                            echo '<span class="text-success">' . lang('overtime') . ' : ';
                                                                            echo $this->attendance_model->get_time_spent_pain_result($total_overtime) . '</span>';
                                                                        }
                                                                        echo '<span class="text-danger pull-right">' . lang('total') . ' : ';
                                                                        echo $this->attendance_model->get_time_spent_pain_result($total_overtime + $total_seconds) . '</span>';

                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                                } else if (empty($subHide)) { ?>
                                                                <tr>
                                                                    <td colspan="4">
                                                                        <?= lang('nothing_to_display') ?>
                                                                    </td>
                                                                </tr>
                                                            <?php }
                                                            ?>

                                                        <?php else : ?>
                                                            <tr>
                                                                <td colspan="6">
                                                                    <?= lang('nothing_to_display') ?>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php
                                    } ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                </div>
            </div>
        <?php endif; ?>
        </div>


        <script type="text/javascript">
            function printEmp_report(printableArea) {
                $('div.wrapper').find('.collapse').css('display', 'block');
                var printContents = document.getElementById(printableArea).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>