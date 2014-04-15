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


class Jayje_Rma_Model_Config_Returntype extends Varien_Object
{
   // const STATUS_ENABLED	= 1;
  //  const STATUS_DISABLED	= 2;


    public function toOptionArray()
    {
          
        return Mage::getModel('rma/rstatus')->getAllStatusArray('rma_returntype');
    
           
    }
    
    
}