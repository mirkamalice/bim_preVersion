<!DOCTYPE html>
<html>

<head>
    <title><?php
            if (!empty($title)) {
                echo $title;
            } else {
                config_item('company_name');
            }
            ?></title>
    <?php
    $direction = $this->session->userdata('direction');
    if (!empty($direction) && $direction == 'rtl') {
        $RTL = 'on';
    } else {
        $RTL = config_item('RTL');
    }
    ?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
        .table_tr1 {
            width: 100%;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        .table_tr1 .th {
            border-bottom: 1px solid #aaaaaa;
            background-color: #dddddd;
            font-size: 12px;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        .table_tr2 th,
        .table_tr3 th,
        .table_tr1 .th,
        .table_tr3 td {
            padding: 3px 0px 3px 5px;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        .table_tr3 th {
            border-bottom: 1px solid #aaaaaa;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        .table_tr3 td {
            border-bottom: 1px solid #dad3d3;
            font-size: 12px;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        .table_tr3 .td {
            font-size: 13px;
            background: #dee0e4;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } ?>
        }

        .th3 {
            font-size: 13px;
            <?php if (!empty($RTL)) { ?>text-align: right;
            <?php } else { ?>text-align: left;
            <?php } ?>
        }
    </style>
</head>

<body style="min-width: 100%; min-height: 100%; ; alignment-adjust: central;">
    <br />
    <?php
    $img = ROOTPATH . '/' . config_item('company_logo');
    $a = file_exists($img);
    if (empty($a)) {
        $img = base_url() . config_item('company_logo');
    }
    if (!file_exists($img)) {
        $img = ROOTPATH . '/' . 'uploads/default_logo.png';
    }
    ?>
    <div style="width: 100%; border-bottom: 2px solid black;">
        <table style="width: 100%; vertical-align: middle;">
            <tr>
                <td style="width: 50px; border: 0px;">
                    <img style="width: 50px;height: 50px;margin-bottom: 5px;" src="<?= $img ?>" alt="" class="img-circle" />
                </td>

                <td style="border: 0px;">
                    <p style="margin-left: 10px; font: 14px lighter;"><?= config_item('company_name') ?></p>
                </td>
            </tr>
        </table>
    </div>
    <br />
    <div style="width: 100%;">
        <div style="width: 100%; background-color: rgb(224, 224, 224); padding: 1px 0px 5px 15px;">
            <table style="width: 100%;">
                <tr style="font-size: 20px;  text-align: center">
                    <td style="padding: 10px;">
                        <strong><?= lang('works_hours_deatils') . ' ' ?><?php echo $month; ?></strong>
                        <p><strong><?= lang('department') . ' : ' . $dept_name->deptname ?></strong></p>
                    </td>
                </tr>
            </table>
        </div>
        <br />
        <?php if (!empty($attendace_info)) { ?>
            <table class="table_tr3" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="th3"><?= lang('employee') ?></th>
                        <th class="th3"><?= lang('time_in') ?></th>
                        <th class="th3"><?= lang('time_out') ?></th>
                        <th class="th3"><?= lang('total_hour') ?></th>
                        <th class="th3"><?= lang('overtime') ?></th>
                        <!-- <th class="th3"><?= lang('total_time') ?></th> -->
                        <th class="th3"><?= lang('date') ?></th>
                        <th class="th3"><?= lang('location') ?></th>
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
        <?php }; ?>
    </div>

</body>

</html>