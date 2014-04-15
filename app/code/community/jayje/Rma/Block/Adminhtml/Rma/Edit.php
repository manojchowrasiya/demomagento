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


class Jayje_Rma_Block_Adminhtml_Rma_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'rma';
        $this->_controller = 'adminhtml_rma';
        
        $this->_updateButton('save', 'label', Mage::helper('rma')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('rma')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('rma_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'rma_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'rma_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText(){
        if( Mage::registry('rma_data') && Mage::registry('rma_data')->getId() ) {
            $status = Mage::registry('rma_data')->getAdminstatus();
            $status = Mage::getModel('rma/rstatus')->getStatusById($status);
            return Mage::helper('rma')->__("Edit RMA,  Status -'%s' ", 
            $this->htmlEscape($status));
            
            
        } else {
            return Mage::helper('rma')->__('Add RMA');
        }
    }


}