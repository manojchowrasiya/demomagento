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

class Jayje_Rma_Model_Rcomments extends Mage_Core_Model_Abstract{

	public function _construct(){
		parent::_construct();
		$this->_init('rma/rcomments');
	}

       public function getRcidByRmaid($rmaid){
                  $db = Mage::getSingleton('core/resource')->getConnection('core_read');
                  $que = "SELECT id FROM `rma_comments`  where rmaid='$rmaid'";
                  $resultn = $db->fetchCol($que);
                  return $resultn;
      }

}