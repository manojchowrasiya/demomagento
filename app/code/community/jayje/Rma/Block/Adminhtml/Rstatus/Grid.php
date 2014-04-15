<?php
/**
 * Jayje_Rma extension
 *  
 * @category   	Return Merchant Authorization Magento - wakensys
 * @package	Jayje_Rma
 * @copyright  	Copyright (c) 2013
 * @license	http://opensource.org/licenses/mit-license.php MIT License
 * @category	Jayje
 * @package	Jayje_Rma
 * @author        wakensys
 * @developper   s.ratheepan@gmail.com
 */

class Jayje_Rma_Block_Adminhtml_Rstatus_Grid extends Mage_Adminhtml_Block_Widget_Grid{


	public function __construct(){
		parent::__construct();
		$this->setId('rstatusGrid');
		$this->setDefaultSort('entity_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
		$this->setUseAjax(true);
	}


	protected function _prepareCollection(){
		$collection = Mage::getModel('rma/rstatus')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}


	protected function _prepareColumns(){
		$this->addColumn('entity_id', array(
			'header'	=> Mage::helper('rma')->__('Id'),
			'index'		=> 'entity_id',
			'type'		=> 'number'
		));
                          
		$this->addColumn('type', array(
			'header'=> Mage::helper('rma')->__('Type'),
			'index' => 'type',
			'type'   => 'options',
                                       'options'   => array(
                                                    'rma_status' => 'Rma Status',
                                                    'rma_returntype' => 'Rma Return Type',),

		));
                          
		$this->addColumn('code', array(
			'header'=> Mage::helper('rma')->__('Code'),
			'index' => 'code',
			'type'  => 'text',

		));
                          
		$this->addColumn('label', array(
			'header'=> Mage::helper('rma')->__('Label'),
			'index' => 'label',
			'type'  => 'label',

		));
                          
		$this->addColumn('status', array(
			'header'	=> Mage::helper('rma')->__('Status'),
			'index'		=> 'status',
			'type'		=> 'options',
			'options'	=> array(
				'1' => Mage::helper('rma')->__('Enabled'),
				'0' => Mage::helper('rma')->__('Disabled'),
			)
		));
		$this->addColumn('created_at', array(
			'header'	=> Mage::helper('rma')->__('Created at'),
			'index' 	=> 'created_at',
			'width' 	=> '120px',
			'type'  	=> 'datetime',
		));
                          
		$this->addColumn('updated_at', array(
			'header'	=> Mage::helper('rma')->__('Updated at'),
			'index' 	=> 'updated_at',
			'width' 	=> '120px',
			'type'  	=> 'datetime',
		));
		$this->addColumn('action',
			array(
				'header'=>  Mage::helper('rma')->__('Action'),
				'width' => '100',
				'type'  => 'action',
				'getter'=> 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('rma')->__('Edit'),
						'url'   => array('base'=> '*/*/edit'),
						'field' => 'id'
					)
				),
				'filter'=> false,
				'is_system'	=> true,
				'sortable'  => false,
		));
		$this->addExportType('*/*/exportCsv', Mage::helper('rma')->__('CSV'));
		$this->addExportType('*/*/exportExcel', Mage::helper('rma')->__('Excel'));
		$this->addExportType('*/*/exportXml', Mage::helper('rma')->__('XML'));
		return parent::_prepareColumns();
	}


	protected function _prepareMassaction(){
		$this->setMassactionIdField('entity_id');
		$this->getMassactionBlock()->setFormFieldName('rstatus');
		$this->getMassactionBlock()->addItem('delete', array(
			'label'=> Mage::helper('rma')->__('Delete'),
			'url'  => $this->getUrl('*/*/massDelete'),
			'confirm'  => Mage::helper('rma')->__('Are you sure?')
		));
		$this->getMassactionBlock()->addItem('status', array(
			'label'=> Mage::helper('rma')->__('Change status'),
			'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
			'additional' => array(
				'status' => array(
						'name' => 'status',
						'type' => 'select',
						'class' => 'required-entry',
						'label' => Mage::helper('rma')->__('Status'),
						'values' => array(
								'1' => Mage::helper('rma')->__('Enabled'),
								'0' => Mage::helper('rma')->__('Disabled'),
						)
				)
			)
		));
		return $this;
	}


	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}


	public function getGridUrl(){
		return $this->getUrl('*/*/grid', array('_current'=>true));
	}
}