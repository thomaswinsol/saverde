<?php
/**
 * Product helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_ProductHelper extends Zend_View_Helper_Abstract
{
		
        public function getFoto($productId){ 
            $productfotoModel = new Application_Model_ProductFoto();
            $result = $productfotoModel->getAll("IDProduct=".(int)$productId);
            if (!empty($result)) {
                $fotoModel = new Application_Model_Foto();
                $foto=$fotoModel->getOne($result[0]['IDFoto']);
                echo "<img src='/uploads/foto/".$foto['fileName']."'>";
            }
        }


}

