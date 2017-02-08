<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose     Creating array for backend voucher amount setup
 * @author      Shahed Jamal
 **/
class Shahed_Discount_Model_Createoption extends Mage_Core_Model_Abstract
{
    public function getStatusValue(){
        return array(
            array('value' => '1000', 'label' => '1000'),
            array('value' => '1500', 'label' => '1500'),
            array('value' => '2000', 'label' => '2000'),
            array('value' => '2500', 'label' => '2500'),
            array('value' => '3000', 'label' => '3000'),
            array('value' => '3500', 'label' => '3500'),
            array('value' => '4000', 'label' => '4000'),
            array('value' => '4500', 'label' => '4500'),
            array('value' => '5000', 'label' => '5000'),
            array('value' => '5500', 'label' => '5500'),
            array('value' => '6000', 'label' => '6000'),
        );
    }
}