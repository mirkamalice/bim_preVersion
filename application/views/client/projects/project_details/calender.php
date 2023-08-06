<?php 

?>

<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('calendar') ?></h3>
    </div>
    <div class="panel-body">
        <div class="">
            <div class="panel-heading mb0" style="border-bottom: 1px solid #D8D8D8"></div>
            <div id="calendar"></div>
        </div>
        <link href="<?php echo base_url() ?>asset/css/fullcalendar.css" rel="stylesheet" type="text/css">
        <style type="text/css">
            .datepicker {
                z-index: 1151 !important;
            }

            .mt-sm {
                font-size: 14px;
            }

            .fc-state-default {
                background: none !important;
                color: inherit !important;
                ;
            }
        </style>
        <?php
        $curency = $this->admin_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
        $gcal_api_key = config_item('gcal_api_key');
        $gcal_id = config_item('gcal_id');
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                if ($('#calendar').length) {
                    var date = new Date();
                    var d = date.getDate();
                    var m = date.getMonth();
                    var y = date.getFullYear();
                    var calendar = $('#calendar').fullCalendar({
                        googleCalendarApiKey: '<?= $gcal_api_key ?>',
                        eventAfterRender: function(event, element, view) {
                            if (event.type == 'fo') {
                                $(element).attr('data-toggle', 'ajaxModal').addClass('ajaxModal');
                            }
                        },
                        header: {
                            center: 'prev title next',
                            left: 'month agendaWeek agendaDay today',
                            right: ''
                        },
                        buttonText: {
                            prev: '<i class="fa fa-angle-left" />',
                            next: '<i class="fa fa-angle-right" />'
                        },
                        selectable: true,
                        selectHelper: true,
                        select: function(start, end, allDay) {
                            var endtime = $.fullCalendar.formatDate(end, 'h:mm tt');
                            var starttime = $.fullCalendar.formatDate(start, 'yyyy/MM/dd');
                            var mywhen = starttime + ' - ' + endtime;
                            $('#event_modal #apptStartTime').val(starttime);
                            $('#event_modal #apptEndTime').val(starttime);
                            $('#event_modal #apptAllDay').val(allDay);
                            $('#event_modal #when').text(mywhen);
                            $('#event_modal').modal('show');
                        },
                        events: [
                            <?php
                            $invoice_info = $this->db->where('project_id', $project_details->project_id)->get('tbl_invoices')->result();
                            if (!empty($invoice_info)) {
                                foreach ($invoice_info as $v_invoice) :
                                    $start_day = date('d', strtotime($v_invoice->due_date));
                                    $smonth = date('n', strtotime($v_invoice->due_date));
                                    $start_month = $smonth - 1;
                                    $start_year = date('Y', strtotime($v_invoice->due_date));
                                    $end_year = date('Y', strtotime($v_invoice->due_date));
                                    $end_day = date('d', strtotime($v_invoice->due_date));
                                    $emonth = date('n', strtotime($v_invoice->due_date));
                                    $end_month = $emonth - 1;
                            ?> {
                                        title: "<?php echo $v_invoice->reference_no ?>",
                                        start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                                        end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                                        color: '<?= config_item('invoice_color') ?>',
                                        url: '<?= base_url() ?>client/invoice/manage_invoice/invoice_details/<?= $v_invoice->invoices_id ?>'
                                    },
                                <?php
                                endforeach;
                            }
                            $estimates_info = $this->db->where('project_id', $project_details->project_id)->get('tbl_estimates')->result();;
                            if (!empty($estimates_info)) {
                                foreach ($estimates_info as $v_estimates) :
                                    $start_day = date('d', strtotime($v_estimates->due_date));
                                    $smonth = date('n', strtotime($v_estimates->due_date));
                                    $start_month = $smonth - 1;
                                    $start_year = date('Y', strtotime($v_estimates->due_date));
                                    $end_year = date('Y', strtotime($v_estimates->due_date));
                                    $end_day = date('d', strtotime($v_estimates->due_date));
                                    $emonth = date('n', strtotime($v_estimates->due_date));
                                    $end_month = $emonth - 1;
                                ?> {
                                        title: "<?php echo $v_estimates->reference_no ?>",
                                        start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                                        end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                                        color: '<?= config_item('estimate_color') ?>',
                                        url: '<?= base_url() ?>client/estimates/index/estimates_details/<?= $v_estimates->estimates_id ?>'
                                    },
                            <?php
                                endforeach;
                            }
                            $start_day = date('d', strtotime($project_details->end_date));
                            $smonth = date('n', strtotime($project_details->end_date));
                            $start_month = $smonth - 1;
                            $start_year = date('Y', strtotime($project_details->end_date));
                            $end_year = date('Y', strtotime($project_details->end_date));
                            $end_day = date('d', strtotime($project_details->end_date));
                            $emonth = date('n', strtotime($project_details->end_date));
                            $end_month = $emonth - 1;
                            ?> {
                                title: "<?php echo $project_details->project_name ?>",
                                start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                                end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                                color: '<?= config_item('project_color') ?>',
                                url: '<?= base_url() ?>client/projects/project_details/<?= $project_details->project_id ?>'
                            },
                            <?php

                            $milestone_info = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_milestones')->result();
                            if (!empty($milestone_info)) {
                                foreach ($milestone_info as $v_milestone) :
                                    $start_day = date('d', strtotime($v_milestone->end_date));
                                    $smonth = date('n', strtotime($v_milestone->end_date));
                                    $start_month = $smonth - 1;
                                    $start_year = date('Y', strtotime($v_milestone->end_date));
                                    $end_year = date('Y', strtotime($v_milestone->end_date));
                                    $end_day = date('d', strtotime($v_milestone->end_date));
                                    $emonth = date('n', strtotime($v_milestone->end_date));
                                    $end_month = $emonth - 1;
                            ?> {
                                        title: '<?php echo $v_milestone->milestone_name ?>',
                                        start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                                        end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                                        color: '<?= config_item('milestone_color') ?>',
                                        url: '<?= base_url() ?>client/projects/project_details/<?= $project_details->project_id ?>/5'
                                    },
                                <?php
                                endforeach;
                            }
                            $task_info = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_task')->result();
                            if (!empty($task_info)) {
                                foreach ($task_info as $v_task) :
                                    $start_day = date('d', strtotime($v_task->due_date));
                                    $smonth = date('n', strtotime($v_task->due_date));
                                    $start_month = $smonth - 1;
                                    $start_year = date('Y', strtotime($v_task->due_date));
                                    $end_year = date('Y', strtotime($v_task->due_date));
                                    $end_day = date('d', strtotime($v_task->due_date));
                                    $emonth = date('n', strtotime($v_task->due_date));
                                    $end_month = $emonth - 1;
                                ?> {
                                        title: "<?php echo $v_task->task_name ?>",
                                        start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                                        end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                                        color: '<?= config_item('tasks_color') ?>',
                                        url: '<?= base_url() ?>client/tasks/details/<?= $v_task->task_id ?>'
                                    },
                                <?php
                                endforeach;
                            }
                            $bug_info = $this->db->where(array('project_id' => $project_details->project_id))->get('tbl_bug')->result();
                            if (!empty($bug_info)) {
                                foreach ($bug_info as $v_bug) :
                                    $start_day = date('d', strtotime($v_bug->created_time));
                                    $smonth = date('n', strtotime($v_bug->created_time));
                                    $start_month = $smonth - 1;
                                    $start_year = date('Y', strtotime($v_bug->created_time));
                                    $end_year = date('Y', strtotime($v_bug->created_time));
                                    $end_day = date('d', strtotime($v_bug->created_time));
                                    $emonth = date('n', strtotime($v_bug->created_time));
                                    $end_month = $emonth - 1;
                                ?> {
                                        title: "<?php echo $v_bug->bug_title ?>",
                                        start: new Date(<?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>),
                                        end: new Date(<?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>),
                                        color: '<?= config_item('bugs_color') ?>',
                                        url: '<?= base_url() ?>client/bugs/view_bug_details/<?= $v_bug->bug_id ?>'
                                    },
                            <?php
                                endforeach;
                            }
                            ?>
                        ],
                        eventColor: '#3A87AD',
                    });
                }

            });
        </script>
        <?php include_once 'assets/plugins/fullcalendar/fullcalendar.php'; ?>
        <script src="<?php echo base_url(); ?>asset/js/jquery-ui.min.js"></script>
    </div>
</div>