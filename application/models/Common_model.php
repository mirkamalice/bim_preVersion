<?php

class Common_model extends MY_Model
{
    public $_table_name;
    public $_order_by;
    public $_primary_key;
    
    public function get_public_holidays($yymm)
    {
        $this->db->select('tbl_holiday.*', FALSE);
        $this->db->from('tbl_holiday');
        $this->db->like('start_date', $yymm);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function get_holidays($dayName = null)
    {
        $this->db->select('tbl_working_days.day_id,tbl_working_days.flag', FALSE);
        $this->db->select('tbl_days.day', FALSE);
        $this->db->from('tbl_working_days');
        $this->db->join('tbl_days', 'tbl_days.day_id = tbl_working_days.day_id', 'left');
        $this->db->where('flag', 0);
        if (!empty($dayName)) {
            $this->db->where('day', $dayName);
        }
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function select_user_roll($designations_id)
    {
        $this->db->select('tbl_user_role.*', FALSE);
        $this->db->select('tbl_menu.link, tbl_menu.label', FALSE);
        $this->db->from('tbl_user_role');
        $this->db->join('tbl_menu', 'tbl_user_role.menu_id = tbl_menu.menu_id', 'left');
        $this->db->where('tbl_user_role.designations_id', $designations_id);
        //        $this->db->where('tbl_menu.status !=', 2);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function select_client_roll($user_id)
    {
        $this->db->select('tbl_client_role.*', FALSE);
        $this->db->select('tbl_client_menu.link, tbl_client_menu.label', FALSE);
        $this->db->from('tbl_client_role');
        $this->db->join('tbl_client_menu', 'tbl_client_role.menu_id = tbl_client_menu.menu_id', 'left');
        $this->db->where('tbl_client_role.user_id', $user_id);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function check_uri($uri)
    {
        $this->db->select('tbl_user_role.*', FALSE);
        $this->db->select('tbl_menu.link, tbl_menu.label', FALSE);
        $this->db->from('tbl_user_role');
        $this->db->join('tbl_menu', 'tbl_user_role.menu_id = tbl_menu.menu_id', 'left');
        $this->db->where('tbl_user_role.designations_id', $this->session->userdata('designations_id'));
        $this->db->where('tbl_menu.link', $uri);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function get_holiday_list_by_date($start_date, $end_date = null)
    {
        $this->db->select('tbl_holiday.*', FALSE);
        $this->db->from('tbl_holiday');
        $this->db->where('start_date >=', $start_date);
        if (!empty($end_date)) {
            $this->db->where('end_date <=', $end_date);
        }
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function get_advance_amount($user_id)
    {
        $emp_payroll = $this->db->where('user_id', $user_id)->get('tbl_employee_payroll')->row();
        if (!empty($emp_payroll)) {
            if (!empty($emp_payroll->salary_template_id)) {
                $emp_salary = $this->db->where('salary_template_id', $emp_payroll->salary_template_id)->get('tbl_salary_template')->row();
                $basic_salary = $emp_salary->basic_salary;
            }
            if (!empty($emp_payroll->hourly_rate_id)) {
                $hourly_salary = $this->db->where('hourly_rate_id', $emp_payroll->hourly_rate_id)->get('tbl_hourly_rate')->row();
                $basic_salary = $hourly_salary->hourly_rate * 10;
            }
        }
        if (!empty($basic_salary)) {
            return $basic_salary;
        } else {
            return null;
        }
    }
    
    public function get_total_attendace_by_date($start_date, $end_date, $user_id, $flag = null)
    {
        $this->db->select('tbl_attendance.*', FALSE);
        $this->db->from('tbl_attendance');
        $this->db->where('user_id', $user_id);
        $this->db->where('date_in >=', $start_date);
        $this->db->where('date_in <=', $end_date);
        if (!empty($flag) && $flag == 'absent') {
            $this->db->where('attendance_status', '0');
        } elseif (!empty($flag) && $flag == 'leave') {
            $this->db->where('attendance_status', '3');
        } else {
            $this->db->where('attendance_status', '1');
        }
        
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function get_total_working_hours($id)
    {
        $total_hh = 0;
        $total_mm = 0;
        
        $clock_time = $this->get_attendance_info($id);
        
        foreach ($clock_time as $mytime) {
            if (!empty($mytime)) {
                // calculate the start timestamp
                $startdatetime = strtotime($mytime->date_in . " " . $mytime->clockin_time);
                // calculate the end timestamp
                $enddatetime = strtotime($mytime->date_out . " " . $mytime->clockout_time);
                // calulate the difference in seconds
                $difference = $enddatetime - $startdatetime;
                $years = abs(floor($difference / 31536000));
                $days = abs(floor(($difference - ($years * 31536000)) / 86400));
                $hours = abs(floor(($difference - ($years * 31536000) - ($days * 86400)) / 3600));
                $mins = abs(floor(($difference - ($years * 31536000) - ($days * 86400) - ($hours * 3600)) / 60)); #floor($difference / 60);
                $total_mm += $mins;
                $total_hh += $hours;
                // output the result
                $total['minute'] = round($total_mm);
                $total['hour'] = round($total_hh);
            }
        }
        if (!empty($total)) {
            $total = $total;
        } else {
            $total['minute'] = 0;
            $total['hour'] = 0;
        }
        return $total;
    }
    
    public function get_attendance_info($id)
    {
        
        $this->db->select('tbl_clock.*', FALSE);
        $this->db->select('tbl_attendance.*', FALSE);
        $this->db->from('tbl_clock');
        $this->db->join('tbl_attendance', 'tbl_attendance.attendance_id = tbl_clock.attendance_id', 'left');
        $this->db->where('tbl_clock.attendance_id', $id);
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function get_user_notifications($read = 1, $no_limit = null)
    {
        $total = 15;
        
        $total_unread = $this->db->where(array('to_user_id' => $this->session->userdata('user_id'), 'read' => $read))->get('tbl_notifications')->result();
        if (count(array($total_unread)) > 0) {
            $total_unread = count(array($total_unread));
        } else {
            $total_unread = 0;
        }
        
        $total_unread_inline = $this->db->where(array('to_user_id' => $this->session->userdata('user_id'), 'read_inline' => $read))->get('tbl_notifications')->result();
        if (count(array($total_unread_inline)) > 0) {
            $total_unread_inline = count(array($total_unread_inline));
        } else {
            $total_unread_inline = 0;
        }
        if (is_numeric($read)) {
            $this->db->where('read', $read);
        } //is_numeric($read)
        if ($total_unread > $total) {
            $_diff = $total_unread - $total;
            $total = $_diff + $total;
        } elseif ($total_unread_inline > $total) {
            $_diff = $total_unread_inline - $total;
            $total = $_diff + $total;
        }
        $this->db->where('to_user_id', $this->session->userdata('user_id'));
        if (empty($no_limit)) {
            $this->db->limit(10);
        }
        $this->db->order_by('date', 'desc');
        $result = $this->db->get('tbl_notifications')->result();
        return $result;
    }
    
    public function get_attach_file($id, $module = null, $files_id = null)
    {
        
        // get all data from tbl_attachments and tbl_attachments_files and assign the data to array
        $this->db->select('tbl_attachments.*', FALSE);
        $this->db->select('tbl_attachments_files.*', FALSE);
        $this->db->select('tbl_account_details.fullname', FALSE);
        $this->db->from('tbl_attachments');
        $this->db->join('tbl_attachments_files', 'tbl_attachments_files.attachments_id = tbl_attachments.attachments_id', 'left');
        $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_attachments.user_id', 'left');
        if (!empty($module) && empty($files_id)) {
            
            if ($module == 'g') {
                $this->db->where('tbl_attachments.attachments_id', $id);
            } else {
                $this->db->where('tbl_attachments.module', $module);
                $this->db->where('tbl_attachments.module_field_id', $id);
            }
            $query_result = $this->db->get();
            $result = $query_result->result();
            // assign the data to array using attachments_id as key
            if ($module != 'g') {
                $data = array();
                foreach ($result as $row) {
                    $data[$row->attachments_id][] = $row;
                }
            } else {
                $data = $result;
            }
        } else {
            $this->db->where('tbl_attachments.attachments_id', $id);
            if (!empty($files_id)) {
                $this->db->where('tbl_attachments_files.uploaded_files_id', $files_id);
            }
            $query_result = $this->db->get();
            if (!empty($module) && $module == 'r') {
                $data[] = $query_result->row();
            } else {
                $data = $query_result->result();
            }
        }
        
        return $data;
    }
    
    public function count($table, $where = null)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->from($table);
        return $this->db->count_all_results();
    }
    
    private function commentsJoinStart()
    {
        $this->db->select('tbl_task_comment.*', FALSE);
        $this->db->select('tbl_account_details.fullname,tbl_account_details.avatar', FALSE);
        $this->db->from('tbl_task_comment');
        $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_task_comment.user_id', 'left');
    }
    
    private function commentsJoinEnd()
    {
        $this->db->order_by('tbl_task_comment.comment_datetime', 'desc');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function get_comments_by_where($where = null)
    {
        $this->commentsJoinStart();
        if (!empty($where)) {
            $this->db->where($where);
        }
        $result = $this->commentsJoinEnd();
        return $result;
    }
    
    
    public function get_comment_details($module_id, $module = null, $relpy_id = null)
    {
        // get all data from tbl_task_comment and tbl_users and assign the data to array and order by comment_datetime
        $this->commentsJoinStart();
        if (empty($module)) {
            if ($relpy_id) {
                $this->db->where('tbl_task_comment.comments_reply_id', $module_id);
            } else {
                $this->db->where('tbl_task_comment.task_comment_id', $module_id);
            }
        } else {
            $this->db->where('tbl_task_comment.module', $module);
            $this->db->where('tbl_task_comment.module_field_id', $module_id);
            $this->db->where('tbl_task_comment.comments_reply_id', '0');
            $this->db->where('tbl_task_comment.attachments_id', '0');
            $this->db->where('tbl_task_comment.uploaded_files_id', '0');
        }
        $result = $this->commentsJoinEnd();
        return $result;
        
    }
    
    public function get_activities_info($module, $id)
    {
        $this->db->select('tbl_activities.*', FALSE);
        $this->db->select('tbl_account_details.fullname,tbl_account_details.avatar', FALSE);
        $this->db->from('tbl_activities');
        $this->db->join('tbl_account_details', 'tbl_account_details.user_id = tbl_activities.user', 'left');
        $this->db->where('tbl_activities.module', $module);
        $this->db->where('tbl_activities.module_field_id', $id);
        $this->db->order_by('tbl_activities.activity_date', 'desc');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function sales_pdf($data, $pdf)
    {
        $this->load->helper('dompdf');
        $status = explode(':', $data['sales_info']->status);
        $viewfile = $this->load->view('admin/common/sales_pdf', $data, TRUE);
        if ($pdf == 'attach' && $pdf != 1) {
            $result = pdf_create($viewfile, slug_it($data['title'] . '_pdf_' . $data['sales_info']->reference_no), 1, null, true, '', $status[1]);
            return $result;
        } else {
            pdf_create($viewfile, slug_it($data['title'] . '_pdf_' . $data['sales_info']->reference_no), 1, null, false, '', $status[1]);
        }
    }
    
}
