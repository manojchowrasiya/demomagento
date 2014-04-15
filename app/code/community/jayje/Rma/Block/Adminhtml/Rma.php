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

class Jayje_Rma_Block_Adminhtml_Rma extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_rma';
    $this->_blockGroup = 'rma';
    $this->_headerText = Mage::helper('rma')->__('RMA Manager');
  //  $this->_addButtonLabel = Mage::helper('rma')->__('Add RMA');
    parent::__construct();
    $this->removeButton('add');
  }
    public function getOrderInfo($id='')
    {
         if($id==''){
             $orderid = Mage::registry('rma_data')->getOrderId();
             $rmaid = Mage::registry('rma_data')->getRmaId();
              return array($orderid, $rmaid);
         }else{
             $orderid  = Mage::getModel('rma/rma')->load($id)->getOrderId();  print_r($model);
             return array($orderid, $rmaid);

         }
    }
    public function getRmaProducts($id='') 
    { 
         if($id==''){
             $rmaid = Mage::registry('rma_data')->getRmaId();
         }else{
             $rmaid = $id;
         }
             $db = Mage::getSingleton('core/resource')->getConnection('core_read');
             $que = "SELECT * FROM `rma_products` WHERE rmaid ='$rmaid' LIMIT 0 , 30";
             $result = $db->query($que);
             return $result;
 
    }
    
     public function getRTypeCombo(){
            $return_type = Mage::getStoreConfig('jayje_section/jayje_group/jayje_type',Mage::app()->getStore());
            $return_types = explode(",",$return_type);
            //print_r($satuss);
            foreach($return_types as $return_type){
               $bestForArray = array(
                "label" => $return_type,
                "value" => $return_type
                );
               $arr[]=$bestForArray;
            } 
          return $arr;          
        }
     public function getStatusCombo(){
            $satus = Mage::getStoreConfig('jayje_section/jayje_group/jayje_status',Mage::app()->getStore());
            $satuss = explode(",",$satus);
            //print_r($satuss);
            foreach($satuss as $satus){
               $bestForArray = array(
                "label" => $satus,
                "value" => $satus
                );
               $arr[]=$bestForArray;
            } 
          return $arr;          
        }
}