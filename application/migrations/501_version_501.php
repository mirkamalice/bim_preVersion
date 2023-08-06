<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_501 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function up()
    {
        
        $this->db->query("ALTER TABLE `tbl_task_comment` ADD `module` VARCHAR(50) NULL DEFAULT NULL AFTER `bug_id`, ADD `module_field_id` INT(11) NULL DEFAULT NULL AFTER `module`;");
        $this->db->query("RENAME TABLE `tbl_task_attachment` TO `tbl_attachments`;");
        $this->db->query("ALTER TABLE `tbl_attachments` CHANGE `task_attachment_id` `attachments_id` INT NOT NULL AUTO_INCREMENT;");
        $this->db->query("RENAME TABLE `tbl_task_uploaded_files` TO `tbl_attachments_files`;");
        $this->db->query("ALTER TABLE `tbl_attachments_files` CHANGE `task_attachment_id` `attachments_id` INT NOT NULL;");
        $this->db->query("ALTER TABLE `tbl_task_comment` CHANGE `task_attachment_id` `attachments_id` INT NULL DEFAULT '0';");
        $this->db->query("ALTER TABLE `tbl_clock` ADD `latitude` VARCHAR(300) NULL DEFAULT NULL AFTER `ip_address`, ADD `longitude` VARCHAR(300) NULL DEFAULT NULL AFTER `latitude`, ADD `location` TEXT NULL DEFAULT NULL AFTER `longitude`;");
        $this->db->query("UPDATE `tbl_config` SET `value` = '5.0.1' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
