<?php

class Magestore_Giftvoucher_Model_Total_Order_Invoice_Giftvoucher extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
	public function collect(Mage_Sales_Model_Order_Invoice $invoice){
		$order = $invoice->getOrder();
		if ($order->getPayment()->getMethod() == 'giftvoucher')
			return $this;
		if ($order->getBaseGiftVoucherDiscount() && $order->getGiftVoucherDiscount()){
			$baseDiscount = $order->getBaseGiftVoucherDiscount();
			$discount = $order->getGiftVoucherDiscount();
			$invoice->setGiftCodes($order->getGiftCodes());
			if ($invoice->getBaseGrandTotal() - $baseDiscount < 0){
				$invoice->setBaseGiftVoucherDiscount($invoice->getBaseGrandTotal());
				$invoice->setGiftVoucherDiscount($invoice->getGrandTotal());
				$invoice->setBaseGrandTotal(0);
				$invoice->setGrandTotal(0);
			}else{
				$invoice->setBaseGiftVoucherDiscount($baseDiscount);
				$invoice->setGiftVoucherDiscount($discount);
				$invoice->setBaseGrandTotal($invoice->getBaseGrandTotal()-$baseDiscount);
				$invoice->setGrandTotal($invoice->getGrandTotal()-$discount);
			}
		}
	}
}