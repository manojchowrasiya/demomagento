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

class Jayje_Rma_Block_Adminhtml_Rstatus_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{

	protected function _prepareForm(){
                          $form = new Varien_Data_Form(array(
                          	'id' 		=> 'edit_form',
                          	'action' 	=> $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                          	'method' 	=> 'post',
                          	'enctype'	=> 'multipart/form-data'
                          )
		);
		$form->setUseContainer(true);
		$this->setForm($form);
		return parent::_prepareForm();
	}
}