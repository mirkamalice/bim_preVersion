<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_405 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_manufacturer` (
            `manufacturer_id` int NOT NULL AUTO_INCREMENT,  
            `manufacturer` varchar(100) NOT NULL,
            `description` text,
            PRIMARY KEY (`manufacturer_id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `tbl_saved_items` ADD `code` VARCHAR(100) NULL DEFAULT NULL AFTER `item_name`, ADD `manufacturer_id` INT(11) NULL DEFAULT NULL AFTER `code`, ADD `barcode_symbology` VARCHAR(50) NOT NULL AFTER `manufacturer_id`, ADD `upload_file` TEXT NULL DEFAULT NULL AFTER `barcode_symbology`, ADD `cost_price` DECIMAL(20,2) NOT NULL DEFAULT '0.00' AFTER `upload_file`;");
        $this->db->query("INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `status`) VALUES (NULL, 'pos_sales', 'admin/invoice/pos_sales', 'fa fa-circle-o', '12', '0', '1');");
        $this->db->query("UPDATE `tbl_config` SET `value` = '4.0.5' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
