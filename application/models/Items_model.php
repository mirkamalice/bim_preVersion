<?php

/**
 * Description of Project_Model
 *
 * @author NaYeM
 */
class Items_Model extends MY_Model
{

    public $_table_name;
    public $_order_by;
    public $_primary_key;

    public function calculate_progress_by_tasks($id)
    {
        $project = $this->get($id);
        $total_project_tasks = total_rows('tbl_task', array(
            'project_id' => $id
        ));
        $total_finished_tasks = total_rows('tbl_task', array(
            'project_id' => $id,
            'task_status' => 'completed'
        ));
        $percent = 0;
        if ($total_finished_tasks >= floatval($total_project_tasks)) {
            $percent = 100;
        } else {
            if ($total_project_tasks !== 0) {
                $percent = number_format(($total_finished_tasks * 100) / $total_project_tasks, 2);
            }
        }
        return $percent;
    }

    function calculate_milestone_progress($milestones_id)
    {
        $all_milestone_tasks = $this->db->where('milestones_id', $milestones_id)->get('tbl_task')->num_rows();
        $complete_milestone_tasks = $this->db->where(
            array(
                'task_progress' => '100',
                'milestones_id' => $milestones_id
            )
        )->get('tbl_task')->num_rows();
        if ($all_milestone_tasks > 0) {
            return round(($complete_milestone_tasks / $all_milestone_tasks) * 100);
        } else {
            return 0;
        }
    }

    function calculate_project($project_value, $project_id)
    {
        switch ($project_value) {
            case 'project_cost':
                return $this->total_project_cost($project_id);
                break;
            case 'project_hours':
                return $this->total_project_hours($project_id, true);
                break;
        }
    }

    function total_project_cost($project_id)
    {
        $project_info = $this->db->where('project_id', $project_id)->get('tbl_project')->row();
        $tasks_cost = $this->calculate_all_tasks_cost($project_id);
        $project_time = $this->calculate_total_task_time($project_id);
        $project_hours = $project_time / 3600;
        if (empty($project_info->hourly_rate)) {
            $project_info = 0;
        }
        $project_cost = $project_hours * $project_info->hourly_rate;

        if ($project_info->billing_type == 'tasks_hours') {
            return $tasks_cost;
        }
        if ($project_info->billing_type == 'tasks_and_project_hours') {
            return $project_cost + $tasks_cost;
        }
        if ($project_info->billing_type == 'project_hours') {
            return $project_cost;
        } else {
            return $this->get_any_field('tbl_project', array('project_id' => $project_id), 'project_cost');
        }
    }

    public function calculate_all_tasks_cost($project_id)
    {
        $all_tasks = $this->db->where('project_id', $project_id)->get('tbl_task')->result();
        $total_cost = 0;
        if (!empty($all_tasks)) {
            foreach ($all_tasks as $v_tasks) {
                if (!empty($v_tasks->billable) && $v_tasks->billable == 'Yes') {
                    $task_time = $this->task_spent_time_by_id($v_tasks->task_id);
                    $total_time = $task_time / 3600;

                    $total_cost += $total_time * $v_tasks->hourly_rate;
                }
            }
        }
        return $total_cost;
    }

    function total_project_hours($project_id, $second = null, $task = null)
    {
        $project_info = $this->db->where('project_id', $project_id)->get('tbl_project')->row();
        $project_time = $this->calculate_total_task_time($project_id);
        $all_tasks = $this->db->where('project_id', $project_id)->get('tbl_task')->result();

        $task_time = 0;
        if (!empty($all_tasks)) {
            foreach ($all_tasks as $v_tasks) {
                if (!empty($v_tasks->billable) && $v_tasks->billable == 'Yes') {
                    $task_time += $this->task_spent_time_by_id($v_tasks->task_id);
                }
            }
        }
        $c_logged_time = 0;
        if ($project_info->billing_type == 'project_hours') {
            $c_logged_time = $project_time / 3600;
        }
        if ($project_info->billing_type == 'tasks_hours') {
            $c_logged_time = $task_time / 3600;
        }
        if ($project_info->billing_type == 'tasks_and_project_hours') {
            $c_logged_time = ($task_time + $project_time) / 3600;
        }
        if (!empty($task)) {
            return $logged_time = $task_time;
        }
        if (!empty($second)) {
            $logged_time = $project_time;
        } else {
            $logged_time = $c_logged_time;
        }

        return $logged_time;
    }

