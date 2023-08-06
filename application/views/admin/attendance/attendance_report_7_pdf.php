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
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        .table_tr1 .th {
            border: 1px solid #aaaaaa;
            background-color: #dddddd;
            font-size: 12px;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        .table_tr2 th, .table_tr3 th, .table_tr1 .th, .table_tr3 td {
            padding: 3px 0px 3px 5px;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        .table_tr3 th {
            border-bottom: 1px solid #aaaaaa;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>

        }

        .table_tr3 td {
            border-bottom: 1px solid #dad3d3;
            font-size: 12px;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        .table_tr3 .td {
            font-size: 13px;
            background: #dee0e4;
        <?php if(!empty($RTL)){?> text-align: right;
        <?php }?>
        }

        .th1 {
            text-align: center;
            border: 1px solid #aaaaaa;

        }
    </style>
</head>
<body style="min-width: 100%; min-height: 100%; ; alignment-adjust: central;">
<br/>
<?php
$img = ROOTPATH . '/' . config_item('company_logo');
$a = file_exists($img);
if (empty($a)) {
    $img = base_url() . config_item('company_logo');
}
if (!file_exists($img)) {
    $img = ROOTPATH . '/' . 'uploads/default_logo.png';
}

if ($search_type == 'month') {
    $month = jdate('F Y', strtotime($date));
} else {
    $month = jdate('F d, Y', strtotime($start_date)) . ' - ' . jdate('F d, Y', strtotime($end_date));
    $date = $start_date . '/' . $end_date;
}
?>
<div style="width: 100%; border-bottom: 2px solid black;">
    <table style="width: 100%; vertical-align: middle;">
        <tr>
            <td style="width: 50px; border: 0px;">
                <img style="width: 50px;height: 50px;margin-bottom: 5px;"
                     src="<?= $img ?>" alt="" class="img-circle"/>
            </td>
            
            <td style="border: 0px;">
                <p style="margin-left: 10px; font: 14px lighter;"><?= config_item('company_name') ?></p>
            </td>
        </tr>
    </table>
</div>
<br/>
<div style="width: 100%;">
    <div style="width: 100%; background-color: rgb(224, 224, 224); padding: 1px 0px 5px 15px;">
        <table style="width: 100%;">
            <tr style="font-size: 20px;  text-align: center">
                <td style="padding: 10px;">
                    <strong><?= lang('attendance_list') . ' ' . lang('of') . ' ' ?><?php echo $month; ?></strong>
                    <?php
                    if (!empty($dept_name->deptname)) {
                        ?>
                        <p><strong><?= lang('department') . ' : ' . $dept_name->deptname ?></strong></p>
                    <?php } else {
                        ?>
                        <p><strong><?= lang('department') . ' : ' . lang('all') . ' ' . lang('departments') ?></strong>
                        </p>
                    <?php } ?>
                </td>
                <td style="padding: 10px;">
                    <p><strong><?= lang('total') . ' ' . lang('days') ?></strong>: <?php echo $total_days ?></p>
                    <p><strong><?= lang('working_days') ?></strong>: <?php echo $total_days - $total_holidays ?></p>
                    <p><strong><?= lang('holidays') ?></strong>: <?php echo $total_holidays ?></p>
                </td>
            </tr>
        </table>
    </div>
    <br/>
    
    <table class="table_tr1">
        <tr>
            <th style="width: 20%" class="th"><?= lang('name') ?></th>
            <?php foreach ($dateSl as $edate) : ?>
                <th class="th th1"><?php echo $edate ?></th>
            <?php endforeach; ?>
            <th class="th th1"><?php echo lang('attended') ?></th>
            <th class="th th1"><?php echo lang('attended') . ' %' ?></th>
        </tr>
        <?php
        $total_attendence = [];
        foreach ($attendance as $key => $v_employee) { ?>
            <tr>
                <td style="width: 20%;border: 1px solid #aaaaaa;"><?php echo $employee[$key]->fullname ?></td>
                <?php
                $total_attend = 0;
                foreach ($v_employee as $v_result) {
                    ?>
                    <?php foreach ($v_result as $emp_attendance) { ?>
                        <td class="th1">
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
                <td class="th1"><?php echo $total_attend ?></td>
                <td class="th1"><?php
                    if (!empty($total_attend)) {
                        $total_working_days = count($dateSl);
                        $total_attend = ($total_attend / $total_working_days) * 100;
                        echo round($total_attend) . '%';
                    }
                    ?></td>
            </tr>
        <?php };
        ksort($total_attendence);
        ?>
        <tr>
            <td style="width: 20%;text-align: left" class="th1 th"><?= lang('attended') ?></td>
            <?php foreach ($dates as $edate) : ?>
                <td class="th1 th" style="text-align: left">
                    <?php
                    echo !empty($total_attendence[$edate]) ? count($total_attendence[$edate]) : 0;
                    ?>
                </td>
            <?php endforeach; ?>
        </tr>
        <tr>
            <td style="width: 20%;text-align: left" class="th1 th"><?= lang('attended') . '%' ?></td>
            <?php foreach ($dates as $edate) : ?>
                <td class="th1 th" style="text-align: left">
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
    </table>
</div>
</body>
</html>