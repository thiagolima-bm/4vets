<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Company extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_wholesaler_company';
        $this->_blockGroup = 'wholesaler';
        $this->_headerText = Mage::helper('wholesaler')->__('Wholesaler Manager');
       
        $this->_addButtonLabel = Mage::helper('wholesaler')->__('Add Wholesaler');

        parent::__construct();
    }
}