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

class Jayje_Rma_Block_Rma extends Mage_Core_Block_Template
{
    public function _prepareLayout()
    {
	return parent::_prepareLayout();
    }
    
     public function getRma(){ 
        if (!$this->hasData('rma')) {
            $this->setData('rma', Mage::registry('rma'));
        }
        return $this->getData('rma');
        
    }
    
    public function getOrder(){
         $result = Mage::getSingleton('rma/rma')->getOrder();
         while( $resultn = $result->fetch(PDO::FETCH_ASSOC)){
             extract($resultn);
             echo '<option value="'.$entity_id.'">'.$increment_id.' - '.$store_currency_code.' '.$grand_total.'</option>'; 
        }
        //return $result;
    } 

    public function getRmaList(){
        $result = Mage::getSingleton('rma/rma')->getRmaList();
        return $result;
    }

    public function getRmaDetails(){
        return Mage::getSingleton('rma/rma')->getRmaInfo();
    }
    
    public function getRmaProducts(){
         return    Mage::getSingleton('rma/rma')->getRmaProducts();
    }
    
    public function getRmaComments(){
        return    Mage::getSingleton('rma/rma')->getRmaComments();
   }
        
        
    public function getRType(){

         $return_types = Mage::getModel('rma/rstatus')->getAllStatus('rma_returntype');
         foreach($return_types as $return_type){
             echo '<option value="'.$return_type['code'].'">'.$return_type['label'].'</option>'; 
         }
                    
   }
}

?>