    function calculate_total_task_time($project_id)
    {
        $total_time = "SELECT start_time,end_time,project_id,
		end_time - start_time time_spent FROM tbl_tasks_timer WHERE project_id = '$project_id'";
        $result = $this->db->query($total_time)->result();

        $time_spent = array();
        foreach ($result as $time) {
            if ($time->start_time != 0 && $time->end_time != 0) {
                $time_spent[] = $time->time_spent;
            }
        }
        if (is_array($time_spent)) {
            return array_sum($time_spent);
        } else {
            return 0;
        }
    }

    function task_spent_time_by_staff($task_id, $user_id)
    {
        $where = 'task_id = ' . $task_id and 'user_id =' . $user_id;
        $total_time = "SELECT start_time,end_time,project_id,
		end_time - start_time time_spent FROM tbl_tasks_timer WHERE $where";
        $result = $this->db->query($total_time)->result();
        $time_spent = array();
        foreach ($result as $time) {
            if ($time->start_time != 0 && $time->end_time != 0) {
                $time_spent[] = $time->time_spent;
            }
        }
        if (is_array($time_spent)) {
            return array_sum($time_spent);
        } else {
            return 0;
        }
    }

    function project_hours($project_id)
    {
        $task_time = $this->get_sum('tbl_tasks', 'logged_time', array('project' => $project_id));
        $project_time = $this->get_sum('tbl_project', 'time_logged', array('project_id' => $project_id));
        $logged_time = ($task_time + $project_time) / 3600;
        return $logged_time;
    }


    function get_project_progress($id)
    {
        $project_info = $this->check_by(array('project_id' => $id), 'tbl_project');
        if ($project_info->project_status == 'completed') {
            $progress = 100;
        } else {
            if (!empty($project_info->calculate_progress) && $project_info->calculate_progress != '0') {
                if ($project_info->calculate_progress == 'through_project_hours') {
                    $estimate_hours = $project_info->estimate_hours;
                    $percentage = $this->get_estime_time($estimate_hours);
                    if ($percentage != 0) {
                        $logged_hour = $this->calculate_project('project_hours', $project_info->project_id);
                        if ($percentage != 0) {
                            $progress = round(($logged_hour / $percentage) * 100);
                        }
                    }
                } else {
                    $done_task = $this->db->where(array('project_id' => $id, 'task_status' => 'completed'))->get('tbl_task')->result();
                    $total_tasks = $this->db->where(array('project_id' => $id))->get('tbl_task')->result();
                    if (count(array($done_task)) > 0) {
                        $done_task = count(array($done_task));
                    } else {
                        $done_task = 0;
                    }
                    if (count(array($total_tasks)) > 0) {
                        $total_tasks = count(array($total_tasks));
                    } else {
                        $total_tasks = 0;
                    }
                    if ($total_tasks != 0) {
                        $progress = round(($done_task / $total_tasks) * 100);
                    }
                }
            } else {
                $progress = $project_info->progress;
            }
            if (empty($progress)) {
                $progress = 0;
            } else {
                if ($progress > 100) {
                    $progress = 100;
                }
            }
        }

        return $progress;
    }

    function set_progress($id)
    {
        $project_info = $this->check_by(array('project_id' => $id), 'tbl_project');

        if (!empty($project_info->calculate_progress) && $project_info->calculate_progress != '0') {
            if ($project_info->calculate_progress == 'through_project_hours') {
                $estimate_hours = $project_info->estimate_hours;
                $percentage = $this->get_estime_time($estimate_hours);
                $logged_hour = $this->calculate_project('project_hours', $project_info->project_id);
                if ($percentage != 0) {
                    $progress = round(($logged_hour / $percentage) * 100);
                }
            } else {
                $done_task = $this->db->where(array('project_id' => $id, 'task_status' => 'completed'))->get('tbl_task')->result();
                $total_tasks = $this->db->where(array('project_id' => $id))->get('tbl_task')->result();
                if (count(array($done_task)) > 0) {
                    $done_task = count(array($done_task));
                } else {
                    $done_task = 0;
                }
                if (count(array($total_tasks)) > 0) {
                    $total_tasks = count(array($total_tasks));
                } else {
                    $total_tasks = 0;
                }
                if (empty($total_tasks) || empty($done_task)) {
                    $progress = 0;
                } else {
                    $progress = round(($done_task / $total_tasks) * 100);
                }

                if ($progress > 100) {
                    $progress = 100;
                }
            }
        } else {
            $progress = $project_info->progress;
        }
        if (empty($progress)) {
            $progress = 0;
        }
        $p_data = array(
            'progress' => $progress,
        );
        $this->_table_name = "tbl_project"; //table name
        $this->_primary_key = "project_id";
        $this->save($p_data, $id);
    }

