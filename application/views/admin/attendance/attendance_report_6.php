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
<?php $this->load->view('admin/attendance/attendance_report_search'); ?>
<?php
if ((!empty($date)) && !empty($attendace_info)) : ?>
    <div class="" id="printableArea">
        <div class="col-sm-12 std_print">
            <div class="panel panel-custom ">
                <div class="panel-heading">
                    <h4 class="panel-title"><strong><?= lang('works_hours_deatils') . ' ' ?><?php echo $month; ?></strong>
                        <div class="show_print">
                            <?= lang('department') . ' : ' . $dept_name->deptname ?>
                        </div>
                        <div class="pull-right hidden-print">
                            <a href="<?= base_url() ?>admin/attendance/attendance_pdf/6/<?= $departments_id . '/' . $date; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="<?= lang('pdf') ?>"><span><i class="fa fa-file-pdf-o"></i></span></a>
                            <a href="" onclick="printEmp_report('printableArea')" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="<?= lang('print') ?>"><span><i class="fa fa-print"></i></span></a>
                        </div>
                    </h4>
                </div>
                <div class="panel-body" id="accordion" style="margin:8px 5px" role="tablist" aria-multiselectable="true">
                    <table class="table table-striped table-bordered" id="Transation_DataTables">
                        <thead>
                            <tr>
                                <th><?= lang('employee') ?></th>
                                <th><?= lang('time_in') ?></th>
                                <th><?= lang('time_out') ?></th>
                                <th><?= lang('total_hour') ?></th>
                                <th><?= lang('overtime') ?></th>
                                <!-- <th><?= lang('total_time') ?></th> -->
                                <th><?= lang('date') ?></th>
                                <th><?= lang('location') ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $totalSeconds = 0;
                            foreach ($attendace_info as $date => $v_attendace_info) {
                                $total_hours = 0;
                                $total_minutes = 0;
                                foreach ($v_attendace_info as $name => $attendaceInfo) {
                                    $total_seconds = 0;
                                    $first_clock_in = '';
                                    $last_clock_out = '';
                                    $location = '';
                                    foreach ($attendaceInfo as  $attendace) {
                                        if ($attendace->attendance_status == 1 && !empty($attendace->clockout_time)) {
                                            // get first object and last object of attendance info
                                            $first_clock_in = $attendaceInfo[0]->clockin_time;
                                            $last_clock_out = $attendace->clockout_time;

                                            // calculate the start timestamp
                                            $startdatetime = strtotime($attendace->date_in . " " . $attendace->clockin_time);
                                            // calculate the end timestamp
                                            $enddatetime = strtotime($attendace->date_out . " " . $attendace->clockout_time);
                                            // calulate the difference in seconds
                                            $difference = $enddatetime - $startdatetime;

                                            $total_seconds += $difference;
                                            $location = $attendace->location;
                                        }
                                    }
                                    $all_info[$name]['clock_in'] = $first_clock_in;
                                    $all_info[$name]['clock_out'] = $last_clock_out;
                                    $all_info[$name]['total_hour'] = $total_seconds;
                                    $all_info[$name]['date'] = $date;
                                    $all_info[$name]['location'] = $location;
                                    $all_info[$name]['overtime'] = $attendaceInfo['total_overtime'];
                                }
                                if (!empty($all_info)) {
                                    foreach ($all_info as $name => $v_all_info) {
                                        if (!empty($v_all_info['clock_in']) && !empty($v_all_info['clock_out'])) {
                            ?>
                                            <tr>
                                                <td><?php echo $name; ?></td>
                                                <td><?php echo display_time($v_all_info['clock_in']); ?></td>
                                                <td><?php echo display_time($v_all_info['clock_out']); ?></td>
                                                <td><?= $this->attendance_model->get_time_spent_pain_result($v_all_info['total_hour']); ?></td>
                                                <td><?= $this->attendance_model->get_time_spent_pain_result($v_all_info['overtime']); ?></td>
                                                <!-- <td><?= $this->attendance_model->get_time_spent_pain_result($v_all_info['total_hour'] + $v_all_info['overtime']); ?></td> -->
                                                <td><?php echo $v_all_info['date']; ?></td>
                                                <td><?php echo $v_all_info['location']; ?></td>
                                            
                                            </tr>
                            <?php
                                        }
                                    }
                                }
                            }

                            ?>

                        </tbody>
                    </table>
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