<?php
class Shopelect_Seller_CreateController extends Mage_Core_Controller_Front_Action{

    public function IndexAction() {    
      // echo 'manloj';
    // exit();  
	   $this->loadLayout();   
	   $this->renderLayout(); 
	  
    }

    public function createAction(){
       $this->loadLayout();  
       $this->renderLayout();  
    }
}