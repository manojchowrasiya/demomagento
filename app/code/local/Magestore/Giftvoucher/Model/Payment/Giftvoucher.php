<?php

class Magestore_Giftvoucher_Model_Payment_Giftvoucher extends Mage_Payment_Model_Method_Abstract
{
	protected $_code  = 'giftvoucher';
	protected $_formBlockType = 'giftvoucher/payment_form';
	
	protected $_canUseInternal = false;
	
    public function isAvailable($quote = null)
    {
		//if(!Mage::helper('maegenotification')->checkLicenseKey('Giftvoucher')){
		//	return false;
		//}
		
		return parent::isAvailable($quote);
	}	
}