<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose     Admin block
 * @author      Shahed Jamal
 **/
class Shahed_Discount_Block_Adminhtml_Discount extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function _construct()
    {
        // The blockGroup must match the first half of how we call the block, and controller matches the second half
        // ie. shahed_discount/adminhtml_discount
        $this->_blockGroup = 'shahed_discount';
        $this->_controller = 'adminhtml_discount';
        $this->_headerText = Mage::helper('shahed_discount')->__('Discount Manager');
        $this->_addButtonLabel = Mage::helper('shahed_discount')->__('Add Discount');
        parent::_construct();
    }
}