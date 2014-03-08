<?php
 
class Acaldeira_Wholesaler_Block_Adminhtml_Catalog_Product_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('product_form', array('legend'=>Mage::helper('wholesaler')->__('Product Information')));

        if ($this->getRequest()->getParam('id')) {
            
            $fieldset->addField('entity_id', 'hidden', array(
                'name'      => 'product_id',
            ));  
        } 
        
        
        $fieldset->addField('name', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Product Name'),
            'required'  => true,
            'name'      => 'name',
          //  'note'		=> Mage::helper('catalog')->__('Name that will be shown')
        ));

        $fieldset->addField('description', 'textarea', array(
            'label'     => Mage::helper('wholesaler')->__('Description'),
            'required'  => true,
            'name'      => 'description',
          //  'note'        => Mage::helper('catalog')->__('Name that will be shown')
        ));

        $fieldset->addField('price', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Price'),
            'required'  => true,
            'name'      => 'price',
          //  'note'        => Mage::helper('catalog')->__('Name that will be shown')
        ));

         $fieldset->addField('qty', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Qty'),
            'required'  => true,
            'name'      => 'qty',
          //  'note'        => Mage::helper('catalog')->__('Name that will be shown')
        ));

         $fieldset->addField('weight', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Weight'),
            'required'  => true,
            'name'      => 'weight',
            'note'        => Mage::helper('wholesaler')->__('Product Weight in kilos')
        ));

        

        if ( Mage::getSingleton('adminhtml/session')->getwholesalerData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getwholesalerData());
            Mage::getSingleton('adminhtml/session')->setwholesalerData(null);
        } elseif ( Mage::registry('wholesaler_product') ) {
            $form->setValues(Mage::registry('wholesaler_product')->getData());
        }
        return parent::_prepareForm();
    }
}