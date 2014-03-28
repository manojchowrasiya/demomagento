<?php

class Magestore_Giftvoucher_Helper_Data extends Mage_Core_Helper_Data
{
	public function getGeneralConfig($code,$store = null){
		return Mage::getStoreConfig('giftvoucher/general/'.$code,$store);
	}
	
	public function getInterfaceConfig($code,$store = null){
		return Mage::getStoreConfig('giftvoucher/interface/'.$code,$store);
	}
	
	public function getEmailConfig($code,$store = null){
		return Mage::getStoreConfig('giftvoucher/email/'.$code,$store);
	}
	
	public function calcCode($expression){
		if ($this->isExpression($expression)){
			return preg_replace_callback('#\[([AN]{1,2})\.([0-9]+)\]#',array($this,'convertExpression'),$expression);
		}else{
			return $expression;
		}
	}
	
	public function convertExpression($param){
		$alphabet  = (strpos($param[1],'A'))===false ? '':'ABCDEFGHIJKLMNPQRSTUVWXYZ';
		$alphabet .= (strpos($param[1],'N'))===false ? '': '123456789';
		return $this->getRandomString($param[2],$alphabet);
	}
	
	public function isExpression($string){
		return preg_match('#\[([AN]{1,2})\.([0-9]+)\]#',$string);
	}
	
	public function getGiftAmount($amountStr){
		$amountStr = trim(str_replace(array(' ', "\r", "\t"), '',$amountStr));
		if ($amountStr == '' || $amountStr == '-'){
			return array('type'=>'any');
		}
		
		$values = explode('-',$amountStr);
		if (count($values) == 2){
			return array('type'=>'range','from'=>$values[0],'to'=>$values[1]);
		}
		
		$values = explode(',',$amountStr);
		if (count($values) > 1){
			return array('type'=>'dropdown','options'=>$values);
		}
		
		$value = floatval($amountStr);
		return array('type'=>'static','value'=>$value);
	}
	
	public function getGiftVoucherOptions(){
		return array(
			'recipient_name'	=> $this->__('Recipient name'),
			'recipient_email'	=> $this->__('Recipient email'),
			'recipient_ship'	=> $this->__('Ship to recipient'),
			'recipient_address'	=> $this->__('Recipient address'),
			'message'			=> $this->__('Custom message'),
		);
	}
	
	public function getFullGiftVoucherOptions(){
		return array(
			'send_friend'		=> $this->__('Send Gift Voucher to friend'),
			'recipient_name'	=> $this->__('Recipient name'),
			'recipient_email'	=> $this->__('Recipient email'),
			'recipient_ship'	=> $this->__('Ship to recipient'),
			'recipient_address'	=> $this->__('Recipient address'),
			'message'			=> $this->__('Custom message'),
		);
	}
	
	public function getHiddenCode($code){
		$prefix = $this->getGeneralConfig('showprefix');
		$prefixCode = substr($code,0,$prefix);
		$suffixCode = substr($code,$prefix);
		if ($suffixCode){
			$hiddenChar = $this->getGeneralConfig('hiddenchar');
			if (!$hiddenChar) $hiddenChar = 'X';
			else $hiddenChar = substr($hiddenChar,0,1);
			$suffixCode = preg_replace('#([A-Z,0-9]{1})#',$hiddenChar,$suffixCode);
		}
		return $prefixCode.$suffixCode;
	}
	
	public function isAvailableToAddCode(){
		$codes = Mage::getSingleton('giftvoucher/session')->getCodes();
		if ($max = Mage::helper('giftvoucher')->getGeneralConfig('maximum'))
			if (count($codes) > $max)
				return false;
		return true;
	}
	public function isGVAvailableInCart(){

		if(Mage::helper('checkout/cart')->getItemsCount() > 0)
		{
			$session= Mage::getSingleton('checkout/session');			
			foreach($session->getQuote()->getAllItems() as $item)
			{
			   
			  	$productId = $item->getProductId();
				$attr_id = Mage::getModel('catalog/product')->load($productId)->getAttributeSetId(); 
				$attributeSetName = Mage::getModel('eav/entity_attribute_set')->load($attr_id)->getAttributeSetName();
			   	if($attributeSetName =='frc_giftvoucher')
				{
				   return true;
				}
			  
			}
			 return false;			
		}
		else
		{
			return false;
		}		
		
	}
	public function isOnlyGVAvailableInCart(){

		if(Mage::helper('checkout/cart')->getItemsCount() > 0)
		{
			$session= Mage::getSingleton('checkout/session');			
			foreach($session->getQuote()->getAllItems() as $item)
			{
			   
			  	$productId = $item->getProductId();
				$attr_id = Mage::getModel('catalog/product')->load($productId)->getAttributeSetId(); 
				$attributeSetName = Mage::getModel('eav/entity_attribute_set')->load($attr_id)->getAttributeSetName();
			   	if($attributeSetName !='frc_giftvoucher')
				{
				   return false;
				}
			  
			}
			 return true;			
		}
		else
		{
			return false;
		}		
		
	}
		public function isGiftVoucherProduct($item)
		{
			   
			  	$productId = $item->getProductId();
				$attr_id = Mage::getModel('catalog/product')->load($productId)->getAttributeSetId(); 
				$attributeSetName = Mage::getModel('eav/entity_attribute_set')->load($attr_id)->getAttributeSetName();
			   	if($attributeSetName =='frc_giftvoucher')
				{
				   return true;
				}
				else
				{			
				   return false;
				}
		
		}
		public function getLastAppliedVoucher()
		{
			$session = Mage::getSingleton('checkout/session');
			$codes = $session->getGiftCodes();
			if ($codes)
			{
	    			$codesArray = explode(',',$codes);
		    		return end($codesArray); 
			}
			return '';
		} 
}
