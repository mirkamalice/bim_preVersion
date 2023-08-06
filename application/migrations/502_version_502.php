<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_502 extends CI_Migration
{
  function __construct()
  {
    parent::__construct();
  }

  public function up()
  {
    // $this->db->query("INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `status`) VALUES (NULL, 'workplace', 'admin/attendance/workplace', 'fa fa-street-view', '105', '2', '1');");
    $this->db->query("UPDATE `tbl_config` SET `value` = '5.0.2' WHERE `tbl_config`.`config_key` = 'version';");
  }
}
