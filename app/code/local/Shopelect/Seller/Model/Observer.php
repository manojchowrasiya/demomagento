<?php
class Shopelect_Seller_Model_Observer
{

			public function sellerRegistration(Varien_Event_Observer $observer)
			{
				//Mage::dispatchEvent('admin_session_user_login_success', array('user'=>$user));
				//$user = $observer->getEvent()->getUser();
				//$user->doSomething();
				// echo 'hfhffhfhhfhf';exit();
				try {
					$customer = $observer->getEvent()->getCustomer();
			        $customer->setData('group_id', 2); // or whatever the group id should be
			        $customer->save();

			        } catch ( Exception $e ) {}
			}

			public function sellerLoginCheck(Varien_Event_Observer $observer)
			{
					// $login = Mage::getSingleton( 'customer/session' )->isLoggedIn(); 
					// if($login)
					// {
					// 	$group_id = Mage::getSingleton('customer/session')->getCustomerGroupId(); //Get Customers Group ID
					// 	if($group_id==2){
					// 		$this->_redirect("seller/account");
					// 	}
					// }
			}
		
}
