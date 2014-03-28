<?php
class Iksula_Homepagebanner_Block_Adminhtml_Homeslider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("homepagebanner_form", array("legend"=>Mage::helper("homepagebanner")->__("Item information")));

				
						$fieldset->addField("name", "text", array(
						"label" => Mage::helper("homepagebanner")->__("Name"),
						"name" => "name",
						));
									
						$fieldset->addField('image', 'image', array(
						'label' => Mage::helper('homepagebanner')->__('Upload image'),
						'name' => 'image',
						'note' => '(*.jpg, *.png, *.gif)',
						));
						$fieldset->addField("sortorder", "text", array(
						"label" => Mage::helper("homepagebanner")->__("Sort Order"),
						"name" => "sortorder",
						));
					
						$fieldset->addField("url", "text", array(
						"label" => Mage::helper("homepagebanner")->__("Url"),
						"name" => "url",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getHomesliderData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getHomesliderData());
					Mage::getSingleton("adminhtml/session")->setHomesliderData(null);
				} 
				elseif(Mage::registry("homeslider_data")) {
				    $form->setValues(Mage::registry("homeslider_data")->getData());
				}
				return parent::_prepareForm();
		}
}
