<?php

class Acaldeira_Wholesaler_Model_Company extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('wholesaler/company');
    }
}