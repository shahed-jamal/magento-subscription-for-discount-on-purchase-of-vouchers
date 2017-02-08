<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose     Prepare form
 * @author      Shahed Jamal
 **/
class Shahed_Discount_Block_Adminhtml_Discount_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init class
     */
    public function _construct()
    {
        parent::_construct();

        $this->setId('shahed_discount_form');
        $this->setTitle($this->__('Discount Information'));
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
