<?php

class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Sales_Order_View_Info extends Mage_Adminhtml_Block_Sales_Order_View_Info
{

	 public function getViewUrl($orderId)
    {
        return $this->getUrl('*/adminhtml_sales_order/view', array('order_id'=>$orderId));
    }


    
}


?>