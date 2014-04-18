<?php
class Shopelect_Seller_AccountController extends Mage_Core_Controller_Front_Action{

  protected function _construct()
  {
    parent::_construct();
    // $this->setTemplate('stenik/qaforum/forum.phtml');
    // echo 'manjo';exit();
    // $login = Mage::getSingleton( 'customer/session' )->isLoggedIn();
    // $login = Mage::getSingleton( 'customer/session' )->isLoggedIn(); 
    //     if(!$login)
    //     {
    //         $group_id = Mage::getSingleton('customer/session')->getCustomerGroupId(); //Get Customers Group ID
    //         $this->_redirect("seller/");
    //     }
     

  }
    public function IndexAction() {    
      $login = Mage::getSingleton( 'customer/session' )->isLoggedIn(); 
        if(!$login)
        {
            $group_id = Mage::getSingleton('customer/session')->getCustomerGroupId(); //Get Customers Group ID
            $this->_redirect("seller/");
        }else{
            $this->loadLayout();   
            $this->renderLayout(); 
        }

	  
    }

    public function ordersAction(){
       $this->loadLayout();  
       $this->renderLayout();  
    } 

    public function auctionsAction(){
       $this->loadLayout();  
       $this->renderLayout();  
    } 

    public function excessAction(){
       $this->loadLayout();  
       $this->renderLayout();  
    } 

    public function ratecontractAction(){
       $this->loadLayout();  
       $this->renderLayout();  
    }
}