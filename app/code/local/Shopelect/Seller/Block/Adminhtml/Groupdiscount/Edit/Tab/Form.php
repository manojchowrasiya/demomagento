<?php
class Shopelect_Seller_Block_Adminhtml_Groupdiscount_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("seller_form", array("legend"=>Mage::helper("seller")->__("Item information")));

								
						 $fieldset->addField('group_id', 'select', array(
						'label'     => Mage::helper('seller')->__('Group'),
						'values'   => Shopelect_Seller_Block_Adminhtml_Groupdiscount_Grid::getValueArray2(),
						'name' => 'group_id',
						));
						$fieldset->addField("discount", "text", array(
						"label" => Mage::helper("seller")->__("Discount"),
						"name" => "discount",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getGroupdiscountData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getGroupdiscountData());
					Mage::getSingleton("adminhtml/session")->setGroupdiscountData(null);
				} 
				elseif(Mage::registry("groupdiscount_data")) {
				    $form->setValues(Mage::registry("groupdiscount_data")->getData());
				}
				return parent::_prepareForm();
		}
}
