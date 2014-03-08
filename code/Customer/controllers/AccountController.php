<?php

include_once "Mage/Customer/controllers/AccountController.php";

class Acaldeira_Customer_AccountController extends Mage_Customer_AccountController
{


    /**
     * Create an address with zipcode only and set zip code zone to customer
     *
     * @return Mage_Customer_Model_Customer
     */
    protected function _getCustomer()
    {
        $customer = parent::_getCustomer();

        $data = $this->getRequest()->getPost();
 
       	$addressData =  array ('postcode'=>$data['zipcode']);
		
		$address   = Mage::getModel('customer/address');
 		
 		$address->addData($addressData);
		
		$customer->addAddress($address);

        $zipcodezone = Mage::getModel('zipcodezone/zipcodezone')->getCollection()
                ->addFieldToFilter('start', array('lteq' => $data['zipcode']))
                ->addFieldToFilter('end', array('gteq' => $data['zipcode']))
                ->getFirstItem();

        $customer->setZipcodezone($zipcodezone->getId());

        return $customer;
    }

}