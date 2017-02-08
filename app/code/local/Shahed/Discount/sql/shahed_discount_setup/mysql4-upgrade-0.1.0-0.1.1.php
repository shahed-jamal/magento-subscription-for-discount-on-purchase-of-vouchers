<?php
/**
 * Shahed Jamal
 * @category     Magento Module
 * @package      Discount
 * @copyright    Copyright (c) 2017 Shahed Jamal.
 * @purpose      Table creation for extension
 * @author       Shahed Jamal
 * @var $installer Mage_Core_Model_Resource_Setup
 **/
$installer = $this;
$installer->startSetup();
$installer->run("
	ALTER TABLE {$this->getTable('shahed_discount')}
	ADD COLUMN `product_id` int(11) unsigned NOT NULL AFTER `min_price`;
	");
$installer->endSetup();