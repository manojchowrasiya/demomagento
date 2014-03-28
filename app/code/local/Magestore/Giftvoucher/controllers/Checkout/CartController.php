<?php
class Magestore_Giftvoucher_Checkout_CartController extends Mage_Core_Controller_Front_Action
{
	
    private function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }
    
    private function _goBack()
    {
    	return $this->_redirect('checkout/cart');
    }
	public function giftvoucherPostAjaxAction()
    	{

	 $response = array(
            'success' => false,
            'error'=> false,
            'message' => false,
        );

    	// no login


	try {

        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
        			
			
		   throw new Mage_Core_Exception($this->__('You must login to use this function'));		
			
    	}

	
    	

        $giftvouchercode = $this->getRequest()->getParam('giftvouchercode');

	$remove = $this->getRequest()->getParam('remove');
	$giftvouchercode = trim($giftvouchercode);

	if($remove==1)
	{
		$session = Mage::getSingleton('checkout/session');
		$codes = $session->getGiftCodes();
		if ($giftvouchercode && $codes)
		{
    			$codesArray = explode(',',$codes);
    			foreach ($codesArray as $key => $value)
			{
    				if ($value == $giftvouchercode)
				{
    				unset($codesArray[$key]);
    				$success = true;
    				break;
   				}
			}
		}
		    	
		    	if ($success){
		    		$codes = implode(',',$codesArray);
		    		$session->setGiftCodes($codes);

				$response['success'] = true;
                 		$response['message'] = $this->__(Mage::helper('giftvoucher')->__('Gift Voucher "%s" has been removed from your order!',Mage::helper('giftvoucher')->getHiddenCode($giftvouchercode)));
				//$response['message'] = $this->__(Mage::helper('giftvoucher')->__('The maximum number of times to enter Gift Voucher code is %d!',Mage::helper('giftvoucher')->getGeneralConfig('maximum')));		

		    		
		    	}else{
				
                    		throw new  Mage_Core_Exception($this->__('Gift Voucher not found in your order!'));
		    		//$session->addError($this->__('Gift Voucher not found in your order!'));
	     }	
	}
	else
	{

		

		$giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($giftvouchercode);

		$today = strtotime(date("Y-m-d H:i:s")); 
		$expiration_date = strtotime( $giftVoucher->getExpiredAt());

		
        	if (!Mage::helper('giftvoucher')->isAvailableToAddCode()){
			
			
		    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $this->__(Mage::helper('giftvoucher')->__('The maximum number of times to enter Gift Voucher code is %d!',Mage::helper('giftvoucher')->getGeneralConfig('maximum')));		
        		
        	
        }	
	elseif(!$giftVoucher->getId()){

	throw new Mage_Core_Exception($this->__('Invalid Gift Voucher'));	

	}
	elseif ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_USED) {
                    throw new Mage_Core_Exception('Gift Voucher hasbeen used.');
         } elseif ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_EXPIRED) {
                    throw new Mage_Core_Exception('Gift Voucher hasbeen Expired.');
          } elseif ($giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_DISABLED) {
                    throw new Mage_Core_Exception('Gift Voucher is disabled.Please contact our customer care for more information.');
         } 
	elseif( $giftVoucher->getActivatedCid()!=Mage::getSingleton('customer/session')->getId())
	{
		throw new Mage_Core_Exception($this->__('Gift Voucher code is not valid for your account.'));		
	}  
	elseif( $today >  $expiration_date)
	{
		throw new Mage_Core_Exception($this->__('Gift Voucher expired at .'.$giftVoucher->getExpiredAt()));		
	}         
        // if credit is gretter than credit customer
       elseif ($giftVoucher->getId() && $giftVoucher->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE && $giftVoucher->getBaseBalance() > 0){
			$session = Mage::getSingleton('checkout/session');
			$giftVoucher->addToSession($session);


			
			$response['success'] = true;
                 	$response['message'] = $this->__('Gift voucher "%s" was applied to your order.', Mage::helper('giftvoucher')->getHiddenCode($giftVoucher->getGiftCode()));

			
		
					
        }
            else {
			
			$response['success'] = true;
                	$response['message'] = $this->__('Gift Voucher was canceled successfully.');				
           
        	}

        }
	}
        catch (Mage_Core_Exception $e) {
		
			 $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $e->getMessage();
          //  $this->_getSession()->addError($e->getMessage());
        }
        catch (Exception $e) {
		
			$response['success'] = false;
            $response['error'] = true;
            $response['message'] = $this->__('Can not apply Gift Voucher.');
			
           // $this->_getSession()->addError($this->__('Can not apply credit.'));
        }
		
	$html = $this->getLayout()
        ->createBlock('onestepcheckout/summary')
        ->setTemplate('onestepcheckout/summary.phtml')
        ->toHtml();

        $response['summary'] = $html;


        $this->getResponse()->setBody(Zend_Json::encode($response));

        //$this->_goBack();
    }
	
    public function removeAction(){

	$response = array(
            'success' => false,
            'error'=> false,
            'message' => false,
        );

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
			$response['success'] = true;
			$response['message'] = Mage::helper('giftvoucher')->__('Gift Voucher "%s" has been removed from your order.',Mage::helper('giftvoucher')->getHiddenCode($code));
    			//$giftVoucherBlock = $this->getLayout()->createBlock('giftvoucher/payment_form');
			//$response['html']	= $giftVoucherBlock->toHtml();
    	}else{
		$response['success'] = false;
		$response['error'] = true;
		//$response['message'] = Mage::helper('giftvoucher')->__('Gift Voucher "%s" has been removed from your order.',Mage::helper('giftvoucher')->getHiddenCode($code));
    		$result['message'] = Mage::helper('giftvoucher')->__('Gift Voucher "%s" is not found.',$code);
   		}
		//$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));


	$html = $this->getLayout()
        ->createBlock('onestepcheckout/summary')
        ->setTemplate('onestepcheckout/summary.phtml')
        ->toHtml();

        $response['summary'] = $html;


        $this->getResponse()->setBody(Zend_Json::encode($response));
    }
}
?>
