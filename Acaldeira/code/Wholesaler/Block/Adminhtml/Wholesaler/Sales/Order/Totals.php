<?php

class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Sales_Order_Totals extends Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Sales_Totals//Mage_Adminhtml_Block_Sales_Order_Abstract
{

	/**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        parent::_initTotals();

        //overridden store ship
        $storeship = Bm_Cmon::getWholesalerShipping($this->getOrder()->getRealOrderId());

        $_subtotal = 0;
          foreach ($this->getOrder()->getAllItems() as $orderItem) {

            if(Bm_Cmon::checkSkuOwner($orderItem->getSku())){
                $_subtotal+= $orderItem->getQtyOrdered()*$orderItem->getOriginalPrice();

            }
             
             

         }

       
        $this->_totals['paid'] = new Varien_Object(array(
            'code'      => 'paid',
            'strong'    => true,
            'value'     => $_subtotal+$this->getSource()->getDiscountAmount()*(-1)+$storeship['value'], //overridden; hiding descount from wholesaler
            'base_value'=> $this->getSource()->getBaseTotalPaid(),
            'label'     => $this->helper('sales')->__('Total Paid'),
            'area'      => 'footer'
        ));
        $this->_totals['refunded'] = new Varien_Object(array(
            'code'      => 'refunded',
            'strong'    => true,
            'value'     => $this->getSource()->getTotalRefunded(),
            'base_value'=> $this->getSource()->getBaseTotalRefunded(),
            'label'     => $this->helper('sales')->__('Total Refunded'),
            'area'      => 'footer'
        ));

        //$this->_totals['grand_total']
        /*
        $this->_totals['due'] = new Varien_Object(array(
            'code'      => 'due',
            'strong'    => true,
            'value'     => $this->getSource()->getTotalDue(),
            'base_value'=> $this->getSource()->getBaseTotalDue(),
            'label'     => $this->helper('sales')->__('Total Due'),
            'area'      => 'footer'
        ));

        */
        
        return $this;
    }

}

