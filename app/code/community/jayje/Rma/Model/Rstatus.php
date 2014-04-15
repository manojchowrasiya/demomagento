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

class Jayje_Rma_Model_Rstatus extends Mage_Core_Model_Abstract{
	const CACHE_TAG = 'rma_rstatus';


             public  function getAllStatusArray($type='rma_status'){ 
                     $sql = "SELECT label, code FROM `rstatus`  where type='$type' and status='1'";
                      $db = Mage::getSingleton('core/resource')->getConnection('core_read');
                      $result = $db->fetchAll($sql);
                          foreach($result as $row){
                            $bestForArray = array("label" => $row['label'],"value" => $row['code']); 
                            $arr[]=$bestForArray;
                          } 
               
               return $arr;
             }


             public  function getAllStatus($type='rma_status'){ 
                 $sql = "SELECT label, code FROM `rstatus`  where type='$type' and status='1'";
                 $db = Mage::getSingleton('core/resource')->getConnection('core_read');
                 $result = $db->fetchAll($sql);               
               return $result;
             }



             public  function getStatusById($rsid){ 
                 $sql = "SELECT label FROM `rstatus`  where code='$rsid'";
                 $db = Mage::getSingleton('core/resource')->getConnection('core_read');
                 $result = $db->fetchOne($sql);               
               return $result;
             }


	public function _construct(){
		parent::_construct();
		$this->_init('rma/rstatus');
	}


	protected function _beforeSave(){
		parent::_beforeSave();
		$now = Mage::getSingleton('core/date')->gmtDate();
		if ($this->isObjectNew()){
			$this->setCreatedAt($now);
		}
		$this->setUpdatedAt($now);
		return $this;
	}
	protected function _afterSave() {
		return parent::_afterSave();
	}
}