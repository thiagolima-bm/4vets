<?php
 
class Acaldeira_wholesaler_Block_Adminhtml_wholesaler_Company_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('company_form', array('legend'=>Mage::helper('wholesaler')->__('Company Information')));
        

        $options = array();
        $collection = Mage::getModel('admin/user')->getCollection();//addAttributeToFilter('role_id',5);

         //$collection->getSelect()->where('parent_id',5);
        $rolesCol = Mage::getModel('admin/role')->getCollection();
        $rolesCol->addFieldToFilter('role_name',array('='=>'wholesaler'));
        $rolesCol->getFirstItem();
        $wholesalerRoleId = $rolesCol->getFirstItem()->getId();

        $states = array();
        $regionsCollection = Mage::getResourceModel('directory/region_collection')->addCountryFilter('BR')->load();
        foreach ($regionsCollection as $key => $value) {
            $states[$value->getCode()] = $value->getName();
        }
        
        //$wholesalerRoleId = 5;//$rolesCol->getRoleId();
        foreach($collection as $wholesaler){
            $roles = $wholesaler->getRoles();
            
            if(in_array($wholesalerRoleId,$roles)) {

                $option = array('value' =>$wholesaler->getId(),
                                'label' =>$wholesaler->getFirstname()
                                );

                array_push($options, $option);    
            }
            
        }

        $fieldset->addField('company_name', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Company Name'),
            'required'  => true,
            'name'      => 'company_name',
          //  'note'		=> Mage::helper('catalog')->__('Name that will be shown')
        ));

        if (!$this->getRequest()->getParam('id')) {
            $fieldset->addField('username', 'text', array(
                'label'     => Mage::helper('wholesaler')->__('User Name'),
                'required'  => true,
                'class'     => 'validate-length',
                'name'      => 'username',
                'note' => Mage::helper('catalog')->__('Username to access magento admin'),
            ));

            $fieldset->addField('password', 'password', array(
                'label'     => Mage::helper('wholesaler')->__('Password'),
                'required'  => true,
                'name'      => 'password',
            ));
        } else {
             $fieldset->addField('user_id', 'hidden', array(
                'name'      => 'user_id',
            ));
        }

        $fieldset->addField('corporate_name', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Corporate Name'),
            'required'  => true,
            'name'      => 'corporate_name',
        ));

        $fieldset->addField('cnpj', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('CNPJ'),
            'required'  => true,
            'name'      => 'cnpj',
        ));

        $fieldset->addField('inscription_municipal', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Registration'),
            'name'      => 'inscription_municipal',
        ));

        $fieldset->addField('owner_name', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Partner name'),
            'required'  => true,
            'name'      => 'owner_name',
        ));
        
        $fieldset->addField('email', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Email'),
            'required'  => true,
            'name'      => 'email',
        ));

        $fieldset->addField('tel', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Telephone'),
            'required'  => true,
            'name'      => 'tel',
        ));

        $fieldset->addField('tel_alternative', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Alternative Telephone'),
            'name'      => 'tel_alternative',
        ));

        $fieldset->addField('cpf', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('CPF'),
            'required'  => true,
            'name'      => 'cpf',
        ));

        $fieldset->addField('rg', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('RG'),
            'name'      => 'rg',
        ));
        
        $fieldset->addField('address', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Address'),
            'required'  => true,
            'name'      => 'address',
        ));

        $fieldset->addField('district', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('District'),
            'required'  => true,
            'name'      => 'district',
        ));

        $fieldset->addField('complement', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Complement'),
            'required'  => false,
            'name'      => 'complement',
        ));

        $fieldset->addField('city', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('City'),
            'required'  => true,
            'name'      => 'city',
        ));

        $fieldset->addField('state', 'select', array(
            'label'     => Mage::helper('wholesaler')->__('State'),
            'required'  => true,
            'name'      => 'state',
            'values' => $states,
        ));

        $fieldset->addField('cep', 'text', array(
            'label'     => Mage::helper('wholesaler')->__('Cep'),
            'required'  => true,
            'name'      => 'cep2',
        ));

        if ( Mage::getSingleton('adminhtml/session')->getwholesalerData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getwholesalerData());
            Mage::getSingleton('adminhtml/session')->setwholesalerData(null);
        } elseif ( Mage::registry('wholesalerCompanyInfo') ) {
            $form->setValues(Mage::registry('wholesalerCompanyInfo')->getData());
        }
        return parent::_prepareForm();
    }
}