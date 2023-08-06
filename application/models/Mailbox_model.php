<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mailbox_model
 *
 * @author NaYeM
 */
class Mailbox_Model extends MY_Model
{
    
    public $_table_name;
    public $_order_by;
    public $_primary_key;
    
    public function get_inbox_message($email, $flag = Null, $del_info = NULL)
    {
        
        $this->db->select('*');
        $this->db->from('tbl_inbox');
        $this->db->where('to', $email);
//        $this->db->where('user_id', my_id());
        if (!empty($del_info)) {
            $this->db->where('deleted', 'Yes');
        } else {
            $this->db->where('deleted', 'No');
        }
        if (!empty($flag)) {
            $this->db->where('view_status', '2');
        }
        $this->db->order_by('message_time', 'DESC');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function get_sent_message($user_id, $del_info = NULL)
    {
        $this->db->select('*');
        $this->db->from('tbl_sent');
        $this->db->where('user_id', $user_id);
        if (!empty($del_info)) {
            $this->db->where('deleted', 'Yes');
        } else {
            $this->db->where('deleted', 'No');
        }
        $this->db->order_by('message_time', 'DESC');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function get_draft_message($user_id, $del_info = NULL)
    {
        $this->db->select('*');
        $this->db->from('tbl_draft');
        $this->db->where('user_id', $user_id);
        if (!empty($del_info)) {
            $this->db->where('deleted', 'Yes');
        } else {
            $this->db->where('deleted', 'No');
        }
        $this->db->order_by('message_time', 'DESC');
        $query_result = $this->db->get();
        $result = $query_result->result();
        return $result;
    }
    
    public function send_mail($params, $attach = null)
    {
        
        $config = array();
        // If postmark API is being used
        $params['resourceed_file'] = $attach;
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = "html";
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['crlf'] = "\r\n";
        $config['smtp_timeout'] = '30';
        $this->load->library('email', $config);
        $this->email->clear(true);
        $this->email->from(MyDetails()->active_email, MyDetails()->fullname);
        $this->email->to($params['recipient']);
        $this->email->subject($params['subject']);
        $this->email->message($params['message']);
        if (!empty($params['resourceed_file'])) {
            $this->email->attach($params['resourceed_file']);
        }
        $send = $this->email->send();
        if ($send) {
            return $send;
        } else {
            send_later($params);
        }
        return true;
    }
}
