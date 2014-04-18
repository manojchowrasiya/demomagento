<?php
class Shopelect_Seller_Model_Observer
{

	public function sellerRegistration(Varien_Event_Observer $observer) {
		echo 'manoj';exit;

		 try {

            $customer = $observer->getEvent()->getCustomer();

            $customer->setData('group_id', 2); // or whatever the group id should be
            $customer->save();

        } catch ( Exception $e ) {}
	}
		
}
