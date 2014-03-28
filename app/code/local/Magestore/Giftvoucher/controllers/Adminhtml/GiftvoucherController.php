<?php

class Magestore_Giftvoucher_Adminhtml_GiftvoucherController extends Mage_Adminhtml_Controller_Action
{
	public function exportCsvAction(){
		$fileName = 'giftvoucher.csv';
		$content = $this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_grid')->getCsv();
		$this->_prepareDownloadResponse($fileName,$content);
	}
	
	public function exportXmlAction(){
		$fileName = 'giftvoucher.xml';
		$content = $this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_grid')->getXml();
		$this->_prepareDownloadResponse($fileName,$content);
	}
	
	public function importAction(){
		$this->loadLayout()
			->_setActiveMenu('giftvoucher/giftvoucher')
			->_addContent($this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_import'));
		$this->_title($this->__('Gift Voucher'))
			->_title($this->__('Import Gift Voucher'));
		$this->renderLayout();
	}
	
	public function processImportAction(){
		if(isset($_FILES['filecsv'])){
			try{
				$fileName = $_FILES['filecsv']['tmp_name'];
				$csvObject = new Varien_File_Csv();
				$dataFile = $csvObject->getData($fileName);
				
				$count = array();
				$fields = array();
				$giftVoucherData = array();
				
				foreach ($dataFile as $row => $cols){
					if ($row == 0){
						$fields = $cols;
					}else{
						$giftVoucherData = array_combine($fields,$cols);
						$giftVoucher = Mage::getModel('giftvoucher/giftvoucher');
						if ($giftVoucherData['gift_code']){
							$giftVoucher->loadByCode($giftVoucherData['gift_code']);
							if ($giftVoucher->getId()){
								Mage::getSingleton('adminhtml/session')->addError($this->__('Gift Voucher code %s already existed',$giftVoucher->getGiftCode()));
								continue;
							}
						}
						if ($giftVoucherData['gift_cardno']){
							$giftVoucher->load($giftVoucherData['gift_cardno'] , 'gift_cardno');
							if ($giftVoucher->getId()){
								Mage::getSingleton('adminhtml/session')->addError($this->__('Gift card no %s already existed',$giftVoucher->getGiftCardno()));
								continue;
							}
						}
						
						if ($giftVoucherData['gift_pincode']){
							$giftVoucher->load($giftVoucherData['gift_pincode'] , 'gift_pincode');
							if ($giftVoucher->getId()){
								Mage::getSingleton('adminhtml/session')->addError($this->__('Gift pincode %s already existed',$giftVoucher->getGiftPincode()));
								continue;
							}
						}
						
						$statuses = array(
							'1'	=> 1, 'pending'	=> 1,
							'2'	=> 2, 'active'	=> 2,
							'3'	=> 3, 'disabled'=> 3,
							'4'	=> 4, 'used'	=> 4,
							'5'	=> 5, 'expired'	=> 5,
							'7'	=> 7, 'assigned'=> 7
						);
						if ($giftVoucherData['status'])
							$giftVoucherData['status'] = $statuses[$giftVoucherData['status']];
						unset($giftVoucherData['order_increment_id']);
						if ($giftVoucherData['history_amount'])
							$giftVoucherData['amount'] = $giftVoucherData['history_amount'];
						if ($giftVoucherData['extra_content'])
							$giftVoucherData['extra_content'] = str_replace('\n',chr(10),$giftVoucherData['extra_content']);
						else
							$giftVoucherData['extra_content'] = Mage::helper('giftvoucher')->__('Imported by %s',Mage::getSingleton('admin/session')->getUser()->getUsername());
						$giftVoucherData['recipient_address'] = str_replace('\n',chr(10),$giftVoucherData['recipient_address']);
						$giftVoucherData['message'] = str_replace('\n',chr(10),$giftVoucherData['message']);
						if (!$giftVoucherData['currency'])
							$giftVoucherData['currency'] = Mage::app()->getStore($giftVoucherData['store_id'])->getBaseCurrencyCode();
						try{
							$giftVoucher->setData($giftVoucherData)
								->setIncludeHistory(true)
								->setId(null)
								->save();
							$count[] = $giftVoucher->getId();
						}catch(Exception $e){
							Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						}
					}
				}


				if (count($count)){
					$successMessage = $this->__('Imported total %d Gift Voucher(s)',count($count));
					if ($this->getRequest()->getParam('print')){
						$url = $this->getUrl('*/*/massPrint',array(
							'giftvoucher' => implode(',',$count)
						));
						$successMessage .= "<script type='text/javascript'>document.observe('dom:loaded',function(){window.location.href = '$url';});</script>";
					}
					Mage::getSingleton('adminhtml/session')->addSuccess($successMessage);
					$this->_redirect('*/*/index');
					return $this;
				}else{
					Mage::getSingleton('adminhtml/session')->addError($this->__('No gift voucher imported'));
				}
			}catch(Exception $e){
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}else{
			Mage::getSingleton('adminhtml/session')->addError($this->__('No uploaded files'));
		}

		
		$this->_redirect('*/*/import');
	}
	
	public function massPrintAction(){
		$giftvoucherIds = $this->getRequest()->getParam('giftvoucher');
		if ($giftvoucherIds && is_string($giftvoucherIds)) $giftvoucherIds = explode(',',$giftvoucherIds);
		if(!is_array($giftvoucherIds) || !count($giftvoucherIds) ) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Gift Voucher(s)'));
			$this->_redirect('*/*/index');
        }else {
            $pdf = Mage::getModel('giftvoucher/pdf_giftvoucher')->getPdf($giftvoucherIds);
			$this->_prepareDownloadResponse('giftvoucher_'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf',$pdf->render(),'application/pdf');
        }
	}

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('giftvoucher/giftvoucher')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Gift Voucher Manager'), Mage::helper('adminhtml')->__('Gift Voucher Manager'));
		
		return $this;
	}
 
	public function indexAction() {
		//if(!Mage::helper('maegenotification')->checkLicenseKeyAdminController($this)){return;}
		$this->_title($this->__('Gift Voucher'))
			->_title($this->__('Manage Gift Voucher'));
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		//if(!Mage::helper('maegenotification')->checkLicenseKeyAdminController($this)){return;}
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('giftvoucher/giftvoucher')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('giftvoucher_data', $model);
			
			$this->_title($this->__('Gift Voucher'));
			if ($model->getId()){
				$this->_title($model->getGiftCode());
			}else{
				$this->_title($this->__('New Gift Voucher'));
			}

			$this->loadLayout();
			$this->_setActiveMenu('giftvoucher/giftvoucher');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Gift Voucher Manager'), Mage::helper('adminhtml')->__('Gift Voucher Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Gift Voucher News'), Mage::helper('adminhtml')->__('Gift Voucher News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_edit'))
				->_addLeft($this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftvoucher')->__('Gift Voucher does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()){	
			$model = Mage::getModel('giftvoucher/giftvoucher');
			
			$data = $this->_filterDates($data,array('expired_at'));
			if (!$data['expired_at']) $data['expired_at']=null;
			unset($data['order_increment_id']);
			$data['status'] = $data['giftvoucher_status'];
			$data['comments'] = $data['giftvoucher_comments'];
			$data['amount'] = $data['balance'];
			
			if ($this->getRequest()->getParam('id')){
				$data['action'] = Magestore_Giftvoucher_Model_Actions::ACTIONS_UPDATE;
				$data['extra_content'] = Mage::helper('giftvoucher')->__('Updated by %s',Mage::getSingleton('admin/session')->getUser()->getUsername());
			}else{
				$data['extra_content'] = Mage::helper('giftvoucher')->__('Created by %s',Mage::getSingleton('admin/session')->getUser()->getUsername());
			}
			
			$model->setData($data)
				->setIncludeHistory(true)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				$model->save();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('giftvoucher')->__('Gift voucher was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					if ($this->getRequest()->getParam('sendemail')){
						$emailSent = (int)$model->sendEmail()->getEmailSent();
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('giftvoucher')->__('and (%d) email(s) were completely sent.',$emailSent));
					}
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('giftvoucher')->__('Unable to find Gift Voucher to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('giftvoucher/giftvoucher');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Gift voucher was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $giftvoucherIds = $this->getRequest()->getParam('giftvoucher');
        if(!is_array($giftvoucherIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Gift Voucher(s)'));
        }else {
            try {
                foreach ($giftvoucherIds as $giftvoucherId) {
                    $giftvoucher = Mage::getModel('giftvoucher/giftvoucher')->load($giftvoucherId);
                    $giftvoucher->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($giftvoucherIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $giftvoucherIds = $this->getRequest()->getParam('giftvoucher');
        if(!is_array($giftvoucherIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Gift Voucher(s)'));
        } else {
            try {
                foreach ($giftvoucherIds as $giftvoucherId) {
                    $giftvoucher = Mage::getSingleton('giftvoucher/giftvoucher')
                        ->load($giftvoucherId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
						->setAction(Magestore_Giftvoucher_Model_Actions::ACTIONS_MASS_UPDATE)
						->setExtraContent(Mage::helper('giftvoucher')->__('Mass status updated by %s',Mage::getSingleton('admin/session')->getUser()->getUsername()))
						->setIncludeHistory(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($giftvoucherIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    public function historygridAction(){
    	$this->getResponse()->setBody(
			$this->getLayout()->createBlock('giftvoucher/adminhtml_giftvoucher_edit_tab_history')->setGiftvoucher($this->getRequest()->getParam('id'))->toHtml()
		);
    }
    
    public function massEmailAction(){
    	$giftvoucherIds = $this->getRequest()->getParam('giftvoucher');
        if(!is_array($giftvoucherIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Gift Voucher(s)'));
        }else {
            try {
            	$totalEmailSent = 0;
                foreach ($giftvoucherIds as $giftvoucherId) {
                    $giftvoucher = Mage::getModel('giftvoucher/giftvoucher')->load($giftvoucherId);
                    $emailSent = (int)$giftvoucher->sendEmail()->getEmailSent();
                    if ($emailSent) $totalEmailSent += $emailSent;
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d Gift Voucher with %d email(s) were successfully sent.', count($giftvoucherIds),$totalEmailSent
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}
