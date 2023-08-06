<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_602 extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function up()
    {
        $this->db->query("ALTER TABLE `tbl_client` CHANGE `leads_id` `leads_id` INT (11) NULL DEFAULT NULL, CHANGE `active` `active` VARCHAR (20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `tbl_task` CHANGE `timer_started_by` `timer_started_by` VARCHAR (300) NULL DEFAULT NULL, CHANGE `start_time` `start_time` VARCHAR (300) NULL DEFAULT NULL, CHANGE `logged_time` `logged_time` VARCHAR (300) NULL DEFAULT '0';");
        $this->db->query("ALTER TABLE `tbl_warehouse` CHANGE `status` `status` ENUM('published','unpublished') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'published';");
        $this->db->query("ALTER TABLE `tbl_purchases` CHANGE `discount_type` `discount_type` VARCHAR (300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `tbl_opportunities` CHANGE `expected_revenue` `expected_revenue` VARCHAR (300) NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `tbl_kb_category` CHANGE `category` `category` VARCHAR (200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `tbl_stock` CHANGE `stock_sub_category_id` `stock_sub_category_id` INT (11) NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `tbl_employee_award` CHANGE `award_amount` `award_amount` INT (11) NULL DEFAULT NULL;");
        $this->db->query("ALTER TABLE `tbl_employee_award` CHANGE `user_id` `user_id` INT (11) NULL DEFAULT NULL, CHANGE `gift_item` `gift_item` VARCHAR (300) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `award_date` `award_date` VARCHAR (10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `view_status` `view_status` TINYINT(1) NULL DEFAULT '2' COMMENT '1=Read 2=Unread';");
        $this->db->query("ALTER TABLE `tbl_transactions` CHANGE `paid_by` `paid_by` INT (11) NULL DEFAULT '0';");
        $this->db->query("ALTER TABLE `tbl_estimates` CHANGE `discount_type` `discount_type` ENUM('none','before_tax','after_tax') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'none';");
        $this->db->query("ALTER TABLE `tbl_proposals` CHANGE `discount_type` `discount_type` ENUM('none','before_tax','after_tax') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'none';");
        $this->db->query("ALTER TABLE `tbl_credit_note` CHANGE `discount_type` `discount_type` ENUM('none','before_tax','after_tax') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'none';");
        $this->db->query("ALTER TABLE `tbl_purchases` CHANGE `discount_type` `discount_type` ENUM('none','before_tax','after_tax') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'none';");
        $this->db->query("ALTER TABLE `tbl_return_stock` CHANGE `discount_type` `discount_type` ENUM('none','before_tax','after_tax') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'none';");
        $this->db->query("UPDATE `tbl_config` SET `value` = '6.0.2' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
