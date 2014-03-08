<?php


class Acaldeira_Wholesaler_Adminhtml_WholesalerController extends Mage_Adminhtml_Controller_Action
{


	protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('wholesaler/company')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Wholesaler Manager'), Mage::helper('adminhtml')->__('Wholesaler Manager'));
        return $this;
    }


    /**
	* List wholesalers
	*
	*/
    public function indexAction() 
    {

        $this->_initAction();       
        
    	//$this->loadLayout();
        //$this->_addContent($this->getLayout()->createBlock('wholesaler/adminhtml_wholesaler_company'));
        $this->renderLayout();
    }


	/**
	* Create wholesaler
	*
	*/
	public function newAction()
	{
		$this->_redirect('*/*/edit');
	}

	/**
	* Edit wholesaler
	*
	*/
	public function editAction()
	{

        $wholesalerId     = $this->getRequest()->getParam('id');
        $wholesalerCompanyInfo  = Mage::getModel('wholesaler/company')->load($wholesalerId);
        $wholesalerConf  = Mage::getModel('wholesaler/wholesaler')->load($wholesalerCompanyInfo->getWholesalerId());
        
        $wholesalerConf->setServicos(explode("|", $wholesalerConf->getServicos()));

         if ($wholesalerCompanyInfo->getId()) {

            Mage::register('wholesalerCompanyInfo', $wholesalerCompanyInfo);
            Mage::register('wholesalerConf', $wholesalerConf);

        }

            $this->loadLayout();
            $this->_setActiveMenu('wholesaler/company');    

            $this->_addContent($this->getLayout()->createBlock('wholesaler/adminhtml_wholesaler_company_edit'))
                     ->_addLeft($this->getLayout()->createBlock('wholesaler/adminhtml_wholesaler_company_edit_tabs'));
                   
            $this->renderLayout(); 

	}

	/**
	* Delete wholesaler
	*
	*/
	public function deleteAction()
    {
        if( $this->getRequest()->getParam('id') > 0 ) {
            try {
               
                
                $wholesalerCompany = Mage::getModel('wholesaler/company')->load($this->getRequest()->getParam('id'));
                $wholesaler = Mage::getModel('wholesaler/wholesaler')->load($wholesalerCompany->getWholesalerId());
                $userModel = Mage::getModel('admin/user')->load($wholesaler->getUserId());
                $wholesalerCompany->delete();
                $wholesaler->delete();
                $userModel->delete();
                

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('wholesaler')->__('Wholesaler was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

	/**
	* Saves wholesaler
	*
	*/
	public function saveAction()
	{
		 if ( $this->getRequest()->getPost() ) {
            try {

                $postData = $this->getRequest()->getPost();

                $wholesalerId = $this->getRequest()->getParam('id');

                if($wholesalerId){

                     $wholesalerCompanyInfo = Mage::getModel('wholesaler/company')->load($wholesalerId);
                     $wholesalerConf = Mage::getModel('wholesaler/wholesaler')->load($wholesalerCompanyInfo->getWholesalerId());
                     $userId = $wholesalerConf->getUserId();

                }else{
                    $wholesalerCompanyInfo = Mage::getModel('wholesaler/company');
                    $userModel = Mage::getModel('admin/user');
                    $wholesalerConf = Mage::getModel('wholesaler/wholesaler');

                    /* generates storecode */
                    $wholesalerConf->setStorecode(Bm_Cmon::genStorecode());

                    $wholesalerCompanyInfo->setCreated(Mage::getModel('core/date')->timestamp(time()));


                    $userModel->setUsername($postData['username'])
                        ->setFirstname($postData['owner_name'])
                        ->setLastname($postData['company_name'])
                        ->setPassword($postData['password'])
                        ->setEmail($postData['email'])
                        ->setIsActive(true);

                        $userModel->save();

                        $rolesModel = Mage::getModel('admin/role')->getCollection();
                        $rolesModel->addFieldToFilter('role_name', array('=' => 'Wholesaler'));
                        $wholesalerRoleId = $rolesModel->getFirstItem()->getId();

                        $userModel->setRoleIds(array($wholesalerRoleId))
                            ->setRoleUserId($userModel->getUserId())
                            ->saveRelations();

                        $userId = $userModel->getId();
                }

                
                $zipcodezone = Mage::getModel('zipcodezone/zipcodezone')->getCollection()
                ->addFieldToFilter('start', array('lteq' => $postData['cep']))
                ->addFieldToFilter('end', array('gteq' => $postData['cep']))
                ->getFirstItem();
                
               
                $postData['servicos'] = implode("|", $postData['servicos']);
                $wholesalerConf->setServicos($postData['servicos'])
                    ->setMp($postData['mp'])
                    ->setAvisoRecebimento($postData['aviso_recebimento'])
                    ->setValorDeclarado($postData['valor_declarado'])
                    ->setTaxaPostagem($postData['taxa_postagem'])
                    ->setPrazoExtra($postData['prazo_extra'])
                    ->setCep($postData['cep'])
                    ->setCodigoCorreios($postData['codigo_correios'])
                    ->setSenhaCorreios($postData['senha_correios'])
                    ->setUserId($userId)
                    ->setFreeShipping($postData['free_shipping'])
                    ->setMinValueFreeShipping($postData['min_value_free_shipping'])
                    ->setZipcodezone($zipcodezone->getId())
                    ->save();

                 $wholesalerCompanyInfo->setWholesalerId($wholesalerConf->getId())
                    ->setCompanyName($postData['company_name'])
                    ->setCorporateName($postData['corporate_name'])
                    ->setCnpj($postData['cnpj'])
                    ->setInscriptionMunicipal($postData['inscription_municipal'])
                    ->setOwnerName($postData['owner_name'])
                    ->setTel($postData['tel'])
                    ->setTelAlternative($postData['tel_alternative'])
                    ->setCpf($postData['cpf'])
                    ->setRg($postData['rg'])
                    ->setAddress($postData['address'])
                    ->setDistrict($postData['district'])
                    ->setComplement($postData['complement'])
                    ->setCity($postData['city'])
                    ->setState($postData['state'])
                    ->setCep($postData['cep2'])
                    ->setEmail($postData['email']);


                if($wholesalerCompanyInfo->save()){
               
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('wholesaler')->__('Company info was successfully saved'));
                    Mage::getSingleton('adminhtml/session')->setCompanyData(false);
                }
 
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {

                if (!isset($postData['user_id'])) {
                    $userModel->delete();
                }
                
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCompanyData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
	}


    /**
    * Wholesaler Dashboard
    */
    public function dashboardAction()
    {

        $this->_title($this->__('Dashboard'));
        $this->loadLayout();
        $this->_setActiveMenu('wholesaler/dashboard');
        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Dashboard'), Mage::helper('adminhtml')->__('Dashboard'));
        //$this->_addContent($this->getLayout()->createBlock('wholesaler/adminhtml_dashboard'));
        $this->renderLayout();
    }

    public function ordersAction(){

        $this->loadLayout();
        $this->_setActiveMenu('wholesaler/orders');
        $this->_addContent($this->getLayout()->createBlock('wholesaler/adminhtml_orders'));
        $this->renderLayout();

        /*

        $select = $collection->getSelect()->join(
                array('product_store' => $this->getTable('catalog/product')),
                "main_table.product_id = $alias.entity_id AND $alias.attribute_id={$attribute->getId()}",
                array($attributeCode => 'value')
        )
        ->where("$alias.value=?", 75);

        // $collection = Mage::getResourceModel('sales/order_item_collection')->setOrderFilter('100000013');
        print_r($collection);

        */
    }


}