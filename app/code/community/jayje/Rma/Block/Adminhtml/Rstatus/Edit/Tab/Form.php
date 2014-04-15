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

class Jayje_Rma_Block_Adminhtml_Rstatus_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form{	

	protected function _prepareForm(){
		$form = new Varien_Data_Form();
		$form->setFieldNameSuffix('rstatus');
		$this->setForm($form);
		$fieldset = $form->addFieldset('rstatus_form', 
                          array('legend'=>Mage::helper('rma')->__('RMA Status')));

		$fieldset->addField('type', 'select', array(
			'label' => Mage::helper('rma')->__('Type'),
			'name'  => 'type',
			'required'  => true,
			'class' => 'required-entry',
			'values'=> array(
				array(
					'value' => '',
					'label' => Mage::helper('rma')->__('Select Here'),
				),
				array(
					'value' => 'rma_status',
					'label' => Mage::helper('rma')->__('Rma Status'),
				),
				array(
					'value' => 'rma_returntype',                                                                                                                
				 	'label' => Mage::helper('rma')->__('Rma Return type'),
				),
			), 
                          ));
                          
		$fieldset->addField('label', 'text', array(
			'label' => Mage::helper('rma')->__('Label'),
			'name'  => 'label',
			'note'	=> $this->__('Status name'),
			'required'  => true,
			'class' => 'required-entry',

		));
                          
                      	$fieldset->addField('code', 'text', array(
			'label' => Mage::helper('rma')->__('Value'),
			'name'  => 'code',
			'required'  => true,
			'class' => 'required-entry',
		));
                          
		$fieldset->addField('status', 'select', array(
			'label' => Mage::helper('rma')->__('Status'),
			'name'  => 'status',
			'values'=> array(
				array(
					'value' => 1,
					'label' => Mage::helper('rma')->__('Enabled'),
				),
				array(
					'value' => 0,
					'label' => Mage::helper('rma')->__('Disabled'),
				),
			),
		));
	if (Mage::getSingleton('adminhtml/session')->getRstatusData()){
		$form->setValues(Mage::getSingleton('adminhtml/session')->getRstatusData());
		Mage::getSingleton('adminhtml/session')->setRstatusData(null);
	}
	elseif (Mage::registry('current_rstatus')){
		$form->setValues(Mage::registry('current_rstatus')->getData());
	}
	return parent::_prepareForm();
             }
}