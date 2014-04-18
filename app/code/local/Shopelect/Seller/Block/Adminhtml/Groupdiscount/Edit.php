<?php
	
class Shopelect_Seller_Block_Adminhtml_Groupdiscount_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "seller";
				$this->_controller = "adminhtml_groupdiscount";
				$this->_updateButton("save", "label", Mage::helper("seller")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("seller")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("seller")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("groupdiscount_data") && Mage::registry("groupdiscount_data")->getId() ){

				    return Mage::helper("seller")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("groupdiscount_data")->getId()));

				} 
				else{

				     return Mage::helper("seller")->__("Add Item");

				}
		}
}