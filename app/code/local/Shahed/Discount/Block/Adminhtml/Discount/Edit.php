<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose     Edit row
 * @author      Shahed Jamal
 **/
class Shahed_Discount_Block_Adminhtml_Discount_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'shahed_discount';
        $this->_controller = 'adminhtml_discount';

        $this->_updateButton('save', 'label', Mage::helper('shahed_discount')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('shahed_discount')->__('Delete'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('shahed_discount')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
        $this->_formScripts[] = "function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
            ";
    }

    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('shahed_discount')->getId()) {
            return Mage::helper('shahed_discount')->__('Edit discount');
        }
        else {
            return Mage::helper('shahed_discount')->__('Add a new discount');
        }
    }
}