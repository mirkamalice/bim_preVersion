<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_409 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_award_points` (
            `award_points_id` int NOT NULL AUTO_INCREMENT,
            `client_id` int NOT NULL,
            `user_id` int NOT NULL,
            `client_award_point` varchar(100) NOT NULL,
            `user_award_point` varchar(100) NOT NULL,
            `invoices_id` int NOT NULL,
            `payment_status` varchar(100) NOT NULL,
            `date` varchar(40) NOT NULL,
            PRIMARY KEY (`award_points_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_award_program` (
            `award_program_id` int NOT NULL AUTO_INCREMENT,
            `program_name` varchar(100) NOT NULL,
            `award_rule_id` int NOT NULL,
            `client_id` int NOT NULL,
            `start_date` varchar(64) NOT NULL,
            `end_date` varchar(64) NOT NULL,
            `description` varchar(200) NOT NULL,
            `status` int NOT NULL,
            PRIMARY KEY (`award_program_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_award_rule` (
            `award_rule_id` int NOT NULL AUTO_INCREMENT,
            `rule_name` varchar(200) NOT NULL,
            `date_create` date NOT NULL,
            `client_id` int NOT NULL,
            `award_point_from` varchar(20) NOT NULL,
            `award_point_to` varchar(20) NOT NULL,
            `card` int NOT NULL,
            `description` text NOT NULL,
            PRIMARY KEY (`award_rule_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_card_config` (
            `card_config_id` int NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `date_create` date DEFAULT NULL,
            `subject_card` int DEFAULT '0',
            `client_name` int DEFAULT '0',
            `membership` int DEFAULT '0',
            `company_name` int DEFAULT '0',
            `member_since` int DEFAULT '0',
            `custom_field` int DEFAULT '0',
            `custom_field_content` varchar(200) DEFAULT NULL,
            `text_color` varchar(25) DEFAULT NULL,
            PRIMARY KEY (`card_config_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_transfer_item` (
            `transfer_item_id` int NOT NULL AUTO_INCREMENT,
            `reference_no` varchar(50) DEFAULT NULL,
            `date` varchar(100) DEFAULT NULL,
            `status` enum('pending','complete','send','approved','rejected') DEFAULT NULL,
            `shipping_cost` varchar(100) DEFAULT NULL,
            `notes` text,
            `attachment` text,
            `from_warehouse_id` int NOT NULL,
            `to_warehouse_id` int NOT NULL,
            `user_id` int DEFAULT NULL,
            `show_quantity_as` varchar(20) DEFAULT NULL,
            `tax` decimal(18,3) DEFAULT NULL,
            `total_tax` text NOT NULL,
            `permission` text,
            PRIMARY KEY (`transfer_item_id`),
            UNIQUE KEY `reference_no` (`reference_no`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_transfer_itemlist` (
            `transfer_itemList_id` int NOT NULL AUTO_INCREMENT,
            `transfer_item_id` int NOT NULL,
            `saved_items_id` int DEFAULT '0',
            `warehouse_id` int DEFAULT NULL,
            `item_tax_rate` decimal(10,2) DEFAULT '0.00',
            `item_tax_name` text,
            `item_name` varchar(150) DEFAULT 'Item Name',
            `item_desc` longtext,
            `unit_cost` decimal(10,2) DEFAULT '0.00',
            `quantity` decimal(10,2) DEFAULT '0.00',
            `item_tax_total` decimal(10,2) DEFAULT '0.00',
            `total_cost` decimal(10,2) DEFAULT '0.00',
            `date_saved` timestamp NOT NULL DEFAULT '2018-12-12 04:00:00',
            `unit` varchar(200) DEFAULT NULL,
            `hsn_code` text,
            `order` int DEFAULT '0',
            PRIMARY KEY (`transfer_itemList_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_warehouse` (
    `warehouse_id` int NOT NULL AUTO_INCREMENT,
    `warehouse_code` varchar(100) DEFAULT NULL,
    `warehouse_name` varchar(250) DEFAULT NULL,
    `phone` varchar(20) DEFAULT NULL,
    `mobile` varchar(20) DEFAULT NULL,
    `email` varchar(50) DEFAULT NULL,
    `address` text,
    `image` text,
    `permission` text,
    `status` enum('published','unpublished') NOT NULL DEFAULT 'published',
    PRIMARY KEY (`warehouse_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_warehouses_products` (
          `id` int NOT NULL AUTO_INCREMENT,
          `product_id` int NOT NULL,
          `warehouse_id` int NOT NULL,
          `quantity` decimal(15,4) NOT NULL,
          `rack` varchar(55) DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `product_id` (`product_id`),
          KEY `warehouse_id` (`warehouse_id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

        $this->db->query("INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`) VALUES
        (186, 'warehouse', 'admin/warehouse/manage', 'fa fa-building-o', 150, 3, '2021-10-17 12:24:24', 1),
        (187, 'transferItem', 'admin/items/transferItem', 'fa fa-circle-o', 150, 4, '2021-10-13 14:56:08', 1),
        (188, 'transfer_settings', 'admin/settings/transfer', 'fa-fw icon-handbag', 25, 13, '2021-10-20 13:32:34', 2),
        (203, 'award_setting', 'admin/settings/award', 'fa fa-star', 25, 30, '2022-01-18 08:51:40', 2),
        (204, 'award_rule_setting', 'admin/settings/award_rule_settingh', 'fa fa-star', 25, 31, '2022-01-18 12:53:50', 2),
        (205, 'award_program_settings', 'admin/settings/award_program_settings', 'fa fa-cog', 25, 32, '2022-01-19 12:17:30', 2),
        (206, 'client_award_points', 'admin/invoice/client_awards', 'fa fa-circle-o', 12, 10, '2022-01-20 04:39:41', 1),
        (207, 'best_selling_product', 'admin/best_selling', 'fa fa-circle-o', 12, 11, '2022-01-20 09:43:52', 1);");

        $this->db->query("INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `status`) VALUES (NULL, 'transfer_settings', 'admin/settings/transfer', 'fa-fw icon-handbag', '25', '13', '2');");
        $this->db->query("ALTER TABLE `tbl_task_attachment` ADD `module` VARCHAR(50) NULL DEFAULT NULL AFTER `bug_id`, ADD `module_field_id` INT(11) NULL DEFAULT NULL AFTER `module`;");
        $this->db->query("ALTER TABLE `tbl_task` ADD `module` VARCHAR(50) NULL DEFAULT NULL AFTER `bug_id`, ADD `module_field_id` INT(11) NULL DEFAULT NULL AFTER `module`;");
        $this->db->query("ALTER TABLE `tbl_leads_notes` ADD `module` VARCHAR(50) NULL DEFAULT NULL AFTER `user_id`, ADD `module_field_id` INT(11) NULL DEFAULT NULL AFTER `module`");
        $this->db->query("ALTER TABLE `tbl_invoices` ADD `warehouse_id` INT(11) NULL DEFAULT NULL AFTER `show_quantity_as`;");
        $this->db->query('ALTER TABLE `tbl_estimates` ADD `warehouse_id` INT(11) NULL DEFAULT NULL AFTER `invoices_id`');
        $this->db->query('ALTER TABLE `tbl_proposals` ADD `warehouse_id` INT(11) NULL DEFAULT NULL AFTER `discount_total`');
        $this->db->query('ALTER TABLE `tbl_return_stock` ADD `warehouse_id` INT(11) NULL DEFAULT NULL AFTER `invoices_id`');
        $this->db->query('ALTER TABLE `tbl_purchases` ADD `warehouse_id` INT(11) NULL DEFAULT NULL AFTER `created_by`');
        $this->db->query('ALTER TABLE `tbl_credit_note` ADD `warehouse_id` INT(11) NULL DEFAULT NULL AFTER `show_quantity_as`');
        $this->db->query('ALTER TABLE `tbl_transactions` ADD `warehouse_id` INT(11) NULL DEFAULT NULL AFTER `invoices_id`;');
        $this->db->query('ALTER TABLE `tbl_account_details` ADD `warehouse_id` INT(11) NULL DEFAULT NULL AFTER `employment_id`;');
        $this->db->query("UPDATE `tbl_config` SET `value` = '4.0.9' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