    public function get_leads($search_by, $id)
    {
        $all_leads_info = array_reverse($this->get_permission('tbl_leads'));
        if (!empty($search_by) && !empty($id)) {
            $leads = array();
            if (!empty($all_leads_info)) {
                foreach ($all_leads_info as $v_leads) {
                    if ($search_by == 'by_status') {
                        if ($v_leads->lead_status_id == $id) {
                            array_push($leads, $v_leads);
                        }
                    } else if ($search_by == 'by_source') {
                        if ($v_leads->lead_source_id == $id) {
                            array_push($leads, $v_leads);
                        }
                    }
                }
            }
        } else {
            $leads = $all_leads_info;
        }
        return $leads;
    }

    public function make_all_items($project_id, $items_name = null, $tasks = null, $expense = null, $json = null)
    {
        $all_items = array();
        $project_info = $this->check_by(array('project_id' => $project_id), 'tbl_project');
        $project_hours = $this->calculate_project('project_hours', $project_id);
        $project_cost = $this->calculate_project('project_cost', $project_id);
        $p_hours = floor($project_hours / 3600);
        $p_minutes = floor(($project_hours / 60) % 60);
        if ($p_hours < 9) {
            $p_hours = '0' . $p_hours;
        }
        if ($p_minutes < 9) {
            $p_minutes = '0' . $p_minutes;
        }
        if (!empty($items_name)) {
            if ($items_name == 'single_line') {
                $items['items_id'] = 0;
                $items['saved_items_id'] = 'msp_' . $project_id;
                $items['item_name'] = $project_info->project_name;
                $description = $project_info->project_name . ' - ' . $p_hours . ' : ' . $p_minutes . ' ' . lang('hours') . '&nbsp' . ' ' . '&nbsp';;

                if (!empty($tasks)) {
                    foreach ($tasks as $task_id) {
                        $all_tasks = $this->db->where('task_id', $task_id)->get('tbl_task')->result();
                        if (!empty($all_tasks)) {
                            foreach ($all_tasks as $task) {
                                $t_seconds = $this->items_model->task_spent_time_by_id($task->task_id);
                                $t_init = $t_seconds;
                                $t_hours = floor($t_init / 3600);
                                $t_minutes = floor(($t_init / 60) % 60);

                                if ($t_hours < 9) {
                                    $t_hours = '0' . $t_hours;
                                }
                                if ($t_minutes < 9) {
                                    $t_minutes = '0' . $t_minutes;
                                }
                                $description .= $task->task_name . ' - ' . $t_hours . ' : ' . $t_minutes . ' ' . lang('hours') . '&nbsp' . ' ' . '&nbsp';;
                            }
                        }
                    }
                };
                $items['item_desc'] = $description;
                $items['quantity'] = 1;
                $items['unit'] = null;
                $items['unit_cost'] = $project_cost;
                $items['rate'] = $project_cost;
                $all_items[] = (object)$items;
            }

            $total_timer = $this->db->where(array('project_id' => $project_info->project_id))->get('tbl_tasks_timer')->result();
            if ($items_name == 'project_timer' || $items_name == 'all_timesheet_individually') {
                if (!empty($total_timer)) {
                    foreach ($total_timer as $t_key => $v_timer) {
                        $p_timer_info = $this->db->where(array('project_id' => $v_timer->project_id))->get('tbl_project')->row();
                        if (!empty($p_timer_info)) {
                            $user_info = $this->db->where(array('user_id' => $v_timer->user_id))->get('tbl_users')->row();
                            $total_seconds = $v_timer->end_time - $v_timer->start_time;
                            $init = $total_seconds;
                            $pp_hours = floor($init / 3600);
                            $pp_minutes = floor(($init / 60) % 60);
                            $qty = round($init / 3600, 2);

                            $items['items_id'] = 0;
                            $items['saved_items_id'] = 'mti' . $t_key;
                            $items['item_name'] = $project_info->project_name . ' ' . lang('by') . ' - ' . $user_info->username;

                            if ($pp_hours < 9) {
                                $pp_hours = '0' . $pp_hours;
                            }
                            if ($pp_minutes < 9) {
                                $pp_minutes = '0' . $pp_minutes;
                            }
                            $description = lang('start_date') . ' : ' . strftime(config_item('date_format'), strtotime($v_timer->start_time)) . ' ' . display_time($v_timer->start_time, true) . '&nbsp';
                            $description .= lang('end_date') . ' : ' . strftime(config_item('date_format'), strtotime($v_timer->end_time)) . ' ' . display_time($v_timer->end_time, true) . '&nbsp';
                            $description .= lang('logged_time') . ' : ' . $pp_hours . ' : ' . $pp_minutes . ' ' . lang('hours') . ' ' . lang('by') . ' - ' . $user_info->username;

                            $items['item_desc'] = $description;
                            $items['quantity'] = $qty;
                            $items['unit'] = null;
                            $items['unit_cost'] = $project_info->hourly_rate;
                            $items['rate'] = $project_info->hourly_rate;
                            $all_items[] = (object)$items;
                        }
                    }
                }
            }
            if ($items_name == 'all_timesheet_individually') {
                if (!empty($tasks)) {
                    foreach ($tasks as $task_id) {
                        $total_tasks_timer = $this->db->where(array('task_id' => $task_id))->get('tbl_tasks_timer')->result();

                        if (!empty($total_tasks_timer)) {
                            foreach ($total_tasks_timer as $t_key => $v_task_timer) {
                                $tasks_timer_info = $this->db->where(array('task_id' => $v_task_timer->task_id))->get('tbl_task')->row();
                                $user_info = $this->db->where(array('user_id' => $v_task_timer->user_id))->get('tbl_users')->row();
                                $tasks_total_seconds = $v_task_timer->end_time - $v_task_timer->start_time;
                                $tasks_init = $tasks_total_seconds;
                                $tt_hours = floor($tasks_init / 3600);
                                $tt_minutes = floor(($tasks_init / 60) % 60);
                                $qty = round($tasks_init / 3600, 2);

                                $items['items_id'] = 0;
                                $items['saved_items_id'] = 'mts' . $t_key;
                                $items['item_name'] = $project_info->project_name . ' - ' . $tasks_timer_info->task_name;

                                if ($tt_hours < 9) {
                                    $tt_hours = '0' . $tt_hours;
                                }
                                if ($tt_minutes < 9) {
                                    $tt_minutes = '0' . $tt_minutes;
                                }
                                $description = lang('start_date') . ' : ' . strftime(config_item('date_format'), strtotime($v_task_timer->start_time)) . ' ' . display_time($v_task_timer->start_time, true) . '&nbsp';
                                $description .= lang('end_date') . ' : ' . strftime(config_item('date_format'), strtotime($v_task_timer->end_time)) . ' ' . display_time($v_task_timer->end_time, true) . '&nbsp';
                                $description .= lang('logged_time') . ' : ' . $tt_hours . ' : ' . $tt_minutes . ' ' . lang('hours') . ' ' . lang('by') . ' - ' . $user_info->username;

                                $items['item_desc'] = $description;
                                $items['quantity'] = $qty;
                                $items['unit'] = null;
                                $items['unit_cost'] = $project_info->hourly_rate;
                                $items['rate'] = $project_info->hourly_rate;
                                $items['task_id'] = $task_id;

                                $all_items[] = (object)$items;
                            }
                        }
                    }
                }
            }
        }
        if ($items_name != 'all_timesheet_individually') {
            if (!empty($tasks)) {
                foreach ($tasks as $task_id) {
                    $all_tasks = $this->db->where('task_id', $task_id)->get('tbl_task')->result();
                    if (!empty($all_tasks)) {
                        foreach ($all_tasks as $key => $task) {
                            $total_seconds = $this->items_model->task_spent_time_by_id($task->task_id);
                            $init = $total_seconds;
                            $hours = floor($init / 3600);
                            $minutes = floor(($init / 60) % 60);
                            $seconds = $init % 60;
                            $qty = round($init / 3600, 2);
                            $items['items_id'] = 0;
                            $items['saved_items_id'] = 'mts' . $key;

                            $items['item_name'] = $project_info->project_name . ' - ' . $task->task_name;

                            if ($hours < 9) {
                                $hours = '0' . $hours;
                            }
                            if ($minutes < 9) {
                                $minutes = '0' . $minutes;
                            }
                            $description = $hours . ' : ' . $minutes . ' ' . lang('hours');;

                            $items['item_desc'] = $description;
                            $items['quantity'] = $qty;
                            $items['unit'] = null;
                            $items['unit_cost'] = $task->hourly_rate;
                            $items['rate'] = $task->hourly_rate;
                            $items['task_id'] = $task_id;

                            $all_items[] = (object)$items;
                        }
                    }
                }
            }
        }
        if (!empty($expense)) {
            foreach ($expense as $key => $expense_id) {
                $expense_info = $this->db->where('transactions_id', $expense_id)->get('tbl_transactions')->row();
                $category_info = $this->db->where('expense_category_id', $expense_info->category_id)->get('tbl_expense_category')->row();
                if (!empty($category_info)) {
                    $category = $category_info->expense_category;
                } else {
                    $category = 'Undefined Category';
                }

                $items['saved_items_id'] = 'mex' . $key;
                $items['items_id'] = 0;
                $items['item_name'] = '[ ' . lang('expense') . ' ] - ' . $category . ' - ' . $expense_info->name;;
                if (!empty($category_info->description)) {
                    $description = '[ ' . lang('projects') . ' ] - ' . $project_info->project_name . ' - ' . $category_info->description;
                } else {
                    $description = '[ ' . lang('projects') . ' ] - ' . $project_info->project_name;
                }
                $items['item_desc'] = $description;
                $items['quantity'] = 1;
                $items['unit'] = null;
                $items['unit_cost'] = $expense_info->amount;
                $items['rate'] = $expense_info->amount;

                $all_items[] = (object)$items;
            }
        }
        
        if (!empty($json)) {
            return json_encode($all_items);
        }

        return $all_items;
    }


