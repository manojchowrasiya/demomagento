<?php
class Shopelect_Seller_Model_Mysql4_Groupdiscount extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("seller/groupdiscount", "id");
    }
}