<?php
class Shopelect_Seller_Block_Adminhtml_Groupdiscount_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("groupdiscount_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("seller")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("seller")->__("Item Information"),
				"title" => Mage::helper("seller")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("seller/adminhtml_groupdiscount_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
