<?php

class Magestore_Giftvoucher_Block_Adminhtml_Giftvoucher_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
	  parent::__construct();
	  $this->setId('giftvoucherGrid');
	  $this->setDefaultSort('giftvoucher_id');
	  $this->setDefaultDir('DESC');
	  $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
	  $collection = Mage::getModel('giftvoucher/giftvoucher')->getCollection()->joinHistory();
	  
	 // echo $collection->getSelect();
	  
	  $this->setCollection($collection);
	  return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
	  $this->addColumn('giftvoucher_id', array(
		  'header'	=> Mage::helper('giftvoucher')->__('ID'),
		  'align'	 =>'right',
		  'width'	 => '50px',
		  'index'	 => 'giftvoucher_id',
          'filter_index'    => 'main_table.giftvoucher_id'
	  ));

	  $this->addColumn('gift_code', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Gift Voucher Code'),
		  'align'	 =>'left',
		  'index'	 => 'gift_code',
          'filter_index'    => 'main_table.gift_code'
	  ));
	  
		 $this->addColumn('gift_cardno', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Gift Card No'),
		  'align'	 =>'left',
		  'index'	 => 'gift_cardno',
          'filter_index'    => 'main_table.gift_cardno'
	  ));

 	$this->addColumn('gift_pincode', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Gift Pincode'),
		  'align'	 =>'left',
		  'index'	 => 'gift_pincode',
          'filter_index'    => 'main_table.gift_pincode'
	  ));

	  $this->addColumn('history_amount', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Initial amount'),
		  'align'	 =>'left',
		  'index'	 => 'history_amount',
		  'type'	  => 'currency',
		  'currency'  => 'history_currency',
          'filter_index'    => 'history.amount'
	  ));
	  
	  $this->addColumn('balance', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Balance'),
		  'align'	 =>'left',
		  'index'	 => 'balance',
		  'type'	  => 'currency',
		  'currency'  => 'currency',
          'filter_index'    => 'main_table.balance'
	  ));

	  $this->addColumn('status', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Status'),
		  'align'	 => 'left',
		  'index'	 => 'status',
		  'type'	  => 'options',
		  'options'   => Mage::getSingleton('giftvoucher/status')->getOptionArray(),
          'filter_index'    => 'main_table.status'
	  ));
	  
	  $this->addColumn('customer_name', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Customer'),
		  'align'	 =>'left',
		  'index'	 =>'customer_name',
          'filter_index'    => 'main_table.customer_name'
	  ));
	  
	  $this->addColumn('order_increment_id', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Order'),
		  'align'	 =>'left',
		  'index'	 =>'order_increment_id',
          'filter_index'    => 'history.order_increment_id'
	  ));
	  
	  $this->addColumn('recipient_name', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Recipient'),
		  'align'	 =>'left',
		  'index'	 =>'recipient_name',
          'filter_index'    => 'main_table.recipient_name'
	  ));
	  /////////
	   $this->addColumn('activated_email', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Activated by Customer Email'),
		  'align'	 =>'left',
		  'index'	 =>'activated_email',
          'filter_index'    => 'main_table.activated_email'
	  ));
	  $this->addColumn('activated_cid', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Activated by Customer Id'),
		  'align'	 =>'left',
		  'index'	 =>'activated_cid',
          'filter_index'    => 'main_table.activated_cid'
	  ));
	     $this->addColumn('activated_name', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Activated by Customer Name'),
		  'align'	 =>'left',
		  'index'	 =>'activated_name',
          'filter_index'    => 'main_table.activated_name'
	  ));
	 
	  
	  $this->addColumn('activated_on', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Activated At'),
		  'align'	 =>'left',
		  'index'	 =>'activated_on',
		  'type'	  =>'datetime',
          'filter_index'    => 'main_table.activated_on'
	  ));
	  
	  
	  
	  ///////
	  
	  $this->addColumn('created_at', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Created at'),
		  'align'	 =>'left',
		  'index'	 =>'created_at',
		  'type'	  =>'datetime',
          'filter_index'    => 'history.created_at'
	  ));
	  
	  $this->addColumn('expired_at', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Expired at'),
		  'align'	 =>'left',
		  'index'	 =>'expired_at',
		  'type'	  =>'date',
          'filter_index'    => 'main_table.expired_at'
	  ));
	  
	  $this->addColumn('store_id', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Store view'),
		  'align'	 =>'left',
		  'index'	 =>'store_id',
		  'type'	  =>'store',
		  'store_all' =>true,
		  'store_view'=>true,
          'filter_index'    => 'main_table.store_id',
          'filter_condition_callback'   => array($this,'filterByGiftvoucherStoreId')
	  ));
	  
	  $this->addColumn('extra_content', array(
		  'header'	=> Mage::helper('giftvoucher')->__('Extra content'),
		  'align'	 =>'left',
		  'index'	 =>'extra_content',
          'filter_index'    => 'history.extra_content'
	  ));
	  
	  $this->addColumn('action',
		array(
			'header'	=>  Mage::helper('giftvoucher')->__('Action'),
			'width'	 => '70px',
			'type'	  => 'action',
			'getter'	=> 'getId',
			'actions'   => array(
				array(
					'caption'   => Mage::helper('giftvoucher')->__('Edit'),
					'url'	   => array('base'=> '*/*/edit'),
					'field'	 => 'id'
				)
			),
			'filter'	=> false,
			'sortable'  => false,
			'index'	 => 'stores',
			'is_system' => true,
	  ));
	  
	  $this->addExportType('*/*/exportCsv', Mage::helper('giftvoucher')->__('CSV'));
	  $this->addExportType('*/*/exportXml', Mage::helper('giftvoucher')->__('XML'));
		
	  return parent::_prepareColumns();
  }
	
	public function getCsv(){
		$csv = '';
		$this->_isExport = true;
		$this->_prepareGrid();
		$this->getCollection()->getSelect()->limit();
		$this->getCollection()->setPageSize(0);
		$this->getCollection()->load();
		$this->_afterLoadCollection();
		
		$this->addColumn('currency',array('index' => 'currency'));
		$this->addColumn('customer_id',array('index' => 'customer_id'));
		$this->addColumn('customer_email',array('index' => 'customer_email'));
		$this->addColumn('recipient_email',array('index' => 'recipient_email'));
		$this->addColumn('recipient_address',array('index' => 'recipient_address'));
		$this->addColumn('message',array('index' => 'message'));
		$this->addColumn('history_currency',array('index' => 'history_currency'));

		$data = array();
		foreach ($this->_columns as $column)
			if (!$column->getIsSystem())
				$data[] = '"'.$column->getIndex().'"';
		
		$csv .= implode(',', $data)."\n";

		foreach ($this->getCollection() as $item) {
			$data = array();
			foreach ($this->_columns as $column){
				if (!$column->getIsSystem()){
					$data[] = '"'.str_replace(array('"', '\\', chr(13), chr(10)), array('""', '\\\\', '', '\n'), $item->getData($column->getIndex())).'"';
				}
			}
			$csv .= implode(',', $data)."\n";
		}

		if ($this->getCountTotals()){
			$data = array();
			foreach ($this->_columns as $column) {
				if (!$column->getIsSystem()) {
					$data[] = '"'.str_replace(array('"', '\\'), array('""', '\\\\'), $column->getRowFieldExport($this->getTotals())).'"';
				}
			}
			$csv.= implode(',', $data)."\n";
		}

		return $csv;
	}

	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('giftvoucher_id');
		$this->getMassactionBlock()->setFormFieldName('giftvoucher');

		$this->getMassactionBlock()->addItem('delete', array(
			 'label'	=> Mage::helper('giftvoucher')->__('Delete'),
			 'url'	  => $this->getUrl('*/*/massDelete'),
			 'confirm'  => Mage::helper('giftvoucher')->__('Are you sure?')
		));

		$statuses = Mage::getSingleton('giftvoucher/status')->getOptionArray();

		array_unshift($statuses, array('label'=>'', 'value'=>''));
		$this->getMassactionBlock()->addItem('status', array(
			 'label'=> Mage::helper('giftvoucher')->__('Change status'),
			 'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
			 'additional' => array(
					'visibility' => array(
						 'name' => 'status',
						 'type' => 'select',
						 'class' => 'required-entry',
						 'label' => Mage::helper('giftvoucher')->__('Status'),
						 'values' => $statuses
					 )
			 )
		));
		
		$this->getMassactionBlock()->addItem('email', array(
			 'label'	=> Mage::helper('giftvoucher')->__('Send email'),
			 'url'	  => $this->getUrl('*/*/massEmail'),
			 'confirm'  => Mage::helper('giftvoucher')->__('Are you sure?')
		));
		
		$this->getMassactionBlock()->addItem('print',array(
			'label'		=> $this->__('Print Gift Voucher'),
			'url'		=> $this->getUrl('*/*/massPrint'),
		));
		
		return $this;
	}

  public function getRowUrl($row)
  {
	  return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
  
  public function filterByGiftvoucherStoreId($collection, $column) {
    $value = $column->getFilter()->getValue();
    if (isset($value) && $value) {
        $collection->addFieldToFilter("main_table.store_id",$value);
    }
  }
}
