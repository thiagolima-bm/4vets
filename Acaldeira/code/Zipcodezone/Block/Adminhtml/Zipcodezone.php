<?php
class Acaldeira_Zipcodezone_Block_Adminhtml_Zipcodezone extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_zipcodezone';
        $this->_blockGroup = 'zipcodezone';
        $this->_headerText = Mage::helper('zipcodezone')->__('Item Manager');
        $this->_addButtonLabel = Mage::helper('zipcodezone')->__('Add Item');
        parent::__construct();
    }
}