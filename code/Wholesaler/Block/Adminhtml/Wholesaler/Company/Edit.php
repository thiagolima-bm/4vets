<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Company_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'wholesaler';
        $this->_controller = 'adminhtml_wholesaler_company';
 
        $this->_updateButton('save', 'label', Mage::helper('wholesaler')->__('Save Wholesaler'));
        $this->_updateButton('delete', 'label', Mage::helper('wholesaler')->__('Delete Wholesaler'));
        $this->_updateButton('reset', 'style', 'display:none');
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('wholesalerCompanyInfo') && Mage::registry('wholesalerCompanyInfo')->getId() ) {
            return Mage::helper('wholesaler')->__("Edit Company '%s'", $this->htmlEscape(Mage::registry('wholesalerCompanyInfo')->getCompanyName()));
        } else {
            return Mage::helper('wholesaler')->__('Add Company');
        }
    }
}