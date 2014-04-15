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

class Jayje_Rma_Block_Adminhtml_Rma_Edit_Tab_Comments extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct(){     
        $this->setTemplate('rma/comments.phtml');
    }

    public function getRmaComments(){
        $id = $this->getRequest()->getParam('id');  
        return    Mage::getSingleton('rma/rma')->getRmaComments($id);
   }

}
