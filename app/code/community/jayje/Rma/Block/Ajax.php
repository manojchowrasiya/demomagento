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

class Jayje_Rma_Block_Ajax extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function __construct()
    {     
       // parent::__construct();
                  $this->setTemplate('rma/ritem.phtml');
 
    }
    public function getRma()     
     { 
        if (!$this->hasData('rma')) {
            $this->setData('rma', Mage::registry('rma'));
        }
        return $this->getData('rma');
        
    }
    
   public function checkRmaProduct($oid, $pid){
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $que = "SELECT product_id FROM `rma_products` WHERE `order_id` ='$oid' and `product_id` = '$pid' LIMIT 0 , 1";
        $result = $db->query($que);
        $resultn = $result->fetch(PDO::FETCH_ASSOC);
        extract($resultn);
        return $product_id;

  }  
    
}

?>


