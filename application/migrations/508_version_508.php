<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_508 extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function up()
    {
        // check module field exist or not into tbl_attachments table
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_attachments` LIKE 'module';");
        if ($query->num_rows() == 0) {
            $this->db->query("ALTER TABLE `tbl_attachments` ADD `module` VARCHAR(50) NULL DEFAULT NULL AFTER `description`, ADD `module_field_id` INT(11) NULL DEFAULT NULL AFTER `module`;");
        }
        $this->db->query("UPDATE `tbl_config` SET `value` = '5.0.8' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
