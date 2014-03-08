<?php
 
class Acaldeira_Zipcodezone_Block_Adminhtml_Zipcodezone_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('zipcodezone_form', array('legend'=>Mage::helper('zipcodezone')->__('Item information')));
        
        $fieldset->addField('zonename', 'text', array(
            'label'     => Mage::helper('zipcodezone')->__('Zone Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'zonename',
        ));

        $fieldset->addField('start', 'text', array(
            'label'     => Mage::helper('zipcodezone')->__('Zip Code Start'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'start',
        ));

         $fieldset->addField('end', 'text', array(
            'label'     => Mage::helper('zipcodezone')->__('Zip Code End'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'end',
        ));


        //  $fieldset->addField('contract', 'select', array(
        //     'label'     => Mage::helper('zipcodezone')->__('Com contrato?'),
        //     'name'      => 'contract',
        //     'values'    => array(
        //         array(
        //             'value'     => 1,
        //             'label'     => Mage::helper('zipcodezone')->__('Yes'),
        //         ),
 
        //         array(
        //             'value'     => 0,
        //             'label'     => Mage::helper('zipcodezone')->__('No'),
        //         ),
        //     ),
        // ));
 
       
        if ( Mage::getSingleton('adminhtml/session')->getzipcodezoneData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getzipcodezoneData());
            Mage::getSingleton('adminhtml/session')->setzipcodezoneData(null);
        } elseif ( Mage::registry('zipcodezone_data') ) {
            $form->setValues(Mage::registry('zipcodezone_data')->getData());
        }
        return parent::_prepareForm();
    }
}