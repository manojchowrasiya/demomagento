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


class Jayje_Rma_Block_Adminhtml_Rma_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('rma_form', array('legend'=>Mage::helper('rma')->__('RMA information')));
   
   $id     = $this->getRequest()->getParam('id');
   if($id != ''){ 
   Mage::register('rid', $id);
   
       $fieldset->addField('title', 'label', array(
          'label'     => Mage::helper('rma')->__('Title'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'title',
      ));
      
      $fieldset->addField('increment_id', 'text', array(
          'label'     => Mage::helper('rma')->__('OrderID'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'increment_id',
      ));
      
      $fieldset->addField('customer_id', 'hidden', array(
          'label'     => Mage::helper('rma')->__('Customer Name'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'customer_id',
      ));


      }else{
             
          $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('rma')->__('Title'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'title',
      ));
      
      $fieldset->addField('increment_id', 'text', array(
          'label'     => Mage::helper('rma')->__('OrderID'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'increment_id',
      ));
      
      $fieldset->addField('customer_id', 'text', array(
          'label'     => Mage::helper('rma')->__('Customer Name'),
          'class'     => 'required-entry',
          'required'  => false,
          'name'      => 'customer_id',
      ));


}		
      $fieldset->addField('adminstatus', 'select', array(
          'label'     => Mage::helper('rma')->__('Tracking No:'),
          'name'      => 'adminstatus',
          'values'    => Mage::getModel('rma/rstatus')->getAllStatusArray('rma_status'),
      ));
     
      $fieldset->addField('tracking_no', 'text', array(
          'label'     => Mage::helper('rma')->__('Tracking No'),
          'name'      => 'tracking_no',
      ));
     
      $fieldset->addField('reason', 'textarea', array(
          'name'      => 'reason',
          'label'     => Mage::helper('rma')->__('Reason'),
          'title'     => Mage::helper('rma')->__('Reason'),
          'style'     => 'width:300px; height:100px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
      
      $fieldset->addField('return_type', 'select', array(
          'label'     => Mage::helper('rma')->__('Return Type'),
          'name'      => 'return_type',
          'values'    => Mage::getModel('rma/rstatus')->getAllStatusArray('rma_returntype'),
      ));
     
      $fieldset->addField('comments', 'textarea', array(
          'name'      => 'comments',
          'label'     => Mage::helper('rma')->__('Comments'),
          'title'     => Mage::helper('rma')->__('Comments'),
          'style'     => 'width:300px; height:100px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getRmaData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getRmaData());
          Mage::getSingleton('adminhtml/session')->setRmaData(null);
      } elseif ( Mage::registry('rma_data') ) {
          $form->setValues(Mage::registry('rma_data')->getData());
      }
      return parent::_prepareForm();
  }
}