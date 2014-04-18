<?php


class Shopelect_Seller_Block_Adminhtml_Groupdiscount extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_groupdiscount";
	$this->_blockGroup = "seller";
	$this->_headerText = Mage::helper("seller")->__("Groupdiscount Manager");
	$this->_addButtonLabel = Mage::helper("seller")->__("Add New Item");
	parent::__construct();
	
	}

}