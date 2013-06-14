<?php
class Zend_View_Helper_toonWinkelmand extends Zend_View_Helper_Abstract
{

    public function toonWinkelmand($data)
    {
        $html=null;
        
            $html .= "<div class='winkelmandempty'>".$this->view->translate("txtWinkelmandLeeg")."</div>";

        return $html;
    }

}

