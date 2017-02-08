<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose      Table creation for extension
 * @author       Shahed Jamal
 * @var $installer Mage_Core_Model_Resource_Setup
 **/
$this->startSetup();
$this->run("
DROP TABLE IF EXISTS {$this->getTable('shahed_discount')};
		CREATE TABLE IF NOT EXISTS {$this->getTable('shahed_discount')} (
			`discount_id` int(11) unsigned NOT NULL auto_increment,
			`title` varchar(255) NOT NULL default '',
			`min_price` varchar(22) NOT NULL default '',
			`discount_percentage` int(5) NOT NULL,
			`discount_month` int(5) NOT NULL,
			`is_enabled` tinyint(1) unsigned NOT NULL,
			PRIMARY KEY (`discount_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;


		DROP TABLE IF EXISTS {$this->getTable('shahed_discount_history')};
		CREATE TABLE IF NOT EXISTS {$this->getTable('shahed_discount_history')} (
			`history_id` int(11) unsigned NOT NULL auto_increment,
			`customer_id` int(11) unsigned NOT NULL,
			`order_id` int(11) unsigned NOT NULL,
			`start_date` DATE NULL,
			`end_date` DATE NULL,
			`min_price` varchar(255) NOT NULL default '',
			`dis_percentage` int(5) NOT NULL,
			PRIMARY KEY (`history_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$this->endSetup();