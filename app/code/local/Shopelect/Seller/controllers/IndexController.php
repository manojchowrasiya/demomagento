<?php
class Shopelect_Seller_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Seller"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("seller", array(
                "label" => $this->__("Seller"),
                "title" => $this->__("Seller")
		   ));

      $this->renderLayout(); 
	  
    }

    public function testAction() {
        $this->loadLayout();   
    $this->getLayout()->getBlock("head")->setTitle($this->__("Magento"));
          $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
       ));

      $breadcrumbs->addCrumb("seller", array(
                "label" => $this->__("Magento"),
                "title" => $this->__("Magento")
       ));

      $this->renderLayout(); 
    

    }

    public function loginsellerAction()
    {
      $email ='manoj.chowrasiya@iksula.com';
      $password = '123456';
      Mage::helper('seller/data')->loginUser($email,$password);
    }
}