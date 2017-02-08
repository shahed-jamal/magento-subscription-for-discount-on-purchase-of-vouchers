<?php
/**
 * Shahed Technologies Pvt. Ltd.
 * @category    Shahed
 * @package    Discount
 * @copyright   Copyright (c) 2016 Shahed. (http://www.codilar.com)
 * @purpose     Collection of order
 * @author       Shahed Team
 **/
class Codilar_Discount_Model_Resource_History_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('codilar_discount/history');
    }
}
