<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose     Grid view to setup discount at admin
 * @author      Shahed Jamal
 **/
class Shahed_Discount_Block_Adminhtml_Discount_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        // Set some defaults for grid
        $this->setId('discount_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _getCollectionClass()
    {
        //This is the model using for grid
        return 'shahed_discount/discount_collection';
    }
    /**
     *  Get and set our collection for the grid
     *
     * @return Shahed_Discount_Block_Adminhtml_Discount
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Add the columns that should appear in the grid
     *
     * @return Mage_Adminhtml_Block_Catalog_Search_Grid
     */
    protected function _prepareColumns()
    {
        // Column for discount id
        $this->addColumn('discount_id',array(
            'header' => Mage::helper('shahed_discount')->__('ID'),
            'width' => '10px',
            'index' => 'discount_id',
        ));

        // Column for Discount name/title
        $this->addColumn('title',array(
            'header' => Mage::helper('shahed_discount')->__('Name'),
            'index' => 'title',
            'width' => '150px',
        ));

        // Column for minimum price
        $this->addColumn('min_price',array(
            'header' => Mage::helper('shahed_discount')->__('Minimum Price'),
            'index' => 'min_price',
            'width' => '10px',
        ));

        // Column for minimum price
        $this->addColumn('product_id',array(
            'header' => Mage::helper('shahed_discount')->__('Voucher Id'),
            'index' => 'product_id',
            'width' => '10px',
        ));

        // Column for minimum price
        $this->addColumn('discount_percentage',array(
            'header' => Mage::helper('shahed_discount')->__('Discount Percentage'),
            'index' => 'discount_percentage',
            'width' => '10px',
        ));

        // Column for minimum price
        $this->addColumn('discount_month',array(
            'header' => Mage::helper('shahed_discount')->__('Discount Month'),
            'index' => 'discount_month',
            'width' => '10px',
        ));

        // Column for status
        $this->addColumn('is_enabled', array(
            'header'	=> $this->__('Status'),
            'width'		=> '50px',
            'index'		=> 'is_enabled',
            'type'		=> 'options',
            'options'	=> array(
                1 => $this->__('Enabled'),
                0 => $this->__('Disabled'),
            ),
        ));

        // Column for Edit link
        $this->addColumn('action',
            array(
                'header'    => Mage::helper('shahed_discount')->__('Action'),
                'width'     => '30px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(array(
                    'caption' => Mage::helper('shahed_discount')->__('Edit'),
                    'url'     => array('base' => '*/*/edit'),
                    'field'   => 'id'
                )),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'discount',
            ));


        return parent::_prepareColumns();
    }

    /**
     * This is where row data will link to
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * Get the current URL for the grid
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * This is to set mass delete
     *
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('discount_id');
        $this->getMassactionBlock()->setFormFieldName('shahed_discount');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('shahed_discount')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('shahed_discount')->__('Are you sure?')
        ));
        return $this;
    }
}