<?php
class Magestore_Giftvoucher_IndexController extends Mage_Core_Controller_Front_Action
{
    public function checkAction()
    {
        //if(!Mage::helper('maegenotification')->checkLicenseKeyFrontController($this)){return;}


	 if (!Mage::helper('customer')->isLoggedIn()) {
            $this->_redirect('customer/account/login');
            return;
        }


	
        
        $this->loadLayout();
        $max = Mage::helper('giftvoucher')->getGeneralConfig('maximum');
        
        if ($code = $this->getRequest()->getParam('code')) {
            $this->getLayout()->getBlock('head')->setTitle(Mage::helper('giftvoucher')->getHiddenCode($code));
            
            $giftVoucher = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($code);


	  	if( $giftVoucher->getActivatedCid()!=Mage::getSingleton('customer/session')->getId())
		{
			Mage::getSingleton('core/session')->addError(Mage::helper('giftvoucher')->__('Invalid Gift Voucher Code.'));	
			$this->_redirect("giftvoucher/index/check");
		}  

            $codes       = Mage::getSingleton('giftvoucher/session')->getCodes();
            if (!$giftVoucher->getId()) {
                $codes[] = $code;
                $codes   = array_unique($codes);
                Mage::getSingleton('giftvoucher/session')->setCodes($codes);
            }
            
            if (!Mage::helper('giftvoucher')->isAvailableToAddCode()) {
                Mage::getSingleton('giftvoucher/session')->addError(Mage::helper('giftvoucher')->__('The maximum number of times to enter Gift Voucher code is %d!', $max));
                $this->_initLayoutMessages('giftvoucher/session');
                $this->renderLayout();
                return;
            }
            
            if (!$giftVoucher->getId()) {
                $errorMessage = Mage::helper('giftvoucher')->__('Invalid voucher code. ');
                if ($max)
                    $errorMessage .= Mage::helper('giftvoucher')->__('You have %d times for checking voucher code.', $max - count($codes));
                Mage::getSingleton('giftvoucher/session')->addError($errorMessage);
            }
        } else {
            $this->getLayout()->getBlock('head')->setTitle(Mage::helper('giftvoucher')->__('Check Gift Voucher Balance'));
            if (!Mage::helper('giftvoucher')->isAvailableToAddCode())
                Mage::getSingleton('giftvoucher/session')->addError(Mage::helper('giftvoucher')->__('The maximum number of times to enter Gift Voucher code is %d!', $max));
        }
        
        $this->_initLayoutMessages('giftvoucher/session');
        $this->renderLayout();
    }
    
