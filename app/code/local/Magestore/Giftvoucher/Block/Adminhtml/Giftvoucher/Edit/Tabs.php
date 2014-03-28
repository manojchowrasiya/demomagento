<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('giftvoucher_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('giftvoucher')->__('Gift Voucher Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('giftvoucher')->__('General Information'),
          'title'     => Mage::helper('giftvoucher')->__('General Information'),
          'content'   => $this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_edit_tab_form')->toHtml(),
      ));
      
      $this->addTab('message_section', array(
          'label'     => Mage::helper('giftvoucher')->__('Message Information'),
          'title'     => Mage::helper('giftvoucher')->__('Message Information'),
          'content'   => $this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_edit_tab_message')->toHtml(),
      ));
      
      if ($id = $this->getRequest()->getParam('id')){
	      $this->addTab('history_section', array(
	          'label'     => Mage::helper('giftvoucher')->__('Transaction History'),
	          'title'     => Mage::helper('giftvoucher')->__('Transaction History'),
	          'content'   => $this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_edit_tab_history')->setGiftvoucher($id)->toHtml(),
	      ));
      }
     
      return parent::_beforeToHtml();
  }
}