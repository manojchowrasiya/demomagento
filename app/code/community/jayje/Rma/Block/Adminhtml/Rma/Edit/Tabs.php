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


class Jayje_Rma_Block_Adminhtml_Rma_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('rma_tabs');
      $this->setDestElementId('edit_form');
  //    $this->setTitle(Mage::helper('rma')->__('RMA Information'));
  }

  protected function _beforeToHtml()
  {
             
      $this->addTab('form_section', array(
          'label'     => Mage::helper('rma')->__('RMA'),
          'title'     => Mage::helper('rma')->__('RMA'),
          'content'   => $this->getLayout()->createBlock('rma/adminhtml_rma_edit_tab_form')->toHtml(),
      ));
      
  $this->addTab('rma', array(
                    'label'     => Mage::helper('rma')->__('RMA Information'),
                    'class'     => 'ajax',
                    'url'       => $this->getUrl('rma/adminhtml_rma/info/', 
                    array('id' => Mage::registry('rid'))),
                ));      
                
      
#          $this->addTab('form_sectionss', array(
#          'label'     => Mage::helper('rma')->__('RMA'),
#          'title'     => Mage::helper('rma')->__('RMA'),
#          'content'   => $this->getLayout()->createBlock('rma/adminhtml_rma_info')->toHtml(),
#      ));
#
  $this->addTab('comments', array(
                    'label'     => Mage::helper('rma')->__('Comments'),
                    'class'     => 'ajax',
                    'url'       => $this->getUrl('rma/adminhtml_rma/comments/', 
                    array('id' => Mage::registry('rid'))),
                ));      
              

  $this->addTab('rmaorder', array(
                    'label'     => Mage::helper('rma')->__('Order'),
                    'class'     => 'ajax',
                    'url'       => $this->getUrl('rma/adminhtml_rma/order', 
                    array('id' => Mage::registry('rid'))),
                ));
      return parent::_beforeToHtml();
  }
}