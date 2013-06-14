<?php
class Zend_View_Helper_toonWinkelmand extends Zend_View_Helper_Abstract
{

    public function toonWinkelmand($data)
    {
        $html=null;
        
        if (!empty($data)) {
            $productModel = new Application_Model_Product();
            $counter=0;
            $totaal=0;

            foreach ($data as $key => $value) {
                $product=$productModel->getProduct(Zend_Registry::get('Zend_Locale'),1,$key);
                if (!empty($product)) {
                    if ($counter==0) {
                        $html .= $this->view->translate("txtWinkelmand");
                        $html .= "<table class='frm_01 frm_02'>";
                        $html .= "<tr>";
                            $html .= "<th>".$this->view->translate("txtProduct")."</th>";
                            $html .= "<th>".$this->view->translate("txtAantal")."</th>";
                            $html .= "<th>".$this->view->translate("txtPrijs")."</th>";
                         $html .= "</tr>";
                    }

                $totaal += ($product['prijs']*$value);
                
                $html .= "<tr>";
                    $html .= "<td>".$product['titel']."</td>";
                    $html .= "<td>".$value."</td>";
                    $html .= "<td class='price'>".$this->view->ShowCurrency($product['prijs'])."</td>";
                $html .= "</tr>";
                $counter++;
                }
                
            }
            if ($counter>0) {
                $html .= "<tfoot>";
                $html .= "<tr><td></td>";
                $html .= "<td>Totaal</td>";
                    $html.= "<td class='price'>". $this->view->ShowCurrency($totaal)."</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                    $html.= "<td colspan=3><a id='Winkelmandbestellen' href='". $this->view->url(array('controller'=>'winkelmand' , 'action'=>'winkelmandbestellen')) ."'>". $this->view->translate('txtBestellen')."</a></td>";
                $html .= "</tr>";
                 $html .= "<tr>";
                $html.= "<td colspan=3><a href='". $this->view->url(array('controller'=>'winkelmand' , 'action'=>'winkelmandLeegmaken')) ."'>". $this->view->translate('txtWinkelmandLeegmaken')."</a></td>";
                $html .= "</tr>";
                $html .= "</tfoot>";
                $html .= "</table>";
            }
        }
        else {
            $html .= "<div class='winkelmandempty'>".$this->view->translate("txtWinkelmandLeeg")."</div>";
        }
        return $html;
    }

}

