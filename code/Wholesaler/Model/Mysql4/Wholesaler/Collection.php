<?php
 
class Acaldeira_Wholesaler_Model_Mysql4_Wholesaler_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('wholesaler/wholesaler');
    }
    
}