    public function get_itemlist($group_id = null)
    {
        if (!empty($group_id)) {
            $result = get_result('tbl_saved_items', array('customer_group_id' => $group_id));
        } else {
            $result = get_result('tbl_saved_items');
        }
        return $result;
    }

    public function get_goal_tracking($id)
    {
        $all_goal_tracking = array_reverse($this->get_permission('tbl_goal_tracking'));
        $goal_tracking = array();
        if (!empty($id)) {
            foreach ($all_goal_tracking as $v_goal_tracking) {
                if ($v_goal_tracking->goal_type_id == $id) {
                    array_push($goal_tracking, $v_goal_tracking);
                }
            }
        } else {
            $goal_tracking = $all_goal_tracking;
        }
        return $goal_tracking;
    }


    public function generate_projects_number()
    {
        $strlen = strlen(config_item('projects_start_no')) + 1;
        $query = $this->db->query('SELECT project_no, project_id FROM tbl_project WHERE project_id = (SELECT MAX(project_id) FROM tbl_project)');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $ref_number = intval(substr($row->project_no, $strlen));
            $next_number = ++$row->project_id;
            if ($next_number < $ref_number) {
                $next_number = $ref_number + 1;
            }
            if ($next_number < config_item('projects_start_no')) {
                $next_number = config_item('projects_start_no');
            }
            $next_number = $this->project_no_exists($next_number);
            $next_number = sprintf('%04d', $next_number);
        } else {
            $next_number = sprintf('%04d', config_item('projects_start_no'));
        }
        if (!empty(config_item('projects_number_format'))) {
            $projects_format = config_item('projects_number_format');
            $projects_prefix = str_replace("[" . config_item('projects_prefix') . "]", config_item('projects_prefix'), $projects_format);
            $yyyy = str_replace("[yyyy]", date('Y'), $projects_prefix);
            $yy = str_replace("[yy]", date('y'), $yyyy);
            $mm = str_replace("[mm]", date('M'), $yy);
            $m = str_replace("[m]", date('m'), $mm);
            $dd = str_replace("[dd]", date('d'), $m);
            $next_number = str_replace("[number]", $next_number, $dd);
        }
        return $next_number;
    }

    public function project_no_exists($next_number)
    {
        $enext_number = sprintf('%04d', $next_number);
        if (!empty(config_item('projects_number_format'))) {
            $projects_format = config_item('projects_number_format');
            $projects_prefix = str_replace("[" . config_item('projects_prefix') . "]", config_item('projects_prefix'), $projects_format);
            $yyyy = str_replace("[yyyy]", date('Y'), $projects_prefix);
            $yy = str_replace("[yy]", date('y'), $yyyy);
            $mm = str_replace("[mm]", date('M'), $yy);
            $m = str_replace("[m]", date('m'), $mm);
            $dd = str_replace("[dd]", date('d'), $m);
            $enext_number = str_replace("[number]", $next_number, $dd);
        }
        $records = $this->db->where('project_no', $enext_number)->get('tbl_project')->num_rows();
        if ($records > 0) {
            return $this->project_no_exists($next_number + 1);
        } else {
            return $next_number;
        }
    }


    function calculate_to($invoice_value, $invoice_id)
    {
        switch ($invoice_value) {
            case 'invoice_cost':
                return $this->get_invoice_cost($invoice_id);
                break;
            case 'tax':
                return $this->get_invoice_tax_amount($invoice_id);
                break;
            case 'discount':
                return $this->get_invoice_discount($invoice_id);
                break;
            case 'paid_amount':
                return $this->get_invoice_paid_amount($invoice_id);
                break;
            case 'invoice_due':
                return $this->get_invoice_due_amount($invoice_id);
                break;
            case 'total':
                return $this->get_invoice_total_amount($invoice_id);
                break;
        }
    }
    function transfer_calculate_to($value, $transfer_item_id)
    {
        switch ($value) {
            case 'transfer_cost':
                return $this->get_transfer_cost($transfer_item_id);
                break;
            case 'tax':
                return $this->get_transfer_tax_amount($transfer_item_id);
                break;
            case 'discount':
                return $this->get_transfer_discount($transfer_item_id);
                break;
            case 'paid_amount':
                return $this->get_transfer_paid_amount($transfer_item_id);
                break;
            case 'purchase_due':
                return $this->get_transfer_due_amount($transfer_item_id);
                break;
            case 'total':
                return $this->get_transfer_total_amount($transfer_item_id);
                break;
        }
    }
    function get_transfer_cost($transfer_item_id)
    {
        $this->db->select_sum('total_cost');
        $this->db->where('transfer_item_id', $transfer_item_id);
        $this->db->from('tbl_transfer_itemlist');
        $query_result = $this->db->get();
        $cost = $query_result->row();
        if (!empty($cost->total_cost)) {
            $result = $cost->total_cost;
        } else {
            $result = '0';
        }
        return $result;
    }
    public function get_transfer_total_amount($transfer_item_id)
    {

        $purchase_info = $this->check_by(array('transfer_item_id' => $transfer_item_id), 'tbl_transfer_itemlist');
        $tax = $this->get_transfer_tax_amount($transfer_item_id);
        $purchase_cost = $this->get_transfer_cost($transfer_item_id);
        //        $payment_made = $this->get_purchase_paid_amount($purchase_id);

        $total_amount = $purchase_cost + $tax;
        if ($total_amount <= 0) {
            $total_amount = 0;
        }
        return $total_amount;
    }
    public function get_transfer_tax_amount($transfer_item_id)
    {
        $purchase_info = $this->check_by(array('transfer_item_id' => $transfer_item_id), 'tbl_transfer_itemlist');
        if (!empty($purchase_info->total_tax)) {
            $tax_info = json_decode($purchase_info->total_tax);
        }
        $tax = 0;
        if (!empty($tax_info)) {
            $total_tax = $tax_info->total_tax;
            if (!empty($total_tax)) {
                foreach ($total_tax as $t_key => $v_tax_info) {
                    $tax += $v_tax_info;
                }
            }
        }
        return $tax;
    }

    function ordered_items_by_id($id, $json = null)
    {
        $rows = $this->db->where('transfer_item_id', $id)->order_by('order', 'asc')->get('tbl_transfer_itemlist')->result();
        if (!empty($json)) {
            if (!empty($rows)) {
                foreach ($rows as $row) {
                    $row->items_id = $row->transfer_itemList_id;
                    $row->qty = $row->quantity;
                    $row->rate = $row->unit_cost;
                    $row->cost_price = $row->unit_cost;
                    $row->new_itmes_id = $row->saved_items_id;
                    $row->taxname = json_decode($row->item_tax_name);
                    $pr[] = $row;
                }
                return json_encode($pr);
            }
        } else {
            return $rows;
        }
    }
}
