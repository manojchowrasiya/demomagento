<?php

class Magestore_Giftvoucher_Model_Total_Quote_Giftvoucher extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
	public function __construct(){
		$this->setCode('giftvoucher');
	}
	
	public function collect(Mage_Sales_Model_Quote_Address $address){

		//return $this;
		$quote = $address->getQuote();
		$session = Mage::getSingleton('checkout/session');

		//$quote->setTotalsCollectedFlag(true)->collectTotals();

		
		if ($address->getAddressType() == 'billing' && !$quote->isVirtual()) return $this;
		
		if ($codes = $session->getGiftCodes()){
			$codesArray = array_unique(explode(',',$codes));
			$store = $quote->getStore();
			
			if ($address->getAllBaseTotalAmounts()) {
				 $baseTotal = array_sum($address->getAllBaseTotalAmounts());

				
			}
			else{
				$baseTotal = $address->getBaseGrandTotal();

			}

			/// hack for store credit and reward point ///
			
 			if($reward_point=$quote->getRewardpointDiscount())
			{
				$baseTotal= $baseTotal - $reward_point;
				
			}
			if($credit=$session->getCredit())
			{
				$baseTotal= $baseTotal - $credit;
				
			}
						

			/// end ///

			

			
			$baseTotalDiscount = 0;
			$totalDiscount = 0;
			
			$codesBaseDiscount = array();
			$codesDiscount = array();
				
			$baseSessionAmountUsed = explode(',',$session->getBaseAmountUsed());
			$baseAmountUsed = array_combine($codesArray,$baseSessionAmountUsed);
			$amountUsed = $baseAmountUsed;

			
			
			foreach ($codesArray as $code){
				$model = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);
				
				if ($baseTotal == 0
					|| $model->getStatus() != Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE
					|| $model->getBalance() == 0
					|| $model->getBaseBalance() <= $baseAmountUsed[$code]
					){
					$codesBaseDiscount[] = 0;
					$codesDiscount[] = 0;
				}else{
					$baseBalance = $model->getBaseBalance() - $baseAmountUsed[$code];
					$baseDiscount = min($baseBalance,$baseTotal);
					$discount = $store->convertPrice($baseDiscount);
					
					$baseAmountUsed[$code] += $baseDiscount;
					$amountUsed[$code] = $store->convertPrice($baseAmountUsed[$code]);
					
					$baseTotal -= $baseDiscount;
					
					$baseTotalDiscount += $baseDiscount;
					$totalDiscount += $discount;
					
					$codesBaseDiscount[] = $baseDiscount;
					$codesDiscount[] = $discount;
				}
			}
			
 			
			Mage::log(get_class($this)."---".print_r($quote->getData() , true), null, 'total.log');
			
			$codesBaseDiscountString = implode(',',$codesBaseDiscount);
			$codesDiscountString = implode(',',$codesDiscount);
			
			//update session
			$session->setBaseAmountUsed(implode(',',$baseAmountUsed));
			
			$session->setBaseGiftVoucherDiscount($session->getBaseGiftVoucherDiscount()+$baseTotalDiscount);
			$session->setGiftVoucherDiscount($session->getGiftVoucherDiscount()+$totalDiscount);
			
			$session->setCodesBaseDiscount($session->getBaseAmountUsed());
			$session->setCodesDiscount(implode(',',$amountUsed));
			
			//update address  
			$address->setBaseGrandTotal($baseTotal);
			$address->setGrandTotal($store->convertPrice($baseTotal));
			
			
			$address->setBaseGiftVoucherDiscount($baseTotalDiscount);
			$address->setGiftVoucherDiscount($totalDiscount);
			
			$address->setGiftCodes($codes);
			$address->setCodesBaseDiscount($codesBaseDiscountString);
			$address->setCodesDiscount($codesDiscountString);
			
			//update quote
			$quote->setBaseGiftVoucherDiscount($session->getBaseGiftVoucherDiscount());
			$quote->setGiftVoucherDiscount($session->getGiftVoucherDiscount());
			
			$quote->setGiftCodes($codes);
			$quote->setCodesBaseDiscount($session->getCodesBaseDiscount());
			$quote->setCodesDiscount($session->getCodesDiscount());
		}
		//Mage::log(get_class($this)."---".print_r($address->getData() , true), null, 'total.log');
		return $this;
	}
	
	public function fetch(Mage_Sales_Model_Quote_Address $address){
		if ( $giftVoucherDiscount = $address->getGiftVoucherDiscount()){
			$address->addTotal(array(
				'code'			=> $this->getCode(),
				'title'			=> Mage::helper('giftvoucher')->__('Gift Voucher'),
				'value'			=> -$giftVoucherDiscount,
				'gift_codes'		=> $address->getGiftCodes(),
				//'codes_base_discount'	=> $address->getCodesBaseDiscount(),
				//'codes_discount'	=> $address->getCodesDiscount()
			));
		}
		return $this;
	}
}
