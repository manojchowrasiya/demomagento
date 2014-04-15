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


class Jayje_Rma_Block_Adminhtml_Rma_Info extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {     
       // parent::__construct();
                  $this->setTemplate('rma/info.phtml');
 
    }


       public function getrid(){
          $id     = $this->getRequest()->getParam(); print_r($id);
       }
       
    public function getHeaderText()
    {
        //$rmaid = Mage::registry('rma_data')->getRmaId();
        if( Mage::registry('rma_data') && Mage::registry('rma_data')->getId() ) {
            return Mage::helper('rma')->__("Edit RMA '%s'",
            $this->htmlEscape(Mage::registry('rma_data')->getTitle()));
        }
    }


}