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


class Jayje_Rma_Model_Status extends Varien_Object
{
   // const STATUS_ENABLED	= 1;
  //  const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
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
    public function toOptionArray()
    {
          
        return array(
            array('value'=>1, 'label'=>Mage::helper('rma')->__('Hello')),
            array('value'=>2, 'label'=>Mage::helper('rma')->__('Goodbye')),
            array('value'=>3, 'label'=>Mage::helper('rma')->__('Yes')),            
            array('value'=>4, 'label'=>Mage::helper('rma')->__('No')),                       
        );
    
           
    }
    
    
}