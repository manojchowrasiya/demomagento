<?php
class Magestore_Giftvoucher_Block_Payment_Form extends Mage_Payment_Block_Form
{
	protected function _construct(){
		parent::_construct();
		$this->setTemplate('giftvoucher/payment/form.phtml');
	}
	
	public function getDescription(){
		return Mage::getStoreConfig('payment/giftvoucher/description');
	}
	
	public function isPassed(){
		return (Mage::app()->getStore()->roundPrice($this->getGrandTotal()) == 0);
	}
	
	public function getGiftVoucherDiscount(){
		$session = Mage::getSingleton('checkout/session');
		$discounts = array();
		if ($codes = $session->getGiftCodes()){
			$codesArray = explode(',',$codes);
			$codesDiscountArray = explode(',',$session->getCodesDiscount());
			$discounts = array_combine($codesArray,$codesDiscountArray);
		}
		return $discounts;
	}
	
	public function getGrandTotal(){
		if (!$this->hasData('grand_total')){
			$quote = Mage::getSingleton('checkout/session')->getQuote()->collectTotals();
			$grandTotal = $quote->getGrandTotal();
			/*
			$addresses = Mage::getSingleton('checkout/session')->getQuote()->getAllShippingAddresses();
			$grandTotal = 0;
			foreach ($addresses as $address)
				$grandTotal += $address->getGrandTotal();
			*/
			$this->setData('grand_total',$grandTotal);
		}
		return $this->getData('grand_total');
	}
	
	public function getAddGiftVoucherUrl(){
		return trim($this->getUrl('giftvoucher/checkout/addgift'),'/');
	}
}
