<?php

class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Orders_View extends Mage_Adminhtml_Block_Sales_Order_Abstract
{


	/**
     * Retrieve required options from parent
     */
    protected function _beforeToHtml()
    {
        $this->setTemplate('wholesaler/view_order.phtml');


        parent::_beforeToHtml();
    }

/**
     * Whether Customer IP address should be displayed on sales documents
     * @return bool
     */
    public function shouldDisplayCustomerIp()
    {
        return !Mage::getStoreConfigFlag('sales/general/hide_customer_ip', $this->getOrder()->getStoreId());
    }


    public function getItemsCollection(){

        $items = array();
         foreach ($this->getOrder()->getItemsCollection() as $item) {
             if (!$item->isDeleted() and strstr($item->getSku(),'Acaldeira')) {
                 $items[] =  $item;
             }
         }
         return $items;
    
    }

}


