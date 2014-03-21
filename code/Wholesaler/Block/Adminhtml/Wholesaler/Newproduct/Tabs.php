<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Newproduct_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('wholesaler_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('wholesaler')->__('Product Information'));
    }
 
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('wholesaler')->__('Product Information'),
            'title'     => Mage::helper('wholesaler')->__('Product Information'),
            'content'   => $this->getLayout()->createBlock('wholesaler/adminhtml_wholesaler_newproduct_tab_form')->toHtml(),
        ));

        // $this->addTab('form_section_price', array(
        //     'label'     => Mage::helper('wholesaler')->__('Price'),
        //     'title'     => Mage::helper('wholesaler')->__('Price'),
        //     'content'   => $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_price')->toHtml(),
        // ));

        
       
       
        return parent::_beforeToHtml();
    }
}