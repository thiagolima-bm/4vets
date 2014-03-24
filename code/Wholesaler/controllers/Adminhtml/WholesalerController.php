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

        $wholesalerConf->setZipcodezone(explode("|", $wholesalerConf->getZipcodezone()));

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

                
                // $zipcodezone = Mage::getModel('zipcodezone/zipcodezone')->getCollection()
                // ->addFieldToFilter('start', array('lteq' => $postData['cep']))
                // ->addFieldToFilter('end', array('gteq' => $postData['cep']))
                // ->getFirstItem();
                
               
                $postData['servicos'] = implode("|", $postData['servicos']);
                $postData['zipcodezone'] = implode("|", $postData['zipcodezone']);


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
                    ->setZipcodezone($postData['zipcodezone'])
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

    public function associateProductsAction()
    {

        $this->_initAction();

        $this->_checkZone();



        if($this->getRequest()->getParam('id') != ""){



            $productId = $this->getRequest()->getParam('id');

            $collection = Mage::getModel('catalog/product')->getCollection();
            //$collection->addAttributeToSelect('*');
            $collection->addAttributeToSelect('*');
            //FIXME: change attr to isCatalog : boolean;
             

            $collection->addAttributeToFilter('entity_id', array('in' => array($productId)));

            if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
                $collection->joinField('qty',
                        'cataloginventory/stock_item',
                        'qty',
                        'product_id=entity_id',
                        '{{table}}.stock_id=1',
                        'left');

                $collection->joinField('is_in_stock',
                        'cataloginventory/stock_item',
                        'is_in_stock',
                        'product_id=entity_id',
                        '{{table}}.stock_id=1',
                        'left');
            }


            //$collection->getSelect()->where('sku '.Bm_Cmon::decSku());

            $wholesalerModel = $collection->getFirstItem();

            //FIXME: wholesaler_data should be product_data;
            Mage::register('wholesaler_data', $wholesalerModel);
            Mage::register('product', $wholesalerModel);

        } else {
            $product    = Mage::getModel('catalog/product');
            $postData = $this->getRequest()->getPost();

            $product->setPrice(0);
            $product->setQty(0);
            $product->setWeight(0);
            $product->setIsInStock(1);

            Mage::register('wholesaler_data', $product);
            Mage::register('product', $product);
        }

        //load adminhtml/wholesaler/Newproduct.php
        $this->_addContent($this->getLayout()->createBlock('wholesaler/adminhtml_wholesaler_newproduct'))
        ->_addLeft($this->getLayout()->createBlock('wholesaler/adminhtml_wholesaler_newproduct_tabs'));
        $this->renderLayout();
    }

    public function ajaxFindProductAction(){


        // if (!$this->getRequest()->isAjax()) {
        //        $this->_ajaxRedirectResponse();
        //        return;
        //    }

        $result = new Varien_Object();

        $sku = $this->getRequest()->getParam('psku');
        //filtering
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('storecode', array('=' => Bm_Cmon::getCatalogCode()));
        $collection->addFieldToFilter('sku', array('=' => $sku));

        if ($collection->getSize() > 0) {

            $_product = $collection->getFirstItem();

            $result->setSuccess(true);
            $result->setProductName($_product->getName());
            $result->setProductWeight($_product->getWeight());

            $block = Mage::getSingleton('core/layout')->createBlock('catalog/product');
            $block->setProduct($_product)->setTemplate('wholesaler/product-preview.phtml');
            $result->setProductPreview($block->toHtml());

            $prod = Mage::getModel('catalog/product')->load($_product->getId());
            Mage::getSingleton('core/session')->setCatalogProduct($prod);
             
        } else {
            $result->setSuccess(false);
        }



        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

    }


    public function saveProductAction(){
        //FIXME: check if bommercatto_catalog is the same; on edit generates an error

        if ( $this->getRequest()->getPost() ) {


            try{

                $storecode = Bm_Cmon::genStorecode();

                $postData = $this->getRequest()->getPost();

               

                $bommercatto_catalog = Mage::getModel('catalog/product')->loadByAttribute('sku',$postData['psku']);
              
                
                $productId  = (int) $this->getRequest()->getParam('id');

                if($productId){

                    $product = Mage::getModel('catalog/product')->load($productId);

                }else{

                    Mage::log('loading new product');
                    //Mage::log('colection size:'.sizeof($collection));

                    if(!$bommercatto_catalog)
                        throw new Exception(Mage::helper('wholesaler')->__('This product was not found at catalog'));

                    $product = Mage::getModel('catalog/product');
                    //get storecode
                    $product->setSku(Bm_Cmon::genSku());
                    // * used to market place;
                    $product->setPsku($postData['psku']);
                    
                    $product->setStorecode(Bm_Cmon::getStorecode());

                    //get from catalog
                    $product->setAttributeSetId($bommercatto_catalog->getAttributeSetId());
                    //get from catalog
                    $product->setTypeId('simple');
                    //get from form
                    $product->setName($bommercatto_catalog->getName());

                    //get from catalog
                    //$product->setCategoryIds(array(7)); # some cat id's, my is 7
                    $product->setCategoryIds($bommercatto_catalog->getCategoryIds());

                    //$product->setWebsiteIDs(array(1)); # Website id, my is 1 (default frontend)
                    $product->setWebsiteIds($bommercatto_catalog->getWebsiteIds());
                    //doesnt metter
                    $product->setDescription('Full description here');
                    $product->setShortDescription('Short description here');

                    //FIXME: use for correios?
                    $product->setHeight('1');
                    $product->setWidth('1');
                    $product->setDepth('1');
                    $product->setType('1');
                    $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE);
                    //get from catalog
                    $product->setStatus(1);
                    $product->setTaxClassId(0); # My default tax class
                    $product->setCreatedAt(strtotime('now'));
                    
                     

                }

                //Mage::log($bommercatto_catalog);

                //get from form
                $product->setPrice($postData['price']); # Set some price

                //Default Magento attribute
                //get from form
                $product->setWeight($postData['weight']);
                 
                //get from form
                $product->setStockData(array(
                        'is_in_stock' => $postData['is_in_stock'],
                        'qty' => $postData['qty']
                ));

                if(isset($postData['product']['group_price']))
                    $product->setData('group_price',$postData['product']['group_price']);


                // $transaction = Mage::getModel('core/resource_transaction');
        
                // $transaction->addObject($product);

                // $transaction->save();

                
                $product->save();


                //Mage::getSingleton('core/session')->unsBommercattoCatalog($product);
                $this->_redirect('*/*/');

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setWholesalerData(false);

                $this->_redirect('*/adminhtml_wcatalog_product/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWholesalerData($this->getRequest()->getPost());
                //$this->_redirect('*/*/editShipping', array('id' => $this->getRequest()->getParam('id')));

                $this->_redirect('*/*/associateProducts');
                return;
            }

        }

    }


    public function _checkZone()
    {
        // if(!Mage::registry('zone'))
        //     $this->_redirect('*/*/registerZone');
 

    }

    public function registerZoneAction()
    {

        

    }




}