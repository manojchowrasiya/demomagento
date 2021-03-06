<?php
class Shopelect_Seller_Helper_Data extends Mage_Core_Helper_Abstract
{	

	public function getCustomPrice($product)
	{
		// $price = 100;
		// Mage::getModel('cata')
		// echo '<pre>';
		// print_r($product->getData());
		$login = Mage::getSingleton( 'customer/session' )->isLoggedIn(); //Check if User is Logged In
        if($login)
        {
             $group_id = Mage::getSingleton('customer/session')->getCustomerGroupId(); //Get Customers Group ID
        }else{
             $group_id = 0;
        }
        $sellerDiscount = Mage::getModel('seller/groupdiscount')->getCollection()->addFieldToFilter('group_id',$group_id); 
        $sellerDiscount_array = $sellerDiscount->getData();
        $discountPercentage = $sellerDiscount_array[0]['discount'];
        $matchedPrice = $original_price =  $product->getPrice();
        $specialproductPrice = $product->getSpecialPrice();
        $matchedPrice = ((100-$discountPercentage)*$matchedPrice)/100;
        if(isset($specialproductPrice)){
            $specialPrice = $product->getSpecialPrice();
            $matchedPrice = ((100-$discountPercentage)*$specialPrice)/100;
        }
        	
        $main_html = '<div class="price-box">
				        <span class="regular-price" id="product-price-16">
				        <span class="price">'.Mage::helper('core')->currency($matchedPrice).'</span></span>
        			 </div>';
        if($specialproductPrice){
	        $main_html = '<div class="price-box">	                                
	        		            <p class="old-price">
	        		                <span class="price-label">Regular Price:</span>
	        		                <span class="price" id="old-price-20">'.Mage::helper('core')->currency($original_price).'</span>
	        		            </p>
	        	
	        		            <p class="special-price">
	        		                <span class="price-label">Special Price:</span>
	        		                <span class="price" id="product-price-20">'.Mage::helper('core')->currency($matchedPrice).'</span>
	        		            </p>                
	        		    
	        		        </div>';
        }
		return $main_html;
		
	}

	public function getSellerDashboardUrl()
    {
        return Mage::getUrl('seller/account');
    }

    public function getSellerAccountUrl()
    {
        return Mage::getUrl('seller/account');
    }

    function IscustomerEmailExists($email, $websiteId = null){
        // echo 'mannnnnnn';
        $websiteId = Mage::app()->getWebsite()->getId();
        $customer = Mage::getModel('customer/customer');
        if ($websiteId) {
            $customer->setWebsiteId($websiteId);
        }
        $customer->loadByEmail($email);
        if ($customer->getId()) {
            return $customer->getId();
        }
        return false;
    }

    function loginUser(){
        $email = $this->getRequest()->getParam('email');
        $password = $this->getRequest()->getParam('password');
        $websiteId = Mage::app()->getWebsite()->getId();
        $cust_exist = $this->IscustomerEmailExists($email,$websiteId);

        if($cust_exist){
            Mage::getSingleton("core/session", array("name" => "frontend"));
            $websiteId = Mage::app()->getWebsite()->getId();
            $store = Mage::app()->getStore();
            $customer = Mage::getModel("customer/customer");
            $customer->website_id = $websiteId;
            $customer->setStore($store);
            try {
                $customer->loadByEmail($email);
                $session = Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
                $session->login($email, $password);
                $url =$this->getSellerAccountUrl();
                echo 'success';
            }
            catch(Exception $e){
            }
        }else{
            echo 'failure';
        }
        

  } 



}
	 