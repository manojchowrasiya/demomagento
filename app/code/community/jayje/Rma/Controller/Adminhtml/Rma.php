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


class jayje_Rma_Controller_Adminhtml_Rma extends Mage_Adminhtml_Controller_Action{

	protected function _uploadAndGetName($input, $destinationFolder, $data){
		try{
			if (isset($data[$input]['delete'])){
				return '';
			}
			else{
				$uploader = new Varien_File_Uploader($input);
				$uploader->setAllowRenameFiles(true);
				$uploader->setFilesDispersion(true);
				$uploader->setAllowCreateFolders(true);
				$result = $uploader->save($destinationFolder);
				return $result['file'];
			}
		}
		catch (Exception $e){
			if ($e->getCode() != Varien_File_Uploader::TMP_NAME_EMPTY){
				throw $e;
			}
			else{
				if (isset($data[$input]['value'])){
					return $data[$input]['value'];
				}
			}
		}
		return '';
	}
}