<?php
/**
 * Jayje_Rma extension
 *  
 * @category   	Return Merchant Authorization Magento - wakensys
 * @package	Jayje_Rma
 * @copyright  	Copyright (c) 2013
 * @license	http://opensource.org/licenses/mit-license.php MIT License
 * @category	Jayje
 * @package	Jayje_Rma
 * @author        wakensys
 * @developper   s.ratheepan@gmail.com
 */


class Jayje_Rma_Model_Rma extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('rma/rma');
    }
    
    
    public function getRmaProducts(){
        $listid = Mage::registry('list');
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $que = "SELECT * FROM `rma_products` WHERE `rmaid` ='$listid'";
        $result = $db->query($que); 
        return $result;
    }
    
      public function getRmaComments($rid=''){
        if($rid !=''){
             $listid = $rid;
        }else{
            $listid = Mage::registry('list');
        }     
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $que = "SELECT * FROM  `rma_comments`  where rmaid='$listid'";
        $result = $db->query($que);
         return $result;
    }
    
    public function getRmaList(){
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $customerid =Mage::helper('customer')->getCustomer()->getData('entity_id') ;
        $que = "SELECT * FROM  `rma` WHERE  `customer_id` =  '$customerid'
                ORDER BY rma_id DESC LIMIT 0 , 30";
        $result = $db->query($que);

        return $result;
    }

    public function getOrder(){   
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $customerid =Mage::helper('customer')->getCustomer()->getData('entity_id') ;
        $que = "SELECT entity_id, increment_id, grand_total, store_currency_code FROM `sales_flat_order` 
                  WHERE customer_id='$customerid' 
                  ORDER BY increment_id DESC LIMIT 0,30";
        return $result = $db->query($que);
     }
     
       public function getRmaInfo(){
        $listid = Mage::registry('list');
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $customerid =Mage::helper('customer')->getCustomer()->getData('entity_id') ;
        $que = "SELECT * FROM  `rma` WHERE  `customer_id` =  '$customerid' AND rma_id= '$listid'
                ORDER BY rma_id DESC LIMIT 0 , 1";
        $result = $db->query($que);
        $resultn = $result->fetch(PDO::FETCH_ASSOC);
        return $resultn;
    }
    

}