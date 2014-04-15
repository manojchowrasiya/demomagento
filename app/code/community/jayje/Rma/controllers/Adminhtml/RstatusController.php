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

class Jayje_Rma_Adminhtml_RstatusController extends Jayje_Rma_Controller_Adminhtml_Rma{


	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('rma/rstatus')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Status Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   


	protected function _initRstatus(){
		$rstatusId  = (int) $this->getRequest()->getParam('id');
		$rstatus	= Mage::getModel('rma/rstatus');
		if ($rstatusId) {
			$rstatus->load($rstatusId);
		}
		Mage::register('current_rstatus', $rstatus);
		return $rstatus;
	}

	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}


	public function gridAction() {
		$this->loadLayout()->renderLayout();
	}

	public function editAction() {
		$rstatusId	= $this->getRequest()->getParam('id');
		$rstatus  	= $this->_initRstatus();
		if ($rstatusId && !$rstatus->getId()) {
			$this->_getSession()->addError(Mage::helper('rma')->__('This rstatus no longer exists.'));
			$this->_redirect('*/*/');
			return;
		}
		$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		if (!empty($data)) {
			$rstatus->setData($data);
		}
		Mage::register('rstatus_data', $rstatus);
		$this->loadLayout();
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) { 
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true); 
		}
		$this->renderLayout();
	}

	public function newAction() {
		$this->_forward('edit');
	}

	public function saveAction() {
		if ($data = $this->getRequest()->getPost('rstatus')) {
			try {
				$rstatus = $this->_initRstatus();
				$rstatus->setCode('');
				$rstatus->setType('');
				$rstatus->addData($data);
				
				$rstatus->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('rma')->__('Rstatus was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $rstatus->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
			} 
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('There was a problem saving the rstatus.'));
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('Unable to find rstatus to save.'));
		$this->_redirect('*/*/');
	}

	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0) {
			try {
				$rstatus = Mage::getModel('rma/rstatus');
				$rstatus->setId($this->getRequest()->getParam('id'))->delete();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('rma')->__('Rstatus was successfully deleted.'));
				$this->_redirect('*/*/');
				return; 
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('There was an error deleteing rstatus.'));
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('Could not find rstatus to delete.'));
		$this->_redirect('*/*/');
	}

	public function massDeleteAction() {
		$rstatusIds = $this->getRequest()->getParam('rstatus');
		if(!is_array($rstatusIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('Please select rstatus to delete.'));
		}
		else {
			try {
				foreach ($rstatusIds as $rstatusId) {
					$rstatus = Mage::getModel('rma/rstatus');
					$rstatus->setId($rstatusId)->delete();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('rma')->__('Total of %d rstatus were successfully deleted.', count($rstatusIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('There was an error deleteing rstatus.'));
			}
		}
		$this->_redirect('*/*/index');
	}

	public function massStatusAction(){
		$rstatusIds = $this->getRequest()->getParam('rstatus');
		if(!is_array($rstatusIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('Please select rstatus.'));
		} 
		else {
			try {
				foreach ($rstatusIds as $rstatusId) {
				$rstatus = Mage::getSingleton('rma/rstatus')->load($rstatusId)
							->setStatus($this->getRequest()->getParam('status'))
							->setIsMassupdate(true)
							->save();
				}
				$this->_getSession()->addSuccess($this->__('Total of %d rstatus were successfully updated.', count($rstatusIds)));
			}
			catch (Mage_Core_Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('There was an error updating rstatus.'));
			}
		}
		$this->_redirect('*/*/index');
	}

	public function exportCsvAction(){
		$fileName   = 'rstatus.csv';
		$content	= $this->getLayout()->createBlock('rma/adminhtml_rstatus_grid')->getCsv();
		$this->_prepareDownloadResponse($fileName, $content);
	}

	public function exportExcelAction(){
		$fileName   = 'rstatus.xls';
		$content	= $this->getLayout()->createBlock('rma/adminhtml_rstatus_grid')->getExcelFile();
		$this->_prepareDownloadResponse($fileName, $content);
	}

	public function exportXmlAction(){
		$fileName   = 'rstatus.xml';
		$content	= $this->getLayout()->createBlock('rma/adminhtml_rstatus_grid')->getXml();
		$this->_prepareDownloadResponse($fileName, $content);
	}
}