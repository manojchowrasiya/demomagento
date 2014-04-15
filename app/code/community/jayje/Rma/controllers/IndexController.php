<?php
/**
 * Jayje_Rma extension
 *  
 * @category   	Return Merchant Authorization Magento - wakensys
 * @package	Jayje_Rma
 * @copyright  	Copyright (c) 2013
 * @license	http://opensource.org/licenses/mit-license.php MIT License
 * @category	Jayje
 * @package	Jayje_Rma
 * @author        wakensys
 * @developper   s.ratheepan@gmail.com
 */

class Jayje_Rma_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    { 
         $this->loadLayout();     
          $session = Mage::getSingleton('customer/session');
          if(!$session->isLoggedIn()) {
             $this->_redirect('customer/account/login/');
          }
          $this->renderLayout();
    }
    
    public function requestAction()
    {
        $this->loadLayout();     
        $session = Mage::getSingleton('customer/session');
            if(!$session->isLoggedIn()) {
                $this->_redirect('customer/account/login/');
            }
		$this->renderLayout();
    }
     public function orderAction()
    {
       
        $this->getResponse()->setBody($this->getLayout()->createBlock('rma/ajax')->toHtml());

    }
    
    public function formpostAction(){
        
        $created_time = date("Y-m-d g:i:s");
        $arrParams = $this->getRequest()->getParams();
      print_r($arrParams);  ;
       
         $customername =Mage::helper('customer')->getCustomer()->getName();
        $customerid =Mage::helper('customer')->getCustomer()->getData('entity_id') ;
        $email =Mage::helper('customer')->getCustomer()->getData('email') ;
        $order_id = $arrParams['order_id'];
        if($order_id !=''){
     $maxpid = $arrParams['maxpid'];

      for ($i=0; $i<=$maxpid; $i++){            
         $qty = $arrParams['qty_'.$i];
         if($qty>0){ 
             $reason = $arrParams['reason'];
            $package = $arrParams['package'];
            $return_type = $arrParams['return_type'];
            $increment_id = Mage::getModel('sales/order')->load($order_id)->getData('increment_id');
            $initialstatus = Mage::getStoreConfig('jayje_section/jayje_group/jayje_basicstatus',Mage::app()->getStore());

              $rma = Mage::getsingleton('rma/rma');
         
              $rma->setData($arrParams)
                  ->setAdminstatus($initialstatus)
                  ->setIncrementId($increment_id)
                  ->setEmail($email)
                  ->setCustomerId($customerid)
                  ->setCreatedTime(now()) 
                  ->setUpdateTime(now())
                  ->save();
                  
            $lastInsertId =  $rma->getId(); 

               $pid = $arrParams['pid_'.$i];
               $total = $arrParams['price_'.$i] * $qty;
               $rproducts = Mage::getModel('rma/rproducts')
                  ->setRmaid($lastInsertId) 
                  ->setOrderId($order_id) 
                  ->setProductId($pid) 
                  ->setQty($qty) 
                  ->setTotal($total) 
                  ->save();

           }else{
 //              $message = $this->__('Sorry, We couldnot save your request');
 //              $url = 'rma/index/request';
           }
       }           
            
                      $message = $this->__('Your request has been sent');
               $url = "rma/index/view/rma_id/".$lastInsertId;     
           
        }else{
            $message = $this->__("Sorry, You don't have any Order");
            $url = 'rma/index/request';
        }
      
        $to_email = $email; 
        $to_name  = $customername;
        $subject  = 'RMA Request';
        $Body    .= '<div class="page-title title-buttons"><img src='.Mage::getDesign()->getSkinUrl('images/logo_email.gif').' />
                    <h4>RMA #'. $lastInsertId.' - Pending</h4>
                    </div>Thank you for your RMA Request.
                    <a href="'.Mage::helper('core/url')->getHomeUrl().'/rma/index/view/rma_id/'.$lastInsertId.'"><h4 align="">Check Here</h4></a>
                    ';
 
 $Body .= "<table   style='font-size: small;' width='100%'>
<tr><td colspan='2'>

                <h5>Request Information</h5>
</td></tr>
<tr><td  width='50%'><div class='col2-set order-info-box'>
    <div class='col-1'>
        <div class='box'>
            <div class='box-content'>
              <p style='font-size: small;'> ID: $lastInsertId</p>
            </div>
            <div class='box-content'>
              <p style='font-size: small;'> Customer Name: $to_name</p>
            </div>
            <div class='box-content'>
              <p style='font-size: small;'> Order: <a href=".Mage::helper('core/url')->getHomeUrl()."/sales/order/view/order_id/$order_id>#$increment_id</a></p>
            </div>
            <div class='box-content'>
              <p style='font-size: small;'> Status:Pending</p>
            </div>

        </div>
    </div></td><td>
    <div class='col-2'><br />
        <div class='box box-payment'>

            <div class='box-content'>
                <p>Created Time: $created_time</p>
            </div>
            <div class='box-content'>
                <p>Package Opened: $package</p>
            </div>
            <div class='box-content'>
                <p>Return Type: $return_type<a></a></p>
            </div>
        </div>
    </div>
</div></td></tr><tr><td colspan='2'>
            <div class='box-content'>
               Reason: $reason; 
            </div></td></tr>
</table>";
  $sender_email = Mage::getStoreConfig('jayje_section/jayje_group/jayje_rmamail',Mage::app()->getStore());
        $Body_text= $Body;
 $mail = new Zend_Mail(); //class for mail
                $mail->setBodyHtml($Body_text); //for sending message containing html code
                $mail->setFrom($sender_email, $sender_name);
                $mail->addTo($to_email, $to_name);
                $mail->addCc($cc, $ccname);    //can set cc
                $mail->addBCc($bcc, $bccname);    //can set bcc
                $mail->setSubject($subject); 
      try {
            $mail->send();
            Mage::getSingleton('core/session')->addSuccess('Your request has been sent');
        }
            catch (Exception $e) {
            Mage::getSingleton('core/session')->addError('Unable to send.');
        }  
        
            $this->loadLayout();
            Mage::getSingleton('core/session')->addSuccess($message);
            $this->_redirect($url);
            $this->renderLayout();
   
    }
    public function viewAction()
     { 
 		$this->loadLayout();     
        $session = Mage::getSingleton('customer/session');
            if(!$session->isLoggedIn()) {
                $this->_redirect('customer/account/login/');
            }
       $arrParams = $this->getRequest()->getParams();
        $rma_id = $arrParams['rma_id'];
        Mage::register('list', $rma_id);     
		$this->renderLayout();
       
 }
 
     public function formpostcommentsAction(){
         $arrParams = $this->getRequest()->getParams();
       //  print_r($arrParams);
        $rma_id = $arrParams['rma_id'];
        $update_time = date("Y-m-d g:i:s");
        $comments = $arrParams['comments'];
        $session = Mage::getSingleton('customer/session');
 
            if($session->isLoggedIn()) {
               $customer = $session->getCustomer();
               $by = $customer->getName();
            }
                  $model1 = Mage::getModel('rma/rcomments');             
                  $model1->setRmaid($rma_id);        
                  $model1->setStype('Comments');        
                  $model1->setComments($comments);        
                  $model1->setDate($update_time);        
                  $model1->setBy($by);        
                  $model1->save(); 
        
            $this->loadLayout();
            $message = $this->__('Your comment has been submitted');
            Mage::getSingleton('core/session')->addSuccess($message);
            $this->_redirect('rma/index/view/rma_id/'.$rma_id);
            $this->renderLayout();

        
     }
     
     public function sendAction(){
        $mail_body = "<h3> These are ordered by you in the event - '' </h3> <br/>".  $email_body;        
        $to_email = "customer email"; 
        $to_name  = 'Customer';
        $subject  = 'Orders';
        $sender_email = Mage::getStoreConfig('jayje_section/jayje_group/jayje_rmamail',Mage::app()->getStore());
        $sender_name  = "mail";

        $Body_text= $Body;
 $mail = new Zend_Mail(); //class for mail
                $mail->setBodyHtml($Body_text); //for sending message containing html code
                $mail->setFrom($sender_email, $sender_name);
                $mail->addTo($to_email, $to_name);
                $mail->addCc($cc, $ccname);    //can set cc
                $mail->addBCc($bcc, $bccname);    //can set bcc
                $mail->setSubject($subject); 
               // $mail->send();      
  
     }

}