<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Catalog_Product_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'wholesaler';
        $this->_controller = 'adminhtml_catalog_product';
 
        $this->_updateButton('save', 'label', Mage::helper('wholesaler')->__('Save Product'));
        $this->_updateButton('delete', 'label', Mage::helper('wholesaler')->__('Delete Product'));
        $this->_updateButton('reset', 'style', 'display:none');
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('wholesaler_product') && Mage::registry('wholesaler_product')->getId() ) {
            return Mage::helper('wholesaler')->__("Edit Product '%s'", $this->htmlEscape(Mage::registry('wholesaler_product')->getName()));
        } else {
            return Mage::helper('wholesaler')->__('Add Product');
        }
    }
}