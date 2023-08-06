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
                <div class="panel-heading" style="">
                    <h4 class="panel-title"><strong><?= lang('works_hours_deatils') . ' ' ?><?php echo $month; ?></strong>
                        <div class="show_print">
                            <?= lang('department') . ' : ' . $dept_name->deptname ?>
                        </div>
                        <div class="pull-right hidden-print">
                            <a href="<?= base_url() ?>admin/attendance/attendance_pdf/4/<?= $departments_id . '/' . $date; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="<?= lang('pdf') ?>"><span><i class="fa fa-file-pdf-o"></i></span></a>
                            <a href="" onclick="printEmp_report('printableArea')" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="<?= lang('print') ?>"><span><i class="fa fa-print"></i></span></a>
                        </div>
                    </h4>
                </div>
                <div class="panel-body" id="accordion" style="margin:8px 5px" role="tablist" aria-multiselectable="true">
                    <table class="table table-striped table-bordered" id="Transation_DataTables">
                        <thead>
                            <tr>
                                <th style="background-color: #eef5f7;"><?= lang('date') ?></th>
                                <th><?= lang('time') ?></th>
                                <th><?= lang('overtime') ?></th>
                                <th><?= lang('total_time') ?></th>
                                <th><?= lang('location') ?></th>
                                <th style="background-color: #eef5f7;"><?= lang('date') ?></th>
                                <th><?= lang('time') ?></th>
                                <th><?= lang('overtime') ?></th>
                                <th><?= lang('total_time') ?></th>
                                <th><?= lang('location') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalSeconds = 0;
                            foreach ($attendace_info as $name => $v_attendace_info) {
                                $total_hours = 0;
                                $total_minutes = 0;
                                $total_seconds = 0;
                            ?>
                                <tr>
                                    <th class="bg-info" colspan="12"><?= $users[$name]->fullname; ?></th>
                                    <?php
                                    $attendace_info_chunk = array_chunk($v_attendace_info, 2, true);
                                    foreach ($attendace_info_chunk as $attendace_row) { ?>
                                <tr>
                                    <?php foreach ($attendace_row as $date => $attendaceInfo) {
                                            // $total_hh = 0;
                                            // $total_mm = 0;
                                            $location = '';
                                            foreach ($attendaceInfo as  $attendace) {
                                                if ($attendace->attendance_status == 1 && !empty($attendace->clockout_time)) {
                                                    // calculate the start timestamp
                                                    $startdatetime = strtotime($attendace->date_in . " " . $attendace->clockin_time);
                                                    // calculate the end timestamp
                                                    $enddatetime = strtotime($attendace->date_out . " " . $attendace->clockout_time);
                                                    // calulate the difference in seconds
                                                    $difference = $enddatetime - $startdatetime;

                                                    $total_seconds += $difference;
                                                    $location = $attendace->location;
                                                }
                                            } ?>

                                        <td style="background-color: #eef5f7;"><?= $date; ?></td>
                                        <td><?php
                                            echo $this->attendance_model->get_time_spent_pain_result($total_seconds); ?></td>
                                        <td><?php
                                            $totalOvertime = $attendaceInfo['total_overtime'];
                                            echo $this->attendance_model->get_time_spent_pain_result($totalOvertime); ?></td>
                                        <td><?php
                                            $total_time = $total_seconds + $totalOvertime;
                                            echo $this->attendance_model->get_time_spent_pain_result($total_time); ?>
                                        </td>
                                        <td><?= $location ?></td>
                                    <?php } ?>
                                </tr>
                            <?php

                                    }

                            ?>
                            </tr>



                        <?php
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