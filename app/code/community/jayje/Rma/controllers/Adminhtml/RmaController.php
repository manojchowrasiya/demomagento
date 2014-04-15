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


class Jayje_Rma_Adminhtml_RmaController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
		->_setActiveMenu('rma/items')
		->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function infoAction() {
                       $this->getResponse()->setBody( 
                       $this->getLayout()->createBlock('rma/adminhtml_rma_info')->toHtml());
	}
             
	public function orderAction() {
                      $this->getResponse()->setBody($this->getLayout()->createBlock('rma/adminhtml_rma_edit_tab_order')->toHtml());
	}
             
	public function commentsAction() {
                      $this->getResponse()->setBody($this->getLayout()->createBlock('rma/adminhtml_rma_edit_tab_comments')->toHtml());
	}
             
             public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('rma/rma')->load($id);
		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('rma_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('rma/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('rma/adminhtml_rma_edit'))
				->_addLeft($this->getLayout()->createBlock('rma/adminhtml_rma_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
	   if ($data = $this->getRequest()->getPost()) {
	        
                 	 $rmaId  = (int) $this->getRequest()->getParam('id');
		$rma	= Mage::getsingleton('rma/rma');
		if ($rmaId) {
			 $rma->load($rmaId) ; 
		}
            $laststatus = $rma->getAdminstatus();	   
	  $rma->setRmaflow('');
	  $rma->setUpdateTime('');
	   $rma->setData($data)
                  ->setId($rmaId);
                 
                  $newstatus = $data['adminstatus'];
                	
           //  print_r($data);  print_r($rma);  exit;
                  try {
                           if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                                    $rma->setCreatedTime(now())
                                    ->setUpdateTime(now());
                           } else {
                                    $rma->setUpdateTime(now());
                           }
                                
                  $rma->save();                  
                  
                if($rma->getId() !=''){
                    $rmaid = $rma->getId();
                    $update_time = date("Y-m-d g:i:s");
                    $comments = $data['comments'];
                    $admin = Mage::getSingleton('admin/session')->getUser();
                    $by = $admin['username'];
                    if($comments != ''){
                           $model1 = Mage::getModel('rma/rcomments');             
                           $model1->setRmaid($rmaid);        
                           $model1->setStype('Comments');        
                           $model1->setComments($comments);        
                           $model1->setDate($update_time);        
                           $model1->setBy($by);        
                           $model1->save();        

                    }
                  if($newstatus != $laststatus){
                           $model2 = Mage::getModel('rma/rcomments');             
                           $model2->setRmaid($rmaid);        
                           $model2->setStype('Status');        
                           $model2->setComments($newstatus);        
                           $model2->setDate($update_time);        
                           $model2->setBy($by);        
                           $model2->save();        
                  } 
                }            

                
                
                
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('rma')->__('RMA was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $rma->getId()));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('rma')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$rmaId = $this->getRequest()->getParam('id');
				$model = Mage::getModel('rma/rma');
			  	$model->setId($rmaId)->delete();
                                    
                                    $rpid = Mage::getModel('rma/rproducts')->getRpidByRmaid($rmaId);
                                	$model = Mage::getModel('rma/rproducts');             
			 	$model->setId($rpid)->delete();    
                                    
                                    $rcids = Mage::getModel('rma/rcomments')->getRcidByRmaid($rmaId);
                                   
                                    foreach($rcids as $rcid){
                                      $model = Mage::getModel('rma/rcomments');             
				  $model->setId($rcid)->delete();           
                                    }
                                	
                                    					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $rmaIds = $this->getRequest()->getParam('rma');
        if(!is_array($rmaIds)) {
                  Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($rmaIds as $rmaId) {
                    $rma = Mage::getModel('rma/rma')->load($rmaId);
                    $rma->delete();
                    $rpid = Mage::getModel('rma/rproducts')->getRpidByRmaid($rmaId);
                	  $model = Mage::getModel('rma/rproducts')->load($rpid)->delete();   
                    
                    $rcids = Mage::getModel('rma/rcomments')->getRcidByRmaid($rmaId);
                    foreach($rcids as $rcid){
                     $model = Mage::getModel('rma/rcomments')->load($rcid)->delete();   
                    }

                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($rmaIds)
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
        $rmaIds = $this->getRequest()->getParam('rma');
        if(!is_array($rmaIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($rmaIds as $rmaId) {
                    $rma = Mage::getSingleton('rma/rma')
                        ->load($rmaId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($rmaIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    	public function exportCsvAction(){
		$fileName   = 'rma_request.csv';
		$content	= $this->getLayout()->createBlock('rma/adminhtml_rma_grid')->getCsv();
		$this->_prepareDownloadResponse($fileName, $content);
	}

	public function exportExcelAction(){
		$fileName   = 'rma_request.xls';
		$content	= $this->getLayout()->createBlock('rma/adminhtml_rma_grid')->getExcelFile();
		$this->_prepareDownloadResponse($fileName, $content);
	}

	public function exportXmlAction(){
		$fileName   = 'rma_request.xml';
		$content	= $this->getLayout()->createBlock('rma/adminhtml_rma_grid')->getXml();
		$this->_prepareDownloadResponse($fileName, $content);
	}

}