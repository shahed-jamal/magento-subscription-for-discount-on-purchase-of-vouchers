<?php
/**
 * Shahed Technologies Pvt. Ltd.
 * @category    Shahed
 * @package    Discount
 * @copyright   Copyright (c) 2016 Shahed. (http://www.codilar.com)
 * @purpose     Resource of history
 * @author       Shahed Team
 **/
class Codilar_Discount_Model_Resource_History extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('codilar_discount/history','history_id');
    }
}