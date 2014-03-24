<?php
include_once "Mage/Adminhtml/controllers/Catalog/ProductController.php";
class Acaldeira_Wholesaler_Adminhtml_Wcatalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{


	 /**
	* List wholesalers
	*
	*/
    public function indexAction() 
    {

       // $this->_initAction();       
    	$this->loadLayout();
        //$this->_addContent($this->getLayout()->createBlock('wholesaler/adminhtml_catalog_product'));
        $this->renderLayout();
    }

    /**
    * Create wholesaler
    *
    */
    public function newAction()
    {
        $this->_redirect('*/*/editProduct');
    }


    /**
    * Edit wholesaler
    *
    */
    public function editProductAction()
    {

        $this->_redirect('wholesaler/adminhtml_wholesaler/associateProducts');

        $product = $this->_initProduct();

        $this->loadLayout();
        
        $this->_setActiveMenu('wholesaler/product');    
        
        $this->renderLayout(); 

    }


    /**
    * Saves wholesaler
    *
    */
    public function saveProductAction()
    {


         if ( $this->getRequest()->getPost() ) {

            try {

                $user = Mage::getSingleton('admin/session')->getUser();

                $product = $this->_initProductSave();

                $productId  = (int) $this->getRequest()->getParam('id');

                if(!$productId){

                    $product->setSku(Bm_Cmon::genSku());
                
                    $product->setStorecode(Bm_Cmon::getStorecode());



                }
                    
                $product->setZone(Bm_Cmon::getZipcodezone());
            

                /* Admin must activate */
                $product->setStatus(Mage_Catalog_Model_Product_Status::STATUS_DISABLED);
                  
                $product->save();
               
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('wholesaler')->__('Product was successfully saved'));
                
                Mage::getSingleton('adminhtml/session')->setCompanyData(false);
 
                $this->_redirect('*/*/');

                return;
                
                }catch (Exception $e) {
                
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    Mage::getSingleton('adminhtml/session')->setWholesalerProduct($this->getRequest()->getPost());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }
        }
        $this->_redirect('*/*/');
    }
	

    /* from Mage_Adminhtml_Catalog_ProductController */

    /**
     * Get categories fieldset block
     *
     */
    public function categoriesAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Initialize product from request parameters
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _initProduct()
    {
        $this->_title($this->__('Catalog'))
             ->_title($this->__('Manage Products'));

        $productId  = (int) $this->getRequest()->getParam('id');
        $product    = Mage::getModel('catalog/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        // Hack

        $this->getRequest()->setParam('set',4);
        $this->getRequest()->setParam('type','simple');
        if (!$productId) {
            if ($setId = (int) $this->getRequest()->getParam('set')) {
                $product->setAttributeSetId($setId);
            }

            if ($typeId = $this->getRequest()->getParam('type')) {
                $product->setTypeId($typeId);
            }
        }

        $product->setData('_edit_mode', true);
        if ($productId) {
            try {
                $product->load($productId);
            } catch (Exception $e) {
                $product->setTypeId(Mage_Catalog_Model_Product_Type::DEFAULT_TYPE);
                Mage::logException($e);
            }
        }

        $attributes = $this->getRequest()->getParam('attributes');
        if ($attributes && $product->isConfigurable() &&
            (!$productId || !$product->getTypeInstance()->getUsedProductAttributeIds())) {
            $product->getTypeInstance()->setUsedProductAttributeIds(
                explode(",", base64_decode(urldecode($attributes)))
            );
        }

        // Required attributes of simple product for configurable creation
        if ($this->getRequest()->getParam('popup')
            && $requiredAttributes = $this->getRequest()->getParam('required')) {
            $requiredAttributes = explode(",", $requiredAttributes);
            foreach ($product->getAttributes() as $attribute) {
                if (in_array($attribute->getId(), $requiredAttributes)) {
                    $attribute->setIsRequired(1);
                }
            }
        }

        if ($this->getRequest()->getParam('popup')
            && $this->getRequest()->getParam('product')
            && !is_array($this->getRequest()->getParam('product'))
            && $this->getRequest()->getParam('id', false) === false) {

            $configProduct = Mage::getModel('catalog/product')
                ->setStoreId(0)
                ->load($this->getRequest()->getParam('product'))
                ->setTypeId($this->getRequest()->getParam('type'));

            /* @var $configProduct Mage_Catalog_Model_Product */
            $data = array();
            foreach ($configProduct->getTypeInstance()->getEditableAttributes() as $attribute) {

                /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
                if(!$attribute->getIsUnique()
                    && $attribute->getFrontend()->getInputType()!='gallery'
                    && $attribute->getAttributeCode() != 'required_options'
                    && $attribute->getAttributeCode() != 'has_options'
                    && $attribute->getAttributeCode() != $configProduct->getIdFieldName()) {
                    $data[$attribute->getAttributeCode()] = $configProduct->getData($attribute->getAttributeCode());
                }
            }

            $product->addData($data)
                ->setWebsiteIds($configProduct->getWebsiteIds());
        }

        Mage::register('product', $product);
        Mage::register('current_product', $product);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $product;
    }

    public function categoriesJsonAction()
    {
        $product = $this->_initProduct();

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }

    /**
     * WYSIWYG editor action for ajax request
     *
     */
    public function wysiwygAction()
    {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock('adminhtml/catalog_helper_form_wysiwyg_content', '', array(
            'editor_element_id' => $elementId,
            'store_id'          => $storeId,
            'store_media_url'   => $storeMediaUrl,
        ));
        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return true;
    }

}