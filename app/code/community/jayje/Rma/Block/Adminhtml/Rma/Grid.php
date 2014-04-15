<?php

class Jayje_Rma_Block_Adminhtml_Rma_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();

      $this->setId('rmaGrid');
      $this->setDefaultSort('rma_id');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('rma/rma')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('rma_id', array(
          'header'    => Mage::helper('rma')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'rma_id',
      ));

      $this->addColumn('created_time', array(
          'header'    => Mage::helper('rma')->__('Created Time'),
          'align'     =>'left',
          'index'     => 'created_time',
      ));

      $this->addColumn('increment_id', array(
          'header'    => Mage::helper('rma')->__('Order ID'),
          'align'     =>'left',
          'index'     => 'increment_id',
      ));

      $this->addColumn('package', array(
          'header'    => Mage::helper('rma')->__('Package'),
          'align'     =>'left',
          'index'     => 'package',
      ));

      $this->addColumn('email', array(
          'header'    => Mage::helper('rma')->__('Customer Email'),
          'align'     =>'left',
          'index'     => 'email',
      ));

      $this->addColumn('adminstatus', array(
          'header'    => Mage::helper('rma')->__('Status'),
          'align'     =>'left',
          'index'     => 'adminstatus',
         'type'      => 'options',
         'options'   => $this->getAllStatus('rma_status')
      ));

      $this->addColumn('tracking_no', array(
          'header'    => Mage::helper('rma')->__('Tracking No:'),
          'align'     =>'left',
          'index'     => 'tracking_no',
      ));

      $this->addColumn('return_type', array(
          'header'    => Mage::helper('rma')->__('Return Type'),
          'align'     =>'left',
          'index'     => 'return_type',
         'type'      => 'options',
         'options'   => $this->getAllStatus('rma_returntype')
      ));


  

	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('rma')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

/**
	   *       $this->addColumn('status', array(
	   *           'header'    => Mage::helper('rma')->__('Status'),
	   *           'align'     => 'left',
	   *           'width'     => '80px',
	   *           'index'     => 'status',
	   *           'type'      => 'options',
	   *           'options'   => array(
	   *               1 => 'Enabled',
	   *               2 => 'Disabled',
	   *           ),
	   *       ));
	   */	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('rma')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('rma')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('rma')->__('CSV'));
		$this->addExportType('*/*/exportExcel', Mage::helper('rma')->__('Excel'));
		$this->addExportType('*/*/exportXml', Mage::helper('rma')->__('XML'));
 
	  
      return parent::_prepareColumns();
  }
  
    protected function getAllStatus($status=''){
      $result = Mage::getModel('rma/rstatus')->getAllStatus($status);
         foreach($result as $row){
                  $arr[$row['code']] = $row['label']; 
         } 
         return $arr;
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('rma_id');
        $this->getMassactionBlock()->setFormFieldName('rma');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('rma')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('rma')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('rma/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('rma')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('rma')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}