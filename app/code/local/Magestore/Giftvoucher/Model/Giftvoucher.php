<?php

class Magestore_Giftvoucher_Model_Giftvoucher extends Mage_Core_Model_Abstract
{
    public function _construct(){
        parent::_construct();
        $this->_init('giftvoucher/giftvoucher');
    }
    
    public function loadByCode($code){
    	return $this->load($code,'gift_code');
    }
    
    public function load($id, $field=null){
    	parent::load($id,$field);
    	if ($this->getIsDeleted()){
    		return Mage::getModel('giftvoucher/giftvoucher');
   		}
   		if ($this->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE
   			&& $this->getExpiredAt()
			&& $this->getExpiredAt() < now())
			$this->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_EXPIRED);
    	return $this;
    }
    
    public function getIsDeleted(){
    	if (!$this->hasData('is_deleted')){
    		$this->setData('is_deleted',$this->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_DELETED);
    	}
    	return $this->getData('is_deleted');
    }
    
    public function getCollection(){
    	return parent::getCollection()->getAvailable();
    }
    
    public function getBaseBalance($storeId = null){
    	if (!$this->hasData('base_balance')){
    		$baseBalance = 0;
    		if ($rate = Mage::app()->getStore($storeId)->getBaseCurrency()->getRate($this->getData('currency')))
    			$baseBalance = $this->getBalance() / $rate;
    		$this->setData('base_balance',$baseBalance);
    	}
    	return $this->getData('base_balance');
    }
    
    protected function _beforeSave(){
    	if (!$this->getId()){
    		$this->setAction(Magestore_Giftvoucher_Model_Actions::ACTIONS_CREATE);
    	}
    	if ($this->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE
			&& Mage::app()->getStore()->roundPrice($this->getBalance()) == 0)
			$this->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_USED);
			
			
		if (!$this->getGiftCode())
			$this->setGiftCode(Mage::helper('giftvoucher')->getGeneralConfig('pattern'));
		if ($this->_codeIsExpression())
			$this->setGiftCode($this->_getGiftCode());
			
		###### code modified to generate random and unique card no and pin code ########
		
		if (!$this->getGiftCardno())
			$this->setGiftCardno(Mage::helper('giftvoucher')->getGeneralConfig('cardnopattern'));
		if (Mage::helper('giftvoucher')->isExpression($this->getGiftCardno()))
			$this->setGiftCardno($this->_getGiftCardno());
			
			
		if (!$this->getGiftPincode())
			$this->setGiftPincode(Mage::helper('giftvoucher')->getGeneralConfig('pincodepattern'));
		if (Mage::helper('giftvoucher')->isExpression($this->getGiftPincode()))
			$this->setGiftPincode($this->_getGiftPincode());
					
				
		if (!$this->getStatus())
			$this->setStatus(1);
		
		###### end ######
		
		//echo "<pre>";
		//print_r($this->getData());
		
		//die();
			
    	return parent::_beforeSave();
    }
    
    protected function _codeIsExpression(){
    	return Mage::helper('giftvoucher')->isExpression($this->getGiftCode());
    }
	
	
	###### code added to uniquly create card no and pin code #####
	protected function _getGiftCardno(){
    	$cardno = Mage::helper('giftvoucher')->calcCode($this->getGiftCardno());
    	$times = 10;
    	while(Mage::getModel('giftvoucher/giftvoucher')->load($cardno,'gift_cardno')->getId() && $times){
    		$cardno = Mage::helper('giftvoucher')->calcCode($this->getGiftCardno());
    		$times--;
    		if ($times == 0){
    			throw new Mage_Core_Exception('Exceeded maximum retries to find available random gift card no!');
    		}
    	}
    	return $cardno;
    }
	
	protected function _getGiftPincode(){
    	$pincode = Mage::helper('giftvoucher')->calcCode($this->getGiftPincode());
    	$times = 10;
    	while(Mage::getModel('giftvoucher/giftvoucher')->load($pincode, 'gift_pincode')->getId() && $times){
    		$pincode = Mage::helper('giftvoucher')->calcCode($this->getGiftPincode());
    		$times--;
    		if ($times == 0){
    			throw new Mage_Core_Exception('Exceeded maximum retries to find available random pin code!');
    		}
    	}
    	return $pincode;
    }
	
	#####




    
    protected function _getGiftCode(){
    	$code = Mage::helper('giftvoucher')->calcCode($this->getGiftCode());
    	$times = 10;
    	while(Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code)->getId() && $times){
    		$code = Mage::helper('giftvoucher')->calcCode($this->getGiftCode());
    		$times--;
    		if ($times == 0){
    			throw new Mage_Core_Exception('Exceeded maximum retries to find available random gift voucher code!');
    		}
    	}
    	return $code;
    }
    
    protected function _afterSave(){
    	if ($this->getIncludeHistory() && $this->getAction()){
    		$history = Mage::getModel('giftvoucher/history')
				->setData($this->getData())
				->setData('created_at',now());
			try{
				$history->save();
			}catch(Exception $e){}
    	}
    	return parent::_afterSave();
    }
    
    public function delete(){
    	$this->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_DELETED)
    		->save();
   		return $this;
    }
	
	public function getFormatedMessage()
	{
		return str_replace("\n","<br/>",$this->getMessage());
	}
    
    public function addToSession($session=null){
    	if (is_null($session)){
    		$session = Mage::getSingleton('checkout/session');
    	}
    	if ($codes = $session->getGiftCodes()){
    		$codesArray = explode(',',$codes);
    		$codesArray[] = $this->getGiftCode();
    		$codes = implode(',',array_unique($codesArray));
    	}else{
    		$codes = $this->getGiftCode();
   		}
    	$session->setGiftCodes($codes);
    	return $this;
    }
    
    public function sendEmail(){
    	$store = Mage::app()->getStore($this->getStoreId());
    	$translate = Mage::getSingleton('core/translate');
		$translate->setTranslateInline(false);
    	$mailSent = 0;
    	
    	if ($this->getCustomerEmail()){
	    	$mailTemplate = Mage::getModel('core/email_template')
				->setDesignConfig(array(
					'area'	=> 'frontend',
					'store'	=> $store->getStoreId()
				))
				->sendTransactional(
					Mage::helper('giftvoucher')->getEmailConfig('self',$store->getStoreId()),
					Mage::helper('giftvoucher')->getEmailConfig('sender',$store->getStoreId()),
					$this->getCustomerEmail(),
					$this->getCustomerName(),
					array(
						'store'		=> $store,
						'sendername'	=> $this->getCustomerName(),
						'name'		=> $this->getRecipientName(),
						'code'		=> $this->getGiftCode(),
						'balance'	=> $this->getBalanceFormated(),
						'status'	=> $this->getStatusLabel(),
						'expiredat'	=> $this->getExpiredAt() ? Mage::getModel('core/date')->date('M d, Y',$this->getExpiredAt()) : '',
						'message'	=> $this->getFormatedMessage(),
					)
				);
			$mailSent++;
		}
		
		if ($this->getRecipientName()){
			$mailTemplate = Mage::getModel('core/email_template')
				->setDesignConfig(array(
					'area'	=> 'frontend',
					'store'	=> $store->getStoreId()
				))
				->sendTransactional(
					Mage::helper('giftvoucher')->getEmailConfig('template',$store->getStoreId()),
					Mage::helper('giftvoucher')->getEmailConfig('sender',$store->getStoreId()),
					$this->getRecipientEmail(),
					$this->getRecipientName(),
					array(
						'store'		=> $store,
						'sendername'	=> $this->getCustomerName(),
						'name'		=> $this->getRecipientName(),
						'code'		=> $this->getGiftCode(),
						'balance'	=> $this->getBalanceFormated(),
						'status'	=> $this->getStatusLabel(),
						'expiredat'	=> $this->getExpiredAt() ? Mage::getModel('core/date')->date('M d, Y',$this->getExpiredAt()) : '' ,
						'message'	=> $this->getFormatedMessage(),
					)
				);
			$mailSent++;
    	}
    	
    	$this->setEmailSent($mailSent);
    	$translate->setTranslateInline(true);
    	return $this;
    }
    
    public function getBalanceFormated(){
    	$currency = Mage::getModel('directory/currency')->load($this->getCurrency());
    	return $currency->format($this->getBalance());
    }
    
    public function getStatusLabel(){
    	$statusArray = Mage::getSingleton('giftvoucher/status')->getOptionArray();
    	return $statusArray[$this->getStatus()];
    }
}