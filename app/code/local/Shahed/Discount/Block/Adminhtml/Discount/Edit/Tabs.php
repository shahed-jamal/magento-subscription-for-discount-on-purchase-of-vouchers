<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose     Tabs admin edit
 * @author      Shahed Jamal
 **/
class Shahed_Discount_Block_Adminhtml_Discount_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('form_tabs');
        $this->setDestElementId('edit_form'); // this should be same as the form id define in Form.php
        $this->setTitle(Mage::helper('shahed_discount')->__('Discount Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('shahed_discount')->__('General'),
            'title'     => Mage::helper('shahed_discount')->__('General'),
            'content'   => $this->getLayout()->createBlock('shahed_discount/adminhtml_discount_edit_tab_form')->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}
