<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('productGrid');
        // This is the primary key of the database
        $this->setDefaultSort('name');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        
    
        $seller = Mage::getSingleton('admin/session')->getUser();
        $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
        $collection->addAttributeToSelect('*');
        

        $collection->addFieldToFilter('storecode',Bm_Cmon::getStorecode());
    
        $this->setCollection($collection);

        return parent::_prepareCollection();
        
    }
 
    protected function _prepareColumns()
    {
         $this->addColumn('name', array(
            'header'    => Mage::helper('adminhtml')->__('Name'),
            'align'     =>'left',
            'index'     => 'name',
            'actions'   => array(
                array(
                    'caption'   => __('Edit'),
                    'url'       => array('base'=> '*/*/edit'),
                    'field'     => 'id'
                )
            ),
        ));

         $this->addColumn('price', array(
            'header'    => Mage::helper('adminhtml')->__('Price'),
            'align'     =>'left',
            'width'     => '200px',
            'index'     => 'price',
        ));
         /*
         $this->addColumn('email', array(
            'header'    => Mage::helper('wholesaler')->__('Email'),
            'align'     =>'left',
            'width'     => '300px',
            'index'     => 'email',
        ));

         $this->addColumn('cnpj', array(
            'header'    => Mage::helper('wholesaler')->__('Cnpj'),
            'align'     =>'left',
            'width'     => '250px',
            'index'     => 'cnpj',
        ));

         $this->addColumn('created', array(
            'header'    => Mage::helper('wholesaler')->__('Created'),
            'align'     =>'left',
            'width'     => '150px',
            'index'     => 'created',
            'type'      => 'datetime',
        ));
       
       
 
        $this->addColumn('title', array(
            'header'    => Mage::helper('wholesaler')->__('Title'),
            'align'     =>'left',
            'index'     => 'title',
        ));
 
        /*
        $this->addColumn('content', array(
            'header'    => Mage::helper('wholesaler')->__('Item Content'),
            'width'     => '150px',
            'index'     => 'content',
        ));
        
 
        $this->addColumn('created_time', array(
            'header'    => Mage::helper('wholesaler')->__('Creation Time'),
            'align'     => 'left',
            'width'     => '120px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'created_time',
        ));
 
        $this->addColumn('update_time', array(
            'header'    => Mage::helper('wholesaler')->__('Update Time'),
            'align'     => 'left',
            'width'     => '120px',
            'type'      => 'date',
            'default'   => '--',
            'index'     => 'update_time',
        ));   
 
 
        $this->addColumn('status', array(
 
            'header'    => Mage::helper('wholesaler')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => 'Active',
                0 => 'Inactive',
            ),
        ));
        */
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/editProduct', array('id' => $row->getId()));
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
 
 
 
}