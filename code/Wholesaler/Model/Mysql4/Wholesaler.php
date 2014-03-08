<?php

class Acaldeira_Wholesaler_Model_Mysql4_Wholesaler extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('wholesaler/wholesaler', 'wholesaler_id');
    }
}