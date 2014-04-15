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


class Jayje_Rma_Block_Adminhtml_Rstatus_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{
	public function __construct(){
		parent::__construct();
		$this->setId('rstatus_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('rma')->__('Rstatus'));
	}


	protected function _beforeToHtml(){
		$this->addTab('form_section', array(
			'label'		=> Mage::helper('rma')->__('Rstatus'),
			'title'		=> Mage::helper('rma')->__('Rstatus'),
			'content' 	=> $this->getLayout()->createBlock('rma/adminhtml_rstatus_edit_tab_form')->toHtml(),
		));
		return parent::_beforeToHtml();
	}
}