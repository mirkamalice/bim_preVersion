<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_601 extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function up()
    {
        // check module field exist or not into tbl_attachments table
        $this->db->query("ALTER TABLE `tbl_calls` ADD `module` VARCHAR(50) NULL DEFAULT NULL AFTER `user_id`, ADD `module_field_id` INT(11) NULL DEFAULT NULL AFTER `module`;");
        $this->db->query("ALTER TABLE `tbl_mettings` ADD `module` VARCHAR(50) NULL DEFAULT NULL AFTER `user_id`, ADD `module_field_id` INT(11) NULL DEFAULT NULL AFTER `module`;");
        $this->db->query("UPDATE `tbl_config` SET `value` = '6.0.1' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
