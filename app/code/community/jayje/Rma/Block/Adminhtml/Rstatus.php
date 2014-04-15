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

class Jayje_Rma_Block_Adminhtml_Rstatus extends Mage_Adminhtml_Block_Widget_Grid_Container{


	public function __construct(){
	            
                         
		$this->_controller 		= 'adminhtml_rstatus';
		$this->_blockGroup 		= 'rma';
		$this->_headerText 		= Mage::helper('rma')->__('Manage RMA Status and Return Type');
		$this->_addButtonLabel 	= Mage::helper('rma')->__('Add New');                                                               
		parent::__construct();
	}
}