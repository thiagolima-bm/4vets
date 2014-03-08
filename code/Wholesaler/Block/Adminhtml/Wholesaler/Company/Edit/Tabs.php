<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Company_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('company_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('wholesaler')->__('Wholesaler Information'));
    }
 
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('wholesaler')->__('Company Information'),
            'title'     => Mage::helper('wholesaler')->__('Company Information'),
            'content'   => $this->getLayout()->createBlock('wholesaler/adminhtml_wholesaler_company_edit_tab_form')->toHtml(),
        ));
       
        $this->addTab('shipping_section', array(
            'label'     => Mage::helper('wholesaler')->__('Wholesaler Configuration'),
            'title'     => Mage::helper('wholesaler')->__('Wholesaler Configuration'),
            'content'   => $this->getLayout()->createBlock('wholesaler/adminhtml_wholesaler_company_edit_tab_wholesalerinfo')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}