    public function activateGcAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("FREECULTR Gift Card - Activation"));
        $this->renderLayout();
    }
    public function processActAction()
    {
        $result = array(
            'success' => false,
            'message' => false,
            'error' => false
        );
        
        
        
        
        try {
            // check if ajax request with post method or not//			
            if (!$this->getRequest()->isPost() || !$this->getRequest()->isXmlHttpRequest()) {
                throw new Exception('Invalid Request');
            }
            
            $post = $this->getRequest()->isPost();
            
            
            
            
            
            
            if (md5(trim($_POST['norobot'])) == Mage::getSingleton('core/session')->getRandomnr()) {
                // here you  place code to be executed if the captcha test passes
                //echo "Hey great , it appears you are not a robot";
            } else {
                // here you  place code to be executed if the captcha test fails
                throw new Exception('You have entered wrong captcha code or captcha code hasbeen expired so please refresh the code and try again.');
                
            }
            
            $gift_voucher      = ($this->getRequest()->getParam("gv_giftcode"));
            $gift_pincode      = ($this->getRequest()->getParam("gv_pincode"));
            $gift_firstname    = ($this->getRequest()->getParam("gv_firstname"));
            $gift_lastname     = ($this->getRequest()->getParam("gv_lastname"));
            $gift_emailaddress = ($this->getRequest()->getParam("gv_emailaddress"));
            $captcha_code      = ($_POST['norobot']);
            
            // $captcha_code=11;
            
            
            
            if ($gift_voucher == '' || $gift_pincode == '' || $gift_firstname == '' || $gift_lastname == '' || $gift_emailaddress == '' || $captcha_code == '') {
                throw new Exception('Invalid Data Provided.');
            }
            
            $obj = Mage::getModel('giftvoucher/giftvoucher')->loadByCode($gift_voucher);
            if ($obj->getId()) {
                if ($obj->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE) {
                    throw new Exception('Gift Voucher is already active.');
                } elseif ($obj->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_USED) {
                    throw new Exception('Gift Voucher hasbeen used.');
                } elseif ($obj->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_EXPIRED) {
                    throw new Exception('Gift Voucher hasbeen Expired.');
                } elseif ($obj->getStatus() == Magestore_Giftvoucher_Model_Status::STATUS_DISABLED) {
                    throw new Exception('Gift Voucher is disabled.Please contact our customer care for more information.');
                } elseif ($obj->getStatus() != Magestore_Giftvoucher_Model_Status::STATUS_ASSIGNED) {
                    throw new Exception('Invalid Gift Voucher');
                } else // alright go inside  // Mage::helper('customer')->isLoggedIn()
                    {
                    if ($obj->getGiftPincode() == $gift_pincode) { // check if pin code request match

			 
                        $customer_check = Mage::getModel('customer/customer');
			$customer_check->setWebsiteId(Mage::app()->getWebsite()->getId());
                        $customer_check->loadByEmail($gift_emailaddress);

			//echo "<pre>";
			//print_r($customer_check);


			// throw new Exception('1.');

                        
                        if (Mage::helper('customer')->isLoggedIn()) {
                            $customer_session = Mage::getSingleton('customer/session')->getCustomer();
                            
                        } elseif ($customer_check->getId()) {
                            //Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
                            //$customer_session = Mage::getSingleton('customer/session')->getCustomer();
                            $customer_session = $customer_check;
			  
                            
                        } else // if customer is not logged in
                            {
                            $customer = Mage::getModel('customer/customer')->setId(null);
                            $password = $customer->generatePassword(8);
                            $customer->setData('prefix', $data['gv_prefix']);
                            $customer->setData('firstname', $gift_firstname);
                            $customer->setData('lastname', $gift_lastname);
                            $customer->setData('email', $gift_emailaddress);
                            $customer->setData('password', $password);
                            $customer->setIsSubscribed(1);
                            $customer->getGroupId();


				 
                            
                            $validationCustomer = $customer->validate();
                            if (is_array($validationCustomer)) {
                                $errors = array_merge($validationCustomer, $errors);
                            }
                            $validationResult = count($errors) == 0;
                            
                            
                            if (true === $validationResult) {
                                $customer->save();
                                $result['success'] = true;
                                Mage::getSingleton('customer/session')->setCustomerAsLoggedIn($customer);
                                $customer_session = Mage::getSingleton('customer/session')->getCustomer();
                                $customer->sendNewAccountEmail('registered');
                                $result['message'] = 'customer_logged_in';
                                
                            } else {
                                if (is_array($errors)) {
                                    $result['error']  = 'validation_failed';
                                    $result['errors'] = $errors;
                                } else {
                                    //$this->_getSession()->addError($this->__('Invalid customer data'));
                                    $result['error'] = 'Invalid Customer Data';
                                }
                                
                                throw new Exception($result['error']);
                            }
                            
                        }
                        
                        $activated_on = new Zend_Date();
                        $email        = $customer_session->getEmail(); // To get Email Id of a customer
                        $firstname    = $customer_session->getFirstname(); // To get Firstname of a customer
                        $lastname     = $customer_session->getLastname(); // To get Last name of a customer
                        
                        $obj->setStatus(Magestore_Giftvoucher_Model_Status::STATUS_ACTIVE) // mod
                            ->setActivatedOn($activated_on->toString('YYYY-MM-dd HH:mm:ss'))->setActivatedEmail($customer_session->getEmail())->setActivatedCid($customer_session->getId())->setActivatedName($customer_session->getFirstname() . " " . $customer_session->getLastname());
                        
                        #### mod ####
                        if ($timeLife = Mage::helper('giftvoucher')->getGeneralConfig('expire')) {
                            //$currentDate = Mage::getModel('core/date')->gmtDate();
                            $expire = new Zend_Date(); //$currentDate);
                            $expire->addDay($timeLife);
                            $obj->setExpiredAt($expire->toString('YYYY-MM-dd HH:mm:ss'));
                        }
                        
                        $obj->save();
                        $result = array(
                            'success' => true,
                            'message' => (!Mage::helper('customer')->isLoggedIn()) ? 'Gift Voucher has been activated successfully. Please login to check the gift voucher balance from my account section.' : 'Gift Voucher hasbeen activated successfully.',
                            'loggedin' => (int)Mage::helper('customer')->isLoggedIn()
                        );
                        
                    } else {
                        throw new Exception('Invalid Gift Voucher/ Pincode Provided');
                    }
                    
                }
            } else // voucher is invalid or used
                {
                throw new Exception('Invalid Gift Voucher/ Pincode Provided');
            }
        }
        catch (Mage_Core_Exception $e) {
            $result['error'] =  $e->getMessage();
        }
        catch (Exception $e) {
            $result['error'] =  $e->getMessage();
        }
        $this->getResponse()->setBody(Zend_Json::encode($result));
        
    }
    
    
    public function captchaAction()
    {
        $randomnr = rand(1000, 9999);
        $session  = Mage::getSingleton("core/session");
        $session->setData("randomnr", md5($randomnr));
        
        
        $im = imagecreatetruecolor(100, 32);
        
        $white = imagecolorallocate($im, 255, 255, 255);
        $grey  = imagecolorallocate($im, 150, 150, 150);
        $black = imagecolorallocate($im, 0, 0, 0);
        
        imagefilledrectangle($im, 0, 0, 200, 35, $black);
        
        //path to font - this is just an example you can use any font you like:
        
        $font = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . '/font/karate/Karate.ttf';
        
        imagettftext($im, 17, 4, 28, 26, $grey, $font, $randomnr);
        
        imagettftext($im, 17, 4, 21, 28, $white, $font, $randomnr);
        
        //prevent caching on client side:
        header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        
        header("Content-type: image/gif");
        imagegif($im);
        imagedestroy($im);
        exit;
    }
}
