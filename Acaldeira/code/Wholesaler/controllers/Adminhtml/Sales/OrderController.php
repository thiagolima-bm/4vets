<?php

require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';
class Acaldeira_Wholesaler_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{


	/**
     * Init layout, menu and breadcrumb
     *
     * @return Mage_Adminhtml_Sales_OrderController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('wholesaler/order')
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Orders'), $this->__('Orders'));
        return $this;
    }

    /**
     * Initialize order model instance
     *
     * @return Mage_Sales_Model_Order || false
     */
    protected function _initOrder()
    {
        $id = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($id);

        if (!$order->getId()) {
            $this->_getSession()->addError($this->__('This order no longer exists.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;
        }
        
        $items = $order->getItemsCollection();
    
        $_canSee = true;
        foreach($items as $item){
            if(Bm_Cmon::checkSkuOwner($item->getSku())){
                $_canSee =true;
                break;
            }
             //   var_dump($item);


        }

        // exit;
        
        if($_canSee){
            
            Mage::register('sales_order', $order);
            Mage::register('current_order', $order);
            return $order;

        }else{

            $this->_getSession()->addError($this->__('This order no belongs to you.'));
            $this->_redirect('*/*/');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
            return false;

        }


       
    }

     /**
     * View order detale
     */
    public function viewAction()
    {
        $this->_title($this->__('Sales'))->_title($this->__('Orders'));

        if ($order = $this->_initOrder()) {
            $this->_initAction();

            $this->_title(sprintf("#%s", $order->getRealOrderId()));

            $this->renderLayout();
        }
    }



     /**
     * Acl check for admin
     *	FIXME: change permissions;
     * @return bool
     */
    protected function _isAllowed()
    {
        $action = strtolower($this->getRequest()->getActionName());
        switch ($action) {
            case 'hold':
                $aclResource = 'sales/order/actions/hold';
                break;
            case 'unhold':
                $aclResource = 'sales/order/actions/unhold';
                break;
            case 'email':
                $aclResource = 'sales/order/actions/email';
                break;
            case 'cancel':
                $aclResource = 'sales/order/actions/cancel';
                break;
            case 'view':
                $aclResource = true;
                break;
            case 'addcomment':
                $aclResource = true;
                break;
            case 'creditmemos':
                $aclResource = true;
                break;
            case 'reviewpayment':
                $aclResource = 'sales/order/actions/review_payment';
                break;
            default:
                $aclResource = 'sales/order';
                break;

        }
        return $aclResource;
    }

}

?>