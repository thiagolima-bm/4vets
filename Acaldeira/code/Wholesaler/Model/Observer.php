<?php

class Acaldeira_Wholesaler_Model_Observer
{

	public function updateCustomerZipcodezone(Varien_Event_Observer $observer)
    {
    	$customer = $observer->getCustomer();

    	$zipCode = $customer->getZipcode();
    	Mage::log($customer);
    	// $zipcodezone = Mage::getModel('zipcodezone/zipcodezone')->getCollection()
     //            ->addFieldToFilter('start', array('lteq' => $customer->getZipcode()))
     //            ->addFieldToFilter('end', array('gteq' => $customer->getZipcode()))
     //            ->getFirstItem();

      
       	/*
       	$addressData =  array ('postcode'=>$zipCode);
		$customer->addAddress($address);
       	$customer->save();
		*/
      

       Mage::log('updateCustomerZipcodezone');
       
    }
}
