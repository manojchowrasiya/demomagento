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

class Jayje_Rma_Block_Adminhtml_Rstatus_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{


	public function __construct(){
		parent::__construct();
		$this->_blockGroup = 'rma';
		$this->_controller = 'adminhtml_rstatus';
		$this->_updateButton('save', 'label', Mage::helper('rma')->__('Save Rstatus'));
		$this->_updateButton('delete', 'label', Mage::helper('rma')->__('Delete Rstatus'));
		$this->_addButton('saveandcontinue', array(
			'label'		=> Mage::helper('rma')->__('Save And Continue Edit'),
			'onclick'	=> 'saveAndContinueEdit()',
			'class'		=> 'save',
		), -100);
		$this->_formScripts[] = "
			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}


	public function getHeaderText(){
		if( Mage::registry('rstatus_data') && Mage::registry('rstatus_data')->getId() ) {
			return Mage::helper('rma')->__("Edit Rstatus '%s'", $this->htmlEscape(Mage::registry('rstatus_data')->getLabel()));
		} 
		else {
			return Mage::helper('rma')->__('Add Rstatus');
		}
	}
}