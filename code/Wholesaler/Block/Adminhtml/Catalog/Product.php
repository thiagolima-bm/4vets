<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Catalog_Product extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_catalog_product';
        $this->_blockGroup = 'wholesaler';
        $this->_headerText = Mage::helper('wholesaler')->__('Product Manager');
       
        $this->_addButtonLabel = Mage::helper('wholesaler')->__('Add Product');

        parent::__construct();
    }
}