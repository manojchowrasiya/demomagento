<?php
class Iksula_Homepagebanner_Block_Adminhtml_Homeslider_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("homeslider_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("homepagebanner")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("homepagebanner")->__("Item Information"),
				"title" => Mage::helper("homepagebanner")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("homepagebanner/adminhtml_homeslider_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
