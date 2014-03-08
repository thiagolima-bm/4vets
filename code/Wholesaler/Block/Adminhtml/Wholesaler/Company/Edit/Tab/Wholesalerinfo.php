<?php
 
class Acaldeira_wholesaler_Block_Adminhtml_wholesaler_Company_Edit_Tab_Wholesalerinfo extends Mage_Adminhtml_Block_Widget_Form
{
   protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('company_shipping', array('legend'=>Mage::helper('wholesaler')->__('Wholesaler configuration')));



        $availableMethods = Mage::getModel('mailtable/mailtable')->getCollection()->addFieldToFilter('active', array('=' => '1'));
       
        $methods = array();
        foreach ($availableMethods as $method){
            array_push($methods,array(
                'value' => $method->getCode(),
                'label' => Mage::helper('wholesaler')->__($method->getTitle().' ('.$method->getCode().')')
                ));
        }

        $fieldset->addField('servicos', 'multiselect', array(
            'label'     => Mage::helper('wholesaler')->__('Services'),
            'name'      => 'servicos',
            'values'    => $methods,
            'required'  => true,
        ));
        
        //add an empty value
        array_push($methods,array(
        'value' => "",
        'label' => Mage::helper('wholesaler')->__("Choose one free method")
        ));
        
        $fieldset->addField('free_shipping', 'select', array(
                'label'     => Mage::helper('wholesaler')->__('Free Shipping'),
                'name'      => 'free_shipping',
                'values'    => $methods,
                'required'  => false,
                'note'    => Mage::helper('wholesaler')->__('Only if wholesaler offer free shipping'),
        ));
        

        $fieldset->addField('min_value_free_shipping', 'text', array(
                'label'     => Mage::helper('wholesaler')->__('Minimum allowed value to free shipping (R$)'),
                'name'      => 'min_value_free_shipping',
        ));


        $fieldset->addField('mp', 'select', array(
            'label'     => Mage::helper('wholesaler')->__('MP'),
            'name'      => 'mp',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('wholesaler')->__('Yes'),
                ),
 
                array(
                    'value'     => 0,
                    'label'     => Mage::helper('wholesaler')->__('No'),
                ),
            ),
            'required'  => true,
        ));


        $fieldset->addField('aviso_recebimento', 'select', array(
            'label'     => Mage::helper('wholesaler')->__('Return Receipt'),
            'name'      => 'aviso_recebimento',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('wholesaler')->__('Yes'),
                ),
 
                array(
                    'value'     => 0,
                    'label'     => Mage::helper('wholesaler')->__('No'),
                ),
            ),
            'required'  => true,
        ));

        $fieldset->addField('valor_declarado', 'select', array(
            'label'     => Mage::helper('wholesaler')->__('Declared Value'),
            'name'      => 'valor_declarado',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('wholesaler')->__('Yes'),
                ),
 
                array(
                    'value'     => 0,
                    'label'     => Mage::helper('wholesaler')->__('No'),
                ),
            ),
            'required'  => true,
        ));
   

        $fieldset->addField('taxa_postagem', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Taxa de Postagem (R$ ou %)'),
            'required'  => true,
            'name'      => 'taxa_postagem',
            'note'    => 'Essa taxa(em R$) será adicionada ao valor do frete (para porcentagem adicione também "%" ex 5%)',
        ));

        $fieldset->addField('prazo_extra', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Adicionar ao prazo dos Correios (dias)'),
            'required'  => true,
            'name'      => 'prazo_extra',
            'note'    => 'Adicionará mais dias aos prazos fornecidos pelos Correios.',
        ));

        $fieldset->addField('cep', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('CEP de origem'),
            'required'  => true,
            'name'      => 'cep',
        ));

        $fieldset->addField('codigo_correios', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Código Administrativo dos Correios (Serviços Com Contrato)'),
            'name'      => 'codigo_correios',
        ));

        $fieldset->addField('senha_correios', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Senha Administrativa dos Correios (Serviços Com Contrato)'),
            'name'      => 'senha_correios',
            'note'    => 'Essa taxa será adicionada ao valor do frete.',
        ));




        if ( Mage::getSingleton('adminhtml/session')->getWholesalerShippingData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getWholesalerShippingData());
            Mage::getSingleton('adminhtml/session')->setWholesalerShippingData(null);
        } elseif ( Mage::registry('wholesalerConf') ) {
            $form->setValues(Mage::registry('wholesalerConf')->getData());
        }

        return parent::_prepareForm();
    }
}