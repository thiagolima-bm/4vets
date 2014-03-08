<?php

class Acaldeira_Wholesaler_Model_Mysql4_Company extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('wholesaler/company', 'company_id');
    }
}