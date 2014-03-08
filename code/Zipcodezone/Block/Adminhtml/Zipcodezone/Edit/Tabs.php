<?php
 
class Acaldeira_Zipcodezone_Block_Adminhtml_Zipcodezone_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('zipcodezone_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('zipcodezone')->__('News Information'));
    }
 
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('zipcodezone')->__('Item Information'),
            'title'     => Mage::helper('zipcodezone')->__('Item Information'),
            'content'   => $this->getLayout()->createBlock('zipcodezone/adminhtml_Zipcodezone_edit_tab_form')->toHtml(),
        ));
       
        return parent::_beforeToHtml();
    }
}