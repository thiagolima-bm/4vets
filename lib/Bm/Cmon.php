<?php

class Bm_Cmon{

	const FINISHED_STATE = 'complete';
	
	const CANCELED_STATE = 'canceled';
	
	const CANCELED_STATUS = 'canceled';

	const CLOSED_STATUS = 'closed';

	const INVOICED_STATE = 'paid';
	
	private static $catalogCode = '4vets';

	private static $financingTimes = 10;
	
	private static $rate = 1.2178;

	/**
	 * Returns catalogCode
	 *
	 * @return String
	 */
	public static function getCatalogCode(){

		return self::$catalogCode;

	}

	/**
	 * Generates storecode
	 *
	 * @return String
	 */
	public static function genStorecode($size=null){

		$string = md5(uniqid(rand(), true));

		if($size != null)
			return substr($string, 0, (int)$size);
		else
			return substr($string, 0, 8);
	}

	/**
	 * Generates sku
	 *
	 * @param String $storecode
	 * @return String
	 */
	public static function genSku($user_id=null,$size=null){

		//ramdom string
		$string = md5(uniqid(rand(), true));

		$storecode = Bm_Cmon::getStorecode($user_id);

		$string = substr($string, 0, 8);	
		
		return $storecode.'|'.$string;

	}

	/**
	 * Decripty Sku
	 *
	 * @param String $storecode
	 * @return String
	 */
	public static function decSku($user_id=null){

		$storecode = Bm_Cmon::getStorecode($user_id);

		return " LIKE '".$storecode."|%'";
	}

	/**
	 * Returns storecode
	 *
	 * @return String
	 */
	public static function getStorecode($user_id=null){
		//TODO: get storecode from session;


		$user = Mage::getSingleton('admin/session')->getUser();

		if($user){
			$wholesaler = Mage::getModel('wholesaler/wholesaler')->load($user->getUserId(),'user_id');
			
			return $wholesaler->getStorecode();
			
		}else{
			
			$user = Mage::getSingleton('customer/session');

			if (Mage::getSingleton('customer/session')->isLoggedIn()) {
				
				$storecode = $user->getCustomer()->getStorecode();
				return strtolower($storecode);

			}
			
	
		}

		return "";
			

	}


	/**
	 * Decripty Sku
	 *
	 * @param String $storecode
	 * @return String
	 */
	public static function checkSkuOwner($sku,$storecode=""){

		if(empty($storecode))
			$storecode = Bm_Cmon::getStorecode();

		$sku = explode("|",$sku);

		//return " REGEXP '.{2}".$storecode[0].".{2}".$storecode[1].".".$storecode[2].".{5}".$storecode[3]."' ";

		return ($storecode == $sku[0]);
	}


	/**
	 * Decripty Sku
	 *
	 * @param String $storecode
	 * @return String
	 */
	public static function splitStorecodeShip($string){
		$storecode_ship = array();
		$storecode_ship[0] = substr($string, 0, 3);
		$storecode_ship[1] = substr($string, 5, srtlen($string)-1);

		return $storecode_ship;
	}


	public static function getWholesaler(){

		$seller = Mage::getSingleton('admin/session')->getUser();
		return $wholesaler = Mage::getModel('wholesaler/wholesaler')->load($seller->getId(),'user_id');

	}

	public static function getWholesalerShipping($orderId, $wholesaler_id = ''){

		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');

		if (empty($wholesaler_id))
			$wholesaler_id = Bm_Cmon::getWholesaler()->getWholesalerId();

		$sql = "SELECT * FROM wholesaler_ship WHERE wholesaler_id = '".$wholesaler_id."' and order_id ='".$orderId."'";
		// now $write is an instance of Zend_Db_Adapter_Abstract
		return $rows = $connection->raw_fetchRow($sql);

	}

	public static function getAllWholesalerShipping($orderId){

		$connection = Mage::getSingleton('core/resource')->getConnection('core_read');

		$sql = "SELECT * FROM wholesaler_ship ws";
		$sql .= " LEFT JOIN wholesaler w ON w.wholesaler_id = ws.wholesaler_id";
		$sql .= " WHERE order_id ='".$orderId."'";
		return $rows = $connection->raw_query($sql);

	}

	public static function formatedPrice($price){

		return Mage::helper('core')->currency($price, true, false);
	}

	public static function formatedFinancingPrice($price){

		if(self::$rate > 0)
			return Mage::helper('core')->currency( ($price/self::$financingTimes) * self::$rate, true, false);
		else
			return Mage::helper('core')->currency($price/self::$financingTimes, true, false);
	}

	public static function getFinancingTimes(){

		return self::$financingTimes;
	}


	public static function getStorecodeFromSku($sku){

		return $sku[2].$sku[5].$sku[7].$sku[13];
	}
	 
	public static function skuStoreCode() {
		return '(concat(substring(sku,3,1),substring(sku,6,1),substring(sku,8,1),substring(sku,14,1))) as store_code';
	}
	 
	/**
	 * Update Sku owner
	 *
	 * @param String $sku
	 * @param String $storecode
	 * @return String
	 */
	public static function updateSku($sku,$storecode)
	{

		if( (strlen($sku) < 14) || (strlen($storecode) < 4 ))
			return "";


		$sku[2]  =   $storecode[0];
		$sku[5]  =   $storecode[1];
		$sku[7]  =   $storecode[2];
		$sku[13] =   $storecode[3];
		 
		return $sku;
	}


	public static function updateWholesalerShipping($orderId, $newWholesalerId, $oldWholesalerId){

		
		
		if (!empty($newWholesalerId) &&!empty($orderId) && !empty($oldWholesalerId)){

			$write = Mage::getSingleton('core/resource')->getConnection('core_write');

			$sql = "update wholesaler_ship set wholesaler_id = $newWholesalerId WHERE order_id ='".$orderId."' AND wholesaler_id = $oldWholesalerId";

			Mage::log($sql);
			// now $write is an instance of Zend_Db_Adapter_Abstract
			$write->query($sql);

		}

	}


	
	/**
	 * Returns Zipcodezone
	 *
	 * @return String
	 */
	public static function getZipcodezone($user_id=null){
		//TODO: get storecode from session;


		$user = Mage::getSingleton('admin/session')->getUser();

		if($user){
			$wholesaler = Mage::getModel('wholesaler/wholesaler')->load($user->getUserId(),'user_id');
			
			return $wholesaler->getZipcodezone();
			
		}else{
			
			$user = Mage::getSingleton('customer/session');

			if (Mage::getSingleton('customer/session')->isLoggedIn()) {
				
				$storecode = $user->getCustomer()->getZipcodezone();
				return strtolower($storecode);

			}
			
	
		}

		return "";
			

	}





}