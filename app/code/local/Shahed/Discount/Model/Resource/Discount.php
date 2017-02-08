<?php
/**
 * Shahed Technologies Pvt. Ltd.
 * @category    Shahed
 * @package     Discount
 * @copyright   Copyright (c) 2016 Shahed. (http://www.codilar.com)
 * @purpose     Initialize connection and define main table and primary key
 * @author      Shahed Team
 **/
class Codilar_Discount_Model_Resource_Discount extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('codilar_discount/discount', 'discount_id');
    }
}