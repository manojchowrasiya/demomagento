<?php
	
class Iksula_Homepagebanner_Block_Adminhtml_Homeslider_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "homepagebanner";
				$this->_controller = "adminhtml_homeslider";
				$this->_updateButton("save", "label", Mage::helper("homepagebanner")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("homepagebanner")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("homepagebanner")->__("Save And Continue Edit"),
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
				if( Mage::registry("homeslider_data") && Mage::registry("homeslider_data")->getId() ){

				    return Mage::helper("homepagebanner")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("homeslider_data")->getId()));

				} 
				else{

				     return Mage::helper("homepagebanner")->__("Add Item");

				}
		}
}