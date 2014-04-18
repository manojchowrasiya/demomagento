<?php

class Shopelect_Seller_Block_Adminhtml_Groupdiscount_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("groupdiscountGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("seller/groupdiscount")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("seller")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
                
						$this->addColumn('group_id', array(
						'header' => Mage::helper('seller')->__('Group'),
						'index' => 'group_id',
						'type' => 'options',
						'options'=>Shopelect_Seller_Block_Adminhtml_Groupdiscount_Grid::getOptionArray2(),				
						));
						
				$this->addColumn("discount", array(
				"header" => Mage::helper("seller")->__("Discount"),
				"index" => "discount",
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
			$this->getMassactionBlock()->addItem('remove_groupdiscount', array(
					 'label'=> Mage::helper('seller')->__('Remove Groupdiscount'),
					 'url'  => $this->getUrl('*/adminhtml_groupdiscount/massRemove'),
					 'confirm' => Mage::helper('seller')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray2()
		{
            $data_array=array(); 
			$data_array[0]='Guest';
			$data_array[1]='Login';
            return($data_array);
		}
		static public function getValueArray2()
		{
            $data_array=array();
			foreach(Shopelect_Seller_Block_Adminhtml_Groupdiscount_Grid::getOptionArray2() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}