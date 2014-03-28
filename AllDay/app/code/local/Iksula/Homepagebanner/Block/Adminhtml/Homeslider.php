<?php


class Iksula_Homepagebanner_Block_Adminhtml_Homeslider extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_homeslider";
	$this->_blockGroup = "homepagebanner";
	$this->_headerText = Mage::helper("homepagebanner")->__("Homeslider Manager");
	$this->_addButtonLabel = Mage::helper("homepagebanner")->__("Add New Item");
	parent::__construct();
	
	}

}