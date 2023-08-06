<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_600 extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function up()
    {
        // check module field exist or not into tbl_attachments table
        $this->db->query("ALTER TABLE `tbl_purchases` ADD `stock_updated` ENUM('Yes','No') NOT NULL DEFAULT 'No' AFTER `update_stock`;");
        $this->db->query("INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`) VALUES
(NULL, 'warning', 'admin/warning', 'fa fa-warning', 0, 3, '2022-08-06 15:47:01', 1),
(NULL, 'promotion', 'admin/promotion', 'fa fa-arrow-circle-up', 0, 4, '2022-08-06 15:49:28', 1),
(NULL, 'termination', 'admin/termination', 'fa fa-eraser', 0, 5, '2022-08-06 15:50:45', 1),
(NULL, 'resignation', 'admin/resignation', 'fa fa-scissors', 0, 6, '2022-08-06 15:54:31', 1);");
        $this->db->query("INSERT INTO `tbl_email_templates` (`email_templates_id`, `code`, `email_group`, `subject`, `template_body`) VALUES (NULL, 'en', 'promotion_email', 'You have been promoted', '<p>Hi {NAME},</p>\r\n\r\n<p>Congratulations! you have been promoted to {DESIGNATIONS}</p>\r\n\r\n<br>\r\nRegards<br>\r\n<br>\r\nThe {SITE_NAME} Team</p>');
INSERT INTO `tbl_email_templates` (`email_templates_id`, `code`, `email_group`,`subject`, `template_body`) VALUES (NULL, 'en', 'warning_email', 'A Warning Message', '<p>{NAME} i am Warning to you </p>\r\n');
UPDATE `tbl_email_templates` SET `template_body` = '<p>{NAME} i am Warning to you </p>\r\n\r\n<p>{DESCRIPTION}</p>\r\n\r\n<br>\r\nRegards,<br>\r\n<br>\r\nThe {SITE_NAME} Team</p>' WHERE `tbl_email_templates`.`email_templates_id` = 77;
INSERT INTO `tbl_config` (`config_key`, `value`) VALUES ('warning_email', '1');");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_promotions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `designation_id` int NOT NULL,
  `from_designations` int NOT NULL,
  `promotion_title` varchar(190) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `promotion_date` date NOT NULL,
  `description` varchar(190) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_resignations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `notice_date` date NOT NULL,
  `resignation_date` date NOT NULL,
  `description` varchar(190) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_terminations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `attachment` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `notice_date` date NOT NULL,
  `termination_date` date NOT NULL,
  `termination_type` varchar(190) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` varchar(190) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `tbl_warnings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `warning_to` int NOT NULL,
  `warning_by` int NOT NULL,
  `warning_type` int NOT NULL,
  `attachment` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `subject` varchar(190) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `warning_date` date NOT NULL,
  `description` varchar(190) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;");
        $this->db->query("UPDATE `tbl_config` SET `value` = '6.0.0' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
