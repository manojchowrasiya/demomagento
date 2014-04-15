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


class Jayje_Rma_Model_Mysql4_Rma extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the rma_id refers to the key field in your database table.
        $this->_init('rma/rma', 'rma_id');
    }
}