<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose     Tabs of admin form
 * @author      Shahed Jamal
 **/
class Shahed_Discount_Block_Adminhtml_Discount_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = Mage::registry('shahed_discount');
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('discount_form', array('legend'=>Mage::helper('shahed_discount')->__('General')));

        if($model->getId())
        {
            $fieldset->addField('discount_id','text',array(
                'name'      => 'discount_id',
                'label'     => Mage::helper('shahed_discount')->__('ID'),
                'class'     => 'required-entry',
                'readonly'  => true,
                'required'  => true,
            ));
        }

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('shahed_discount')->__('Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));
        
        $fieldset->addField('min_price', 'select', array(
            'name' => 'min_price',
            'label' => Mage::helper('shahed_discount')->__('Minimum Price'),
            'class'     => 'required-entry',
            'required' => true,
            'values' => Mage::getModel('shahed_discount/createoption')->getStatusValue(),
        ));

        $fieldset->addField('product_id', 'text', array(
            'label'     => Mage::helper('shahed_discount')->__('Voucher Product Id'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'product_id',
        ));

        $fieldset->addField('discount_percentage', 'text', array(
            'label'     => Mage::helper('shahed_discount')->__('Discount Percentage'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'discount_percentage',
        ));

        $fieldset->addField('discount_month', 'text', array(
            'label'     => Mage::helper('shahed_discount')->__('Discount Month'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'discount_month',
        ));

        $fieldset->addField('is_enabled', 'select', array(
            'label'     => Mage::helper('shahed_discount')->__('Enable'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'is_enabled',
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));
        //print '<pre>';var_dump($model->getData());
        $form->setValues($model->getData());

        return parent::_prepareForm();
    }
}
