<?php
 
class Acaldeira_Zipcodezone_Model_Mysql4_zipcodezone extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('zipcodezone/zipcodezone', 'zipcodezone_id');
    }
}