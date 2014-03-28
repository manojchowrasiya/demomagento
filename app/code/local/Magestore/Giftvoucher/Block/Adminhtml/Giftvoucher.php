<?php
class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_giftvoucher';
    $this->_blockGroup = 'giftvoucher';
    $this->_headerText = Mage::helper('giftvoucher')->__('Gift Voucher Manager');
    $this->_addButtonLabel = Mage::helper('giftvoucher')->__('Add Gift Voucher');
    parent::__construct();
	$this->_addButton('import_giftvoucher',array(
		'label'		=> Mage::helper('giftvoucher')->__('Import Gift Voucher'),
		'onclick'	=> "setLocation('{$this->getUrl('*/*/import')}')",
		'class'		=> 'add'
	),-1);
  }
}