<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_507 extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function up()
    {
        // sync tbl_task_comment and remove field name task_id
        $all_comments = get_result('tbl_task_comment');
        foreach ($all_comments as $comments) {
            // check if task id exist then update it into module = tasks and module_field_id = task_id
            // then drop the task_id field from tbl_task_comment
            if (is_numeric($comments->task_id)) {
                $this->db->where('task_comment_id', $comments->task_comment_id);
                $this->db->update('tbl_task_comment', array('module' => 'tasks', 'module_field_id' => $comments->task_id));
                // then drop the task_id field from database
            } else if (is_numeric($comments->bug_id)) {
                $this->db->where('task_comment_id', $comments->task_comment_id);
                $this->db->update('tbl_task_comment', array('module' => 'bugs', 'module_field_id' => $comments->bug_id));
            } else if (is_numeric($comments->goal_tracking_id)) {
                $this->db->where('task_comment_id', $comments->task_comment_id);
                $this->db->update('tbl_task_comment', array('module' => 'goal_tracking', 'module_field_id' => $comments->goal_tracking_id));
            } else if (is_numeric($comments->opportunities_id)) {
                $this->db->where('task_comment_id', $comments->task_comment_id);
                $this->db->update('tbl_task_comment', array('module' => 'opportunities', 'module_field_id' => $comments->opportunities_id));
            } else if (is_numeric($comments->leads_id)) {
                $this->db->where('task_comment_id', $comments->task_comment_id);
                $this->db->update('tbl_task_comment', array('module' => 'leads', 'module_field_id' => $comments->leads_id));
            } else if (is_numeric($comments->project_id)) {
                $this->db->where('task_comment_id', $comments->task_comment_id);
                $this->db->update('tbl_task_comment', array('module' => 'projects', 'module_field_id' => $comments->project_id));
            }
            
        }
        $this->db->query('ALTER TABLE tbl_task_comment DROP COLUMN task_id');
        $this->db->query('ALTER TABLE tbl_task_comment DROP COLUMN bug_id');
        $this->db->query('ALTER TABLE tbl_task_comment DROP COLUMN goal_tracking_id');
        $this->db->query('ALTER TABLE tbl_task_comment DROP COLUMN opportunities_id');
        $this->db->query('ALTER TABLE tbl_task_comment DROP COLUMN leads_id');
        $this->db->query('ALTER TABLE tbl_task_comment DROP COLUMN project_id');
        
        // sync tbl_attachments and remove field name task_id
        $all_attachments = get_result('tbl_attachments');
        foreach ($all_attachments as $attachments) {
            // check if task id exist then update it into module = tasks and module_field_id = task_id
            // then drop the task_id field from tbl_attachments
            if (is_numeric($attachments->task_id)) {
                $this->db->where('attachment_id', $attachments->attachment_id);
                $this->db->update('tbl_attachments', array('module' => 'tasks', 'module_field_id' => $attachments->task_id));
                // then drop the task_id field from database
            } else if (is_numeric($attachments->bug_id)) {
                $this->db->where('attachment_id', $attachments->attachment_id);
                $this->db->update('tbl_attachments', array('module' => 'bugs', 'module_field_id' => $attachments->bug_id));
            } else if (is_numeric($attachments->opportunities_id)) {
                $this->db->where('attachment_id', $attachments->attachment_id);
                $this->db->update('tbl_attachments', array('module' => 'opportunities', 'module_field_id' => $attachments->opportunities_id));
            } else if (is_numeric($attachments->leads_id)) {
                $this->db->where('attachment_id', $attachments->attachment_id);
                $this->db->update('tbl_attachments', array('module' => 'leads', 'module_field_id' => $attachments->leads_id));
            } else if (is_numeric($attachments->project_id)) {
                $this->db->where('attachment_id', $attachments->attachment_id);
                $this->db->update('tbl_attachments', array('module' => 'projects', 'module_field_id' => $attachments->project_id));
            }
        }
        $this->db->query('ALTER TABLE tbl_attachments DROP COLUMN task_id');
        $this->db->query('ALTER TABLE tbl_attachments DROP COLUMN bug_id');
        $this->db->query('ALTER TABLE tbl_attachments DROP COLUMN opportunities_id');
        $this->db->query('ALTER TABLE tbl_attachments DROP COLUMN leads_id');
        $this->db->query('ALTER TABLE tbl_attachments DROP COLUMN project_id');
        // check how many label=transfer_settings exist into tbl_menu by label
        $transfer_settings = get_result('tbl_menu', array('label' => 'transfer_settings'));
        // if transfer_settings is more than 1 then delete the transfer_settings label from tbl_menu
        if (count($transfer_settings) > 1) {
            $this->db->where('label', 'transfer_settings');
            $this->db->delete('tbl_menu');
        }
        // check milestones_order exist or not into tbl_task
        $milestones_order = $this->db->query('SHOW COLUMNS FROM `tbl_task` LIKE "milestones_order"')->num_rows();
        if ($milestones_order == 0) {
            $this->db->query('ALTER TABLE `tbl_task` ADD `milestones_order` INT(11) NOT NULL DEFAULT "0" AFTER `index_no`;');
        }
        $this->db->query('ALTER TABLE `tbl_tasks_timer` CHANGE `task_id` `task_id` INT(11) NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_task` CHANGE `index_no` `index_no` INT(11) NULL DEFAULT NULL; ');
        $this->db->query('ALTER TABLE `tbl_bug` CHANGE `notes` `notes` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_invoices` CHANGE `recur_start_date` `recur_start_date` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_invoices` CHANGE `recur_end_date` `recur_end_date` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        
        $this->db->query('ALTER TABLE `tbl_invoices` CHANGE `discount_type` `discount_type` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT "none";');
        $this->db->query('ALTER TABLE `tbl_leads` CHANGE `company_name` `company_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_leads` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        
        $this->db->query('ALTER TABLE `tbl_departments` CHANGE `encryption` `encryption` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_departments` CHANGE `host` `host` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_departments` CHANGE `username` `username` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_departments` CHANGE `password` `password` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_departments` CHANGE `mailbox` `mailbox` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_task` CHANGE `task_name` `task_name` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_task` CHANGE `task_description` `task_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_task` CHANGE `task_start_date` `task_start_date` DATE NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_task` CHANGE `due_date` `due_date` DATE NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_task` CHANGE `task_progress` `task_progress` INT(11) NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_task` CHANGE `task_hour` `task_hour` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_invoices` CHANGE `project_id` `project_id` INT(11) NULL DEFAULT "0"');
        $this->db->query('ALTER TABLE `tbl_award_points` CHANGE `client_award_point` `client_award_point` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `user_award_point` `user_award_point` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query("ALTER TABLE `tbl_proposals` CHANGE `discount_type` `discount_type` ENUM('none','before_tax','after_tax') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'none';");
        $this->db->query('ALTER TABLE `tbl_transactions` CHANGE `category_id` `category_id` INT(11) NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `tbl_transactions` CHANGE `tags` `tags` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;');
        $this->db->query("ALTER TABLE `tbl_contracts` CHANGE `contract_value` `contract_value` DECIMAL(15,2) NULL DEFAULT '0.00';");
        $this->db->query("ALTER TABLE `tbl_credit_note` CHANGE `discount_type` `discount_type` ENUM('none','before_tax','after_tax') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'none';");
        $this->db->query('ALTER TABLE `tbl_transactions` CHANGE `recurring_type` `recurring_type` VARCHAR(50) NULL DEFAULT NULL;');
        $this->db->query("UPDATE `tbl_client_menu` SET `link` = 'spreadsheet' WHERE `tbl_client_menu`.`menu_id` = 25;");
        $this->db->query('ALTER TABLE `tbl_transactions` CHANGE `payment_methods_id` `payment_methods_id` VARCHAR(100) NULL DEFAULT NULL;');
        
        $this->db->query("UPDATE `tbl_config` SET `value` = '5.0.7' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
