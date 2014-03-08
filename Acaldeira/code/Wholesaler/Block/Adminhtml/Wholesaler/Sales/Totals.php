<?php

class Acaldeira_Wholesaler_Block_Adminhtml_Wholesaler_Sales_Totals  extends Mage_Adminhtml_Block_Sales_Totals
{

	 /**
     * Format total value based on order currency
     *
     * @param   Varien_Object $total
     * @return  string
     */
    public function formatValue($total)
    {
        if (!$total->getIsFormated()) {
            return $this->helper('adminhtml/sales')->displayPrices(
                $this->getOrder(),
                $total->getBaseValue(),
                $total->getValue()
            );
        }
        return $total->getValue();
    }

    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        $this->_totals = array();

        //overridden store ship
        $storeship = Bm_Cmon::getWholesalerShipping($this->getOrder()->getRealOrderId());


        $_subtotal = 0;
          foreach ($this->getOrder()->getAllItems() as $orderItem) {

         	if(Bm_Cmon::checkSkuOwner($orderItem->getSku())){
         		$_subtotal+= $orderItem->getQtyOrdered()*$orderItem->getOriginalPrice();

         	}
             
             

         }

        $this->_totals['subtotal'] = new Varien_Object(array(
            'code'      => 'subtotal',
            'value'     => $_subtotal,
            'base_value'=> $this->getSource()->getBaseSubtotal(),
            'label'     => $this->helper('sales')->__('Subtotal')
        ));

        /**
         * overridden ship value
         */
        if (!$this->getSource()->getIsVirtual() && ((float) $this->getSource()->getShippingAmount() || $this->getSource()->getShippingDescription()))
        {
            
            $this->_totals['shipping'] = new Varien_Object(array(
                'code'      => 'shipping',
                'value'     => $storeship['value'], //$this->getSource()->getShippingAmount(),
                'base_value'=> $this->getSource()->getBaseShippingAmount(),
                'label' => $this->helper('sales')->__('Shipping & Handling')
            ));
        }

        /**
         * Add discount
         */

        /*
        if (((float)$this->getSource()->getDiscountAmount()) != 0) {
            if ($this->getSource()->getDiscountDescription()) {
                $discountLabel = $this->helper('sales')->__('Discount (%s)', $this->getSource()->getDiscountDescription());
            } else {
                $discountLabel = $this->helper('sales')->__('Discount');
            }
            $this->_totals['discount'] = new Varien_Object(array(
                'code'      => 'discount',
                'value'     => $this->getSource()->getDiscountAmount(),
                'base_value'=> $this->getSource()->getBaseDiscountAmount(),
                'label'     => $discountLabel
            ));
        }
		*/

		//FIXME: implements discount logic
        $this->_totals['grand_total'] = new Varien_Object(array(
            'code'      => 'grand_total',
            'strong'    => true,
            'value'     => $_subtotal+$this->getSource()->getDiscountAmount()*(-1)+$storeship['value'],
            'base_value'=> $this->getSource()->getBaseGrandTotal(),
            'label'     => $this->helper('sales')->__('Grand Total'),
            'area'      => 'footer'
        ));

        return $this;
    }

}
?>