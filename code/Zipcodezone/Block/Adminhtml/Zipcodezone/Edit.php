<?php
 
class Acaldeira_Zipcodezone_Block_Adminhtml_Zipcodezone_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'zipcodezone';
        $this->_controller = 'adminhtml_zipcodezone';
 
        $this->_updateButton('save', 'label', Mage::helper('zipcodezone')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('zipcodezone')->__('Delete Item'));
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('zipcodezone_data') && Mage::registry('zipcodezone_data')->getId() ) {
            return Mage::helper('zipcodezone')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('zipcodezone_data')->getTitle()));
        } else {
            return Mage::helper('zipcodezone')->__('Add Item');
        }
    }
}