<?php
class Magestore_Giftvoucher_CheckoutController extends Mage_Core_Controller_Front_Action
{
    public function removegiftAction(){
    	$session = Mage::getSingleton('checkout/session');
    	$code = trim($this->getRequest()->getParam('code'));
    	$codes = $session->getGiftCodes();
    	
    	$success = false;
    	if ($code && $codes){
    		$codesArray = explode(',',$codes);
    		foreach ($codesArray as $key => $value)
    			if ($value == $code){
    				unset($codesArray[$key]);
    				$success = true;
    				break;
   				}
    	}
    	
    	if ($success){
    		$codes = implode(',',$codesArray);
    		$session->setGiftCodes($codes);
    		$session->addSuccess($this->__('Gift Voucher "%s" has been removed from your order!',Mage::helper('giftvoucher')->getHiddenCode($code)));
    	}else{
    		$session->addError($this->__('Gift Voucher not found in your order!'));
   		}
    	$this->_redirect('checkout/cart');
    }
    
    public function addgiftAction(){
    	$code = trim($this->getRequest()->getParam('giftvouchercode'));
    	$result = array();
    	$max = Mage::helper('giftvoucher')->getGeneralConfig('maximum');
    	$codes = Mage::getSingleton('giftvoucher/session')->getCodes();
    	
    	if (!$code){
    		$errorMessage = Mage::helper('giftvoucher')->__('Invalid voucher code. ');
    		if ($max)
    			$errorMessage .= Mage::helper('giftvoucher')->__('You have %d times for checking voucher code.',$max);
    		$result['error'] = $errorMessage;
    		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    		return;
    	}
    	$giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);
    	
    	if (!$giftVoucher->getId()){
			$codes[] = $code;
			$codes = array_unique($codes);
			Mage::getSingleton('giftvoucher/session')->setCodes($codes);
    	}
    	
    	if (!Mage::helper('giftvoucher')->isAvailableToAddCode()){
    		$giftVoucherBlock = $this->getLayout()->createBlock('giftvoucher/payment_form');
			$result['html']	= $giftVoucherBlock->toHtml();
    		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
			return;
    	}
    	
		if ($giftVoucher->getId() && $giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE && $giftVoucher->getBaseBalance() > 0){
			$session = Mage::getSingleton('checkout/session');
			$giftVoucher->addToSession($session);
			$session->getQuote()->collectTotals()->save();
			$result['success'] = Mage::helper('giftvoucher')->__('Gift Voucher "%s" was applied to your order.',Mage::helper('giftvoucher')->getHiddenCode($giftVoucher->getGiftCode()));
    		$giftVoucherBlock = $this->getLayout()->createBlock('giftvoucher/payment_form');
			$result['html']	= $giftVoucherBlock->toHtml();
		}else{
			$errorMessage = Mage::helper('giftvoucher')->__('Invalid voucher code. ');
			if ($max)
				$errorMessage .= Mage::helper('giftvoucher')->__('You have %d times remain for checking voucher code.',$max-count($codes));
			$result['error'] = $errorMessage;
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
    
    public function removeAction(){
    	$session = Mage::getSingleton('checkout/session');
    	$code = trim($this->getRequest()->getParam('giftvouchercode'));
    	$codes = $session->getGiftCodes();
    	
    	$success = false;
    	if ($code && $codes){
    		$codesArray = explode(',',$codes);
    		foreach ($codesArray as $key => $value)
    			if ($value == $code){
    				unset($codesArray[$key]);
    				$success = true;
    				break;
   				}
    	}
    	
    	$result = array();
    	if ($success){
    		$codes = implode(',',$codesArray);
    		$session->setGiftCodes($codes);
			$session->getQuote()->collectTotals()->save();
			$result['success'] = Mage::helper('giftvoucher')->__('Gift Voucher "%s" has been removed from your order.',Mage::helper('giftvoucher')->getHiddenCode($code));
    		$giftVoucherBlock = $this->getLayout()->createBlock('giftvoucher/payment_form');
			$result['html']	= $giftVoucherBlock->toHtml();
    	}else{
    		$result['error'] = Mage::helper('giftvoucher')->__('Gift Voucher "%s" is not found.',$code);
   		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
