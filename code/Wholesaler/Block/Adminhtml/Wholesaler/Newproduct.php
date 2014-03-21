<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Newproduct extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'wholesaler';
        $this->_controller = 'adminhtml_wholesaler';
        $this->_mode = 'newproduct';


        /*
         
         if ($this->_blockGroup && $this->_controller && $this->_mode) {
             $this->setChild('form', $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form'));
         }

        */
        

        $this->_updateButton('save', 'label', Mage::helper('wholesaler')->__('Save Product'));
        $this->_updateButton('delete', 'label', Mage::helper('wholesaler')->__('Delete Product'));
    
        
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('wholesaler_data') && Mage::registry('wholesaler_data')->getId() ) {
            return Mage::helper('wholesaler')->__("Edit Product '%s'", $this->htmlEscape(Mage::registry('wholesaler_data')->getName()));
        } else {
            return Mage::helper('wholesaler')->__('Add Product');
        }
    }


}