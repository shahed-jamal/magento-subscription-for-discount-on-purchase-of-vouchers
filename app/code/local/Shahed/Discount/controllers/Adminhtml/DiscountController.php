<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose     Controller for adminhtml
 * @author      Shahed Jamal
 **/
class Shahed_Discount_Adminhtml_DiscountController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init Actions to set initial action
     *
     * @return $this
     */
    protected function _initAction()
    {
        // Load layout & set active menu
        $this->loadLayout()
            ->_setActiveMenu('discount/discount');
        return $this;
    }

    /**
     * Default index action
     */
    public function indexAction()
    {
        // Call initAction method which will set some basic params for each action
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Just forward the new action to a blank edit form
     */
    public function newAction()
    {
        //We just forward the new action to a blank edit form as the same form is used to create and edit
        $this->_forward('edit');
    }

    /**
     * Edit Action
     */
    public function editAction()
    {
        $this->_initAction();

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model=Mage::getModel('shahed_discount/discount');

        // 2. Initial checking
        if($id)
        {
            // Load record
            $model->load($id);
            //Check if record is loaded
            if(!$model->getId())
            {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This discount is no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        // 3. Set entered data if was error when we do save
        $data=Mage::getSingleton('adminhtml/session')->getDiscountData(true);
        if(!empty($data))
        {
            $model->addData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('shahed_discount', $model);

        $this->_addContent($this->getLayout()->createBlock('shahed_discount/adminhtml_discount_edit'))
            ->_addLeft($this->getLayout()->createBlock('shahed_discount/adminhtml_discount_edit_tabs'));
        $this->_addBreadcrumb($id ? $this->__('Edit Discount'):$this->__('New Discount'), $id ? $this->__('Edit Discount'):$this->__('New Discount'));
        $this->renderLayout();
    }

    /**
     * Save action to save form data
     */
    public function saveAction() {
        if($postData=$this->getRequest()->getPost())
        {
            $model=Mage::getSingleton('shahed_discount/discount');
            try
            {
                // Get post data and save to db
                $model->setData($postData);
                $model->save()->getId();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The discount has been saved.'));
                // The following line decides if it is a "save" or "save and continue"
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                }else{
                    $this->_redirect('*/*/');
                }
                //$this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessages());
            }
            catch (Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($this->__('An error occurred while saving this discount.'));
            }

            Mage::getSingleton('adminhtml/session')->setDiscountData($postData);
            $this->_redirectReferer();
        }
    }
    /**
     * Delete One or more than one row using massAction
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
                $model = Mage::getModel('shahed_discount/discount');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item is successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * Action to mass delete from admin grid
     */
    public function massDeleteAction() {
        $ids = $this->getRequest()->getParam('shahed_discount');
        if(!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select discount(s)'));
        } else {
            try {
                foreach ($ids as $id) {
                    $discount = Mage::getModel('shahed_discount/discount')->load($id);
                    $discount->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($ids)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * To work grid like ajax call
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('shahed_discount/adminhtml_discount_grid')->toHtml()
        );
    }

    /**
     *
     */
    public function messageAction()
    {
        $data=Mage::getModel('shahed_discount/discount')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('shahed_discount/shahed_discount_discount');
    }
}
