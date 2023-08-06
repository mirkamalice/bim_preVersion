<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_509 extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function up()
    {
        // check module field exist or not into tbl_attachments table
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_attachments_files` LIKE 'attachments_id';");
        if ($query->num_rows() == 0) {
            $this->db->query("ALTER TABLE `tbl_attachments_files` CHANGE `task_attachment_id` `attachments_id` INT(11) NOT NULL;");
        }
        $this->db->query("UPDATE `tbl_config` SET `value` = '5.0.9' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
