<?php

class Iksula_Homepagebanner_Block_Adminhtml_Homeslider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("homesliderGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("homepagebanner/homeslider")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("homepagebanner")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
				$this->addColumn("name", array(
				"header" => Mage::helper("homepagebanner")->__("Name"),
				"index" => "name",
				));
				$this->addColumn("sortorder", array(
				"header" => Mage::helper("homepagebanner")->__("Sort Order"),
				"index" => "sortorder",
				));
				$this->addColumn("url", array(
				"header" => Mage::helper("homepagebanner")->__("Url"),
				"index" => "url",
				));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_homeslider', array(
					 'label'=> Mage::helper('homepagebanner')->__('Remove Homeslider'),
					 'url'  => $this->getUrl('*/adminhtml_homeslider/massRemove'),
					 'confirm' => Mage::helper('homepagebanner')->__('Are you sure?')
				));
			return $this;
		}
			

}