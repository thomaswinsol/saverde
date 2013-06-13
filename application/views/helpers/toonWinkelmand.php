<?php
class Zend_View_Helper_toonWinkelmand extends Zend_View_Helper_Abstract
{

    public function toonWinkelmand($data)
    {
        $html=null;
        $productModel = new Application_Model_Product();
        foreach ($data as $key => $value) {
            $product=$productModel->getProduct(Zend_Registry::get('Zend_Locale'),1,$key);
            $html .= "<ul>"."<li>".$product['titel']."</li>"."<li>".$value."</li>"."</ul>";
        }
        return $html;
    }

}

