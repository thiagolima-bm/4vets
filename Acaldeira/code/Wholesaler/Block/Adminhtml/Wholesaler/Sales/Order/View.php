<?php

class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Sales_Order_View  extends Mage_Adminhtml_Block_Sales_Order_View
{


	 public function __construct()
    {
    	parent::__construct();

    		//overridden check acl
    	  $this->_addButton('order_ship', array(
                'label'     => Mage::helper('sales')->__('Ship'),
                'onclick'   => 'setLocation(\'' . $this->getShipUrl() . '\')',
                'class'     => 'go'
            ));
    }
	/**
     * Acl check for admin
     *	FIXME: change permissions;
     * @return bool
     */
    protected function _isAllowedAction()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
           
            case 'ship':
                $aclResource = true;
                break;

        }
        return $aclResource;
    }


    public function getShipUrl()
    {
        return $this->getUrl('*/adminhtml_sales_order_shipment/new');
    }
}

?>