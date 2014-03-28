<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
	  $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('giftvoucher_form', array('legend'=>Mage::helper('giftvoucher')->__('General Information')));
     
      $fieldset->addField('gift_code', 'text', array(
          'label'     => Mage::helper('giftvoucher')->__('Gift Voucher Code'),
          'required'  => true,
          'name'      => 'gift_code',
          'value'     => Mage::helper('giftvoucher')->getGeneralConfig('pattern'),
          'note'      => Mage::helper('giftvoucher')->__('Pattern examples:<br/><strong>[A.8] : 8 alpha chars<br/>[N.4] : 4 numerics<br/>[AN.6] : 6 alphanumeric<br/>GIFT-[A.4]-[AN.6] : GIFT-ADFA-12NF0O</strong>'),
      ));	

	$fieldset->addField('gift_cardno', 'text', array(
          'label'     => Mage::helper('giftvoucher')->__('Gift Cardno Pattern'),
          'required'  => true,
          'name'      => 'gift_cardno',
          'value'     => Mage::helper('giftvoucher')->getGeneralConfig('cardnopattern'),
          'note'      => Mage::helper('giftvoucher')->__('Pattern examples:<br/><strong>[A.8] : 8 alpha chars<br/>[N.4] : 4 numerics<br/>[AN.6] : 6 alphanumeric<br/>GIFT-[A.4]-[AN.6] : GIFT-ADFA-12NF0O</strong>'),
      ));

	$fieldset->addField('gift_pincode', 'text', array(
          'label'     => Mage::helper('giftvoucher')->__('Gift Pincode Pattern'),
          'required'  => true,
          'name'      => 'gift_pincode',
          'value'     => Mage::helper('giftvoucher')->getGeneralConfig('pincodepattern'),
          'note'      => Mage::helper('giftvoucher')->__('Pattern examples:<br/><strong>[A.8] : 8 alpha chars<br/>[N.4] : 4 numerics<br/>[AN.6] : 6 alphanumeric<br/>GIFT-[A.4]-[AN.6] : GIFT-ADFA-12NF0O</strong>'),
      ));
     
      $fieldset->addField('balance', 'text', array(
          'label'     => Mage::helper('giftvoucher')->__('Balance'),
          'required'  => true,
          'name'      => 'balance',
      ));
     
      $fieldset->addField('currency', 'select', array(
          'label'     => Mage::helper('giftvoucher')->__('Currency'),
          'required'  => false,
          'name'      => 'currency',
          'value'     => Mage::app()->getStore()->getDefaultCurrencyCode(),
          'values'    => Mage::getSingleton('adminhtml/system_config_source_currency')->toOptionArray(false),
      ));

      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('giftvoucher')->__('Status'),
          'name'      => 'giftvoucher_status',
          'values'    => Mage::getModel('giftvoucher/status')->getOptions(),
      ));
     
      $fieldset->addField('expired_at', 'date', array(
          'label'     => Mage::helper('giftvoucher')->__('Expired at'),
          'required'  => false,
          'name'      => 'expired_at',
          'image'     => $this->getSkinUrl('images/grid-cal.gif'),
          'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
          'format'    => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
      ));

      $fieldset->addField('store_id', 'select', array(
          'label'     => Mage::helper('giftvoucher')->__('Store view'),
          'name'      => 'store_id',
          'required'  => false,
          'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
      ));
      
      $fieldset->addField('giftvoucher_comments', 'editor', array(
          'label'     => Mage::helper('giftvoucher')->__('Update comments'),
          'required'  => false,
          'name'      => 'giftvoucher_comments',
          'style'     => 'height:100px;',
      ));
      
      if (Mage::registry('giftvoucher_data')){
		  $form->addValues(Mage::registry('giftvoucher_data')->getData());
      }
      
      return parent::_prepareForm();
  }
}
