<style>

    .attended {
        transform: rotate(-90deg) !important;
        -webkit-transform: rotate(-90deg) !important;
        -moz-transform: rotate(-90deg) !important;
        -o-transform: rotate(-90deg) !important;
        -ms-transform: rotate(-90deg) !important;
    }

    th.attended {
        padding: 0 !important;
        vertical-align: initial !important;

    }
</style>

<style type="text/css" media="print">
    @media print {
        @page {
            size: landscape
        }
    }


</style>
<?php $this->load->view('admin/attendance/attendance_report_search'); ?>

<div id="EmpprintReport">
    <?php if (!empty($attendance)):
        if ($search_type == 'month') {
            $month = jdate('F Y', strtotime($date));
        } else {
            $month = jdate('F d, Y', strtotime($start_date)) . ' - ' . jdate('F d, Y', strtotime($end_date));
            $date = $start_date . '/' . $end_date;
        }
        
        ?>
        
        <div class="show_print" hidden style="background-color: rgb(224, 224, 224);margin-bottom: 5px;padding: 5px;">
            <table style="margin: 3px 10px 0px 24px; width:100%;">
                <tr>
                    <td style="font-size: 15px"><strong><?= lang('department') ?>
                            : </strong><?php
                        
                        if (!empty($dept_name->deptname)) {
                            echo $dept_name->deptname;
                        } else {
                            echo lang('all') . ' ' . lang('departments');
                        }
                        ?>
                    </td>
                    <td style="font-size: 15px"><strong><?= lang('date') ?> :</strong><?php echo $month ?></td>
                    <td style="font-size: 15px"><strong><?= lang('total') . ' ' . lang('days') ?>
                            :</strong><?php echo $total_days ?></td>
                    <td style="font-size: 15px"><strong><?= lang('working_days') ?> :</strong><?php echo
                            $total_days - $total_holidays ?></td>
                    <td style="font-size: 15px"><strong><?= lang('holidays') ?> :</strong><?php echo $total_holiday ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row">
            <div class="col-sm-12 std_print">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <strong><?= lang('attendance_list') . ' ' . lang('of') . ' ' . $month; ?> </strong>
                            <div class="pull-right hidden-print">
                                <a href="<?= base_url() ?>admin/attendance/attendance_report/<?= $departments_id . '/' . $search_type . '/' . $date; ?>/pdf"
                                   class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top"
                                   title="<?= lang('pdf') ?>"><span><i class="fa fa-file-pdf-o"></i></span></a>
                                <a href="" onclick="printEmp_report('EmpprintReport')" class="btn btn-danger btn-xs"
                                   data-toggle="tooltip" data-placement="top" title="<?= lang('print') ?>"><span><i
                                                class="fa fa-print"></i></span></a>
                            </div>
                        </h3>
                    </div>
                    <table id="" class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th style="width: 100%" class="col-sm-3"><?= lang('name') ?></th>
                            <?php foreach ($dateSl as $edate) : ?>
                                <th class="std_p"><?php echo $edate ?></th>
                            <?php endforeach; ?>
                            <th class="attended"><?php echo lang('attended') ?></th>
                            <th class="attended"><?php echo lang('attended') . ' %' ?></th>
                        </tr>
                        
                        </thead>
                        
                        <tbody>
                        <?php
                        // get total attendence by date
                        $total_attendence = [];
                        foreach ($attendance as $key => $v_employee) { ?>
                            <tr>
                                <td style="width: 100%"
                                    class="col-sm-3"><strong><?php echo $employee[$key]->fullname ?></strong></td>
                                <?php
                                $total_attend = 0;
                                foreach ($v_employee as $v_result) {
                                    ?>
                                    <?php foreach ($v_result as $emp_attendance) { ?>
                                        <td>
                                            <?php
                                            if ($emp_attendance->attendance_status == 1) {
                                                $total_attendence[$emp_attendance->date_in][] += 1;
                                                $total_attend += 1;
                                                echo '<span  style="padding:2px; 4px" class="label label-success std_p">' . lang('p') . '</span>';
                                            }
                                            if ($emp_attendance->attendance_status == '0') {
                                                echo '<span style="padding:2px; 4px" class="label label-danger std_p">' . lang('a') . '</span>';
                                            }
                                            if ($emp_attendance->attendance_status == 'H') {
                                                echo '<span style="padding:2px; 4px" class="label label-info std_p">' . lang('h') . '</span>';
                                            }
                                            if ($emp_attendance->attendance_status == 3) {
                                                echo '<span style="padding:2px; 4px" class="label label-warning std_p">' . lang('l') . '</span>';
                                            }
                                            ?>
                                        </td>
                                    <?php }; ?>
                                
                                
                                <?php }; ?>
                                <td><?php echo $total_attend ?></td>
                                <td><?php
                                    if (!empty($total_attend)) {
                                        $total_working_days = count($dateSl);
                                        $total_attend = ($total_attend / $total_working_days) * 100;
                                        echo round($total_attend) . '%';
                                    }
                                    ?></td>
                            </tr>
                        <?php };
                        // sort array by key
                        ksort($total_attendence);
                        ?>
                        
                        <tr>
                            <td style="width: 100%" class="col-sm-3"><?php echo lang('attended') ?></td>
                            <?php foreach ($dates as $edate) : ?>
                                <td>
                                    <?php
                                    echo !empty($total_attendence[$edate]) ? count($total_attendence[$edate]) : 0;
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <td style="width: 100%" class="col-sm-3"><?php echo lang('attended') . '%' ?></td>
                            <?php foreach ($dates as $edate) : ?>
                                <td>
                                    <?php
                                    if (!empty($total_attendence[$edate])) {
                                        $total_attendence[$edate] = count($total_attendence[$edate]);
                                        $total_attendence[$edate] = ($total_attendence[$edate] / count($employee)) * 100;
                                        echo round($total_attendence[$edate]) . '%';
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    function printEmp_report(EmpprintReport) {
        var printContents = document.getElementById(EmpprintReport).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
