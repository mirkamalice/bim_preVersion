     <?php
        // $project_settings = json_decode($project_details->project_settings);
        // if (!empty($project_settings[2]) && $project_settings[2] == 'show_project_tasks') {
        //     $all_task_info = $this->db->where($where)->order_by('task_id', 'DESC')->get('tbl_task')->result();
        // }
     ?>
     <?php
        $direction = $this->session->userdata('direction');
        if (!empty($direction) && $direction == 'rtl') {
            $RTL = 'on';
        } else {
            $RTL = config_item('RTL');
        }
        if (!empty($RTL)) {
        ?>
         <link href="<?php echo base_url() ?>assets/plugins/ganttView/jquery.ganttViewRTL.css?ver=3.0.0" rel="stylesheet">
         <script src="<?php echo base_url() ?>assets/plugins/ganttView/jquery.ganttViewRTL.js"></script>
     <?php } else {
        ?>
         <link href="<?php echo base_url() ?>assets/plugins/ganttView/jquery.ganttView.css?ver=3.0.0" rel="stylesheet">
         <script src="<?php echo base_url() ?>assets/plugins/ganttView/jquery.ganttView.js"></script>
     <?php } ?>
         <div class="panel panel-custom">
             <div class="panel-heading">
                 <h3 class="panel-title"><?= lang('gantt') ?></h3>
             </div>
             <div class="">
                 <?php
                    //get gantt data for Milestones
                    $gantt_data = '{name: "' . $project_details->project_name . '", desc: "", values: [{
                                label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-headerline"
                                }]},  ';
                    $gantt_data = '{name: "' . $project_details->project_name . '", desc: "", values: [{
                                label: "", from: "' . $project_details->start_date . '", to: "' . $project_details->end_date . '", customClass: "gantt-headerline"
                                }]},  ';
                    if (!empty($all_task_info)) {
                        foreach ($all_task_info as $g_task) {
                            if (!empty($g_task)) {
                                if ($g_task->milestones_id == 0) {
                                    $tasks_result['uncategorized'][] = $g_task->task_id;
                                } else {
                                    $milestones_info = $this->db->where('milestones_id', $g_task->milestones_id)->get('tbl_milestones')->row();
                                    $tasks_result[$milestones_info->milestone_name][] = $g_task->task_id;
                                }
                            }
                        }
                    }
                    if (!empty($tasks_result)) {
                        foreach ($tasks_result as $cate => $tasks_info) :
                            $counter = 0;
                            if (!empty($tasks_info)) {
                                foreach ($tasks_info as $tasks_id) :
                                    $task = $this->db->where('task_id', $tasks_id)->get('tbl_task')->row();
                                    if ($cate != 'uncategorized') {
                                        $milestone = $this->db->where('milestones_id', $task->milestones_id)->get('tbl_milestones')->row();
                                        if (!empty($milestone)) {
                                            $m_start_date = $milestone->start_date;
                                            $m_end_date = $milestone->end_date;
                                        }
                                        $classs = 'gantt-timeline';
                                    } else {
                                        $cate = lang($cate);
                                        $m_start_date = null;
                                        $m_end_date = null;
                                        $classs = '';
                                    }
                                    $milestone_Name = "";
                                    if ($counter == 0) {
                                        $milestone_Name = $cate;
                                        $gantt_data .= '
                                {
                                  name: "' . $milestone_Name . '", desc: "", values: [';

                                        $gantt_data .= '{
                                label: "", from: "' . $m_start_date . '", to: "' . $m_end_date . '", customClass: "' . $classs . '"
                                }';
                                        $gantt_data .= ']
                                },  ';
                                    }

                                    $counter++;
                                    $start = ($task->task_start_date) ? $task->task_start_date : $m_end_date;
                                    $end = ($task->due_date) ? $task->due_date : $m_end_date;
                                    if ($task->task_status == "completed") {
                                        $class = "ganttGrey";
                                    } elseif ($task->task_status == "in_progress") {
                                        $class = "ganttin_progress";
                                    } elseif ($task->task_status == "not_started") {
                                        $class = "gantt_not_started";
                                    } elseif ($task->task_status == "deferred") {
                                        $class = "gantt_deferred";
                                    } else {
                                        $class = "ganttin_progress";
                                    }
                                    $gantt_data .= '
                          {
                            name: "", desc: "' . $task->task_name . '", values: [';

                                    $gantt_data .= '{
                          label: "' . $task->task_name . '", from: "' . $start . '", to: "' . $end . '", customClass: "' . $class . '"
                          }';
                                    $gantt_data .= ']
                          },  ';
                                endforeach;
                            }
                        endforeach;
                    }
                    ?>

                 <div class="gantt"></div>
                 <div id="gantData">

                     <script type="text/javascript">
                         function ganttChart(ganttData) {
                             $(function() {
                                 "use strict";
                                 $(".gantt").gantt({
                                     source: ganttData,
                                     minScale: "years",
                                     maxScale: "years",
                                     navigate: "scroll",
                                     itemsPerPage: 30,
                                     onItemClick: function(data) {
                                         console.log(data.id);
                                     },
                                     onAddClick: function(dt, rowId) {},
                                     onRender: function() {
                                         console.log("chart rendered");
                                     }
                                 });

                             });
                         }

                         ganttData = [<?= $gantt_data; ?>];
                         ganttChart(ganttData);

                         $(document).on("click", '.resize-gantt', function(e) {
                             ganttData = [<?= $gantt_data; ?>];
                             ganttChart(ganttData);
                         });
                     </script>
                 </div>
             </div>
     </div>