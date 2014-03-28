<?php

class Magestore_Giftvoucher_Model_Total_Order_Creditmemo_Giftvoucher extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo){
		$order = $creditmemo->getOrder();
		if ($order->getPayment()->getMethod() == 'giftvoucher')
			return $this;
		if ($order->getBaseGiftVoucherDiscount() && $order->getGiftVoucherDiscount()){
			$baseDiscount = $order->getBaseGiftVoucherDiscount();
			$discount = $order->getGiftVoucherDiscount();
			$creditmemo->setGiftCodes($order->getGiftCodes());
			if ($creditmemo->getBaseGrandTotal() - $baseDiscount < 0){
				$creditmemo->setBaseGiftVoucherDiscount($creditmemo->getBaseGrandTotal());
				$creditmemo->setGiftVoucherDiscount($creditmemo->getGrandTotal());
				$creditmemo->setBaseGrandTotal(0);
				$creditmemo->setGrandTotal(0);
			}else{
				$creditmemo->setBaseGiftVoucherDiscount($baseDiscount);
				$creditmemo->setGiftVoucherDiscount($discount);
				$creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal()-$baseDiscount);
				$creditmemo->setGrandTotal($creditmemo->getGrandTotal()-$discount);
			}
		}
	}
}