<div class="panel panel-custom">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('calendar') ?></h3>
    </div>
    <div class="panel-body">
        <div class="">
            <div id="calendar"></div>
        </div>
        <link href="<?php echo base_url() ?>assets/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet"
              type="text/css">
        
        <style type="text/css">
            .datepicker {
                z-index: 1151 !important;
            }

            .mt-sm {
                font-size: 14px;
            }

            .fc-state-default {
                background: none;
                color: inherit !important;;
            }
        </style>
        <?php
        $gcal_api_key = config_item('gcal_api_key');
        $gcal_id = config_item('gcal_id');
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                if ($('#calendar').length) {
                    var date = new Date();
                    var d = date.getDate();
                    var m = date.getMonth();
                    var y = date.getFullYear();
                    var calendar = $('#calendar').fullCalendar({
                        googleCalendarApiKey: '<?= $gcal_api_key ?>',
                        eventAfterRender: function (event, element, view) {
                            if (event.type == 'fo') {
                                $(element).attr('data-toggle', 'ajaxModal').addClass(
                                    'ajaxModal');
                            }
                        },
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        // buttonText: {
                        //     prev: '<i class="fa fa-angle-left" />',
                        //     next: '<i class="fa fa-angle-right" />'
                        // },
                        selectable: true,
                        selectHelper: true,
                        select: function (start, end, allDay) {
                            var endtime = $.fullCalendar.formatDate(end, 'h:mm tt');
                            var starttime = $.fullCalendar.formatDate(start,
                                'yyyy/MM/dd');
                            var mywhen = starttime + ' - ' + endtime;
                            $('#event_modal #apptStartTime').val(starttime);
                            $('#event_modal #apptEndTime').val(starttime);
                            $('#event_modal #apptAllDay').val(allDay);
                            $('#event_modal #when').text(mywhen);
                            $('#event_modal').modal('show');
                        },
                        events: [
                            <?php
                            $invoice_info = get_result('tbl_invoices', array('project_id' => $project_details->project_id));
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
                                start: new Date(
                                    <?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>
                                ),
                                end: new Date(
                                    <?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>
                                ),
                                color: '<?= config_item('invoice_color') ?>',
                                url: '<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $v_invoice->invoices_id ?>'
                            },
                            <?php
                            endforeach;
                            }
                            $estimates_info = get_result('tbl_estimates', array('project_id' => $project_details->project_id));
                            
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
                                start: new Date(
                                    <?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>
                                ),
                                end: new Date(
                                    <?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>
                                ),
                                color: '<?= config_item('estimate_color') ?>',
                                url: '<?= base_url() ?>admin/estimates/create/estimates_details/<?= $v_estimates->estimates_id ?>'
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
                                title: "<?php echo $project_details->project_name  ?>",
                                start: new Date(
                                    <?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>
                                ),
                                end: new Date(
                                    <?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>
                                ),
                                color: '<?= config_item('project_color') ?>',
                                url: '<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>'
                            },
                            <?php
                            
                            $milestone_info = get_result('tbl_milestones', array('project_id' => $project_details->project_id));
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
                                start: new Date(
                                    <?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>
                                ),
                                end: new Date(
                                    <?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>
                                ),
                                color: '<?= config_item('milestone_color') ?>',
                                url: '<?= base_url() ?>admin/projects/project_details/<?= $project_details->project_id ?>/5'
                            },
                            <?php
                            endforeach;
                            }
                            $task_info = get_result('tbl_task', array('project_id' => $project_details->project_id));
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
                                start: new Date(
                                    <?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>
                                ),
                                end: new Date(
                                    <?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>
                                ),
                                color: '<?= config_item('tasks_color') ?>',
                                url: '<?= base_url() ?>admin/tasks/details/<?= $v_task->task_id ?>'
                            },
                            <?php
                            endforeach;
                            }
                            $bug_info = get_result('tbl_bug', array('project_id' => $project_details->project_id));
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
                                start: new Date(
                                    <?php echo $start_year . ',' . $start_month . ',' . $start_day; ?>
                                ),
                                end: new Date(
                                    <?php echo $end_year . ',' . $end_month . ',' . $end_day; ?>
                                ),
                                color: '<?= config_item('bugs_color') ?>',
                                url: '<?= base_url() ?>admin/bugs/view_bug_details/<?= $v_bug->bug_id ?>'
                            },
                            <?php
                            endforeach;
                            }
                            ?>
                        ],
                    });
                }

            });
        </script>
        <script src='<?= base_url() ?>assets/plugins/fullcalendar/moment.min.js'></script>
        <script src='<?= base_url() ?>assets/plugins/fullcalendar/fullcalendar.min.js'></script>
        <script src="<?= base_url() ?>assets/plugins/fullcalendar/gcal.min.js"></script>
    </div>
</div>