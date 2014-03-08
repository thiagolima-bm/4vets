<?php
		
class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Sales_Order_View_Tab_Info extends Mage_Adminhtml_Block_Sales_Order_View_Tab_Info{

	public function getWholesalerShipping($orderId){
	    	
	    	return Bm_Cmon::getWholesalerShipping($orderId);
			
	}	
}
