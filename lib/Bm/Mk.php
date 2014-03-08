<?php

class Bm_Mk{


	public static function getWholesalerOrderStatus($order) {
		$order_status = $order->getStatusLabel();

		if ($order->getStatus() == 'shipping') {
			$username = Mage::getSingleton('admin/session')->getUser()->getUsername();
			$found = false;

			foreach($order->getShipmentsCollection() as $shipment) {
				$sku = $shipment->getItemsCollection()->getFirstItem()->getSku();
				if (Bm_Cmon::getStorecodeFromSku($sku) == $username) {
					$found = true;
					break;
				}
			}

			if ($found == false) {
				$order_status = Mage_Sales_Model_Order_Config::getStatusLabel('paid');
			}
		}

		return $order_status;
	}

	/**
     * Returns store qty and min price
     *
     * @return String
     */
	 public static function getStoresProductCollection($catalog_product_id,$ean){


	 	if(!$catalog_product_id || !$ean)
	 		return array();

		foreach (Mage::app()->getWebsites() as $website) {
		    foreach ($website->getGroups() as $group) {
		        $stores = $group->getStores();
		        foreach ($stores as $store) {
		            $storeName[$store->getCode()] = $store->getName();
		        }
		    }
		}

		
		//$ean = $_helper->productAttribute($_product, $_product->getEan(),'ean');
		//$catalod_product_id = $_helper->productAttribute($_product, $_product->getId(),'id');
		
		$collection = Mage::getModel('catalog/product')->getCollection();
		//filter for products whose orig_price is greater than (gt) 100
		$collection->addAttributeToSelect(array('price','storecode'));

		$collection->addFieldToFilter('entity_id',array('neq'=>$catalog_product_id));
		$collection->addFieldToFilter('ean',array('='=>$ean));
        $collection->joinTable('cataloginventory/stock_item', 'product_id=entity_id', array('qty' => 'qty', 'is_in_stock' => 'is_in_stock'), '{{table}}.stock_id=1', 'left')
            ->addAttributeToFilter('is_in_stock', array('eq' => 1))
			->addAttributeToFilter('qty', array('gt' => 0));

		$collection->addAttributeToSort('price','ASC');
 		return $collection;

	 }


	public static function setWholesalerProduct($product){

		Mage::unregister('wholesalerProduct');
		Mage::register('wholesalerProduct',$product);	

	 }

	 public static function getWholesalerProduct(){
	 	$product = false;
	 	if(Mage::registry('wholesalerProduct')){
	 		$product = Mage::registry('wholesalerProduct');
	 		Mage::unregister('wholesalerProduct');
	 		return $product;
	 	}

	 	else
	 		return false;

	 }
	 
	 /**
	  * Check if order belongs to wholesaler user
	  *
	  * @param $orderId 
	  * @return boolean
	  */
	 public static function isWholesalerOrder($orderId){
	 	
	 	return false;
	 }
	 
	 /**
	  * Return store name
	  *
	  * @param $storecode
	  * @return string
	  */
	 public static function getStoreName($storecode){
	 	
	 	$user = Mage::getModel('admin/user')->loadByUsername($storecode);
	 	$user_id = $user->getUserId();
	 	
	 	$storeModel  = Mage::getModel('store/store')->load($user_id,'user_id');
	 	 
	 	return $storeModel->getStoreName();
	 }

	 public static function getCompanyNameById($company_id){
	 	$companyModel = Mage::getModel('company/company')->load($company_id,'user_id');
	 	 
	 	return $companyModel->getCompanyName();
	 }
	 
	 public static function getCompanyName($storecode){
	 	
	 	$user = Mage::getModel('admin/user')->loadByUsername($storecode);
	 	$user_id = $user->getUserId();
	 	 
	 	$storeModel  = Mage::getModel('company/company')->load($user_id,'user_id');
	 	
	 	return $storeModel->getCompanyName();
	 	
	 }
	 
	 
	 /**
	  * Displays store rating link
	  * @param string $orderId
	  */
	 public static function isActiveStoreRating($order){
	 	
	 	
	 	return true;
	 	
	 }

	 
 	public static function getStoreFromSku($sku){
	 		
	 	$storecode = $sku[2].$sku[5].$sku[7].$sku[13];
	 
	 	return Bm_Mk::getCompanyByStoreCode($storecode);
	 }
	 
	 
	 
	 public static function getRatedCompanies($orderId){
	 	
	 	return Mage::getModel('questionnaire/answered')->getCollection()->addFieldToFilter('order_id',$orderId);
	 }
	 


	 public static function getCompanyByStoreCode($storecode){
	 	 
	 	$user = Mage::getModel('admin/user')->loadByUsername($storecode);
	 	$user_id = $user->getUserId();
	 
	 	return Mage::getModel('company/company')->load($user_id,'user_id');
	 }
	 
	 
	 public static function getStockByEan($ean){
	 	
	 	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
	 	
	 	$query = "SELECT countStock($ean)";
	 	
	 	return $rows = (int) $connection->fetchOne($query);
	 	
	 }

}