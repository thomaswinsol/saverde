<?php

class OrderController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function listAction() {
        try
        {
            $params = $this->_getAllParams();
            $orderModel = new Application_Model_Order();
            if (!isset($params['id'])) {
                $params['id']=null;
            }
            if (!isset($params['invoice'])) {
                $params['invoice']=null;
            }
            if (!isset($params['archive'])) {
                $params['archive']=null;
            }
            $orders = $orderModel->getList($params['id'], $params['invoice'], $params['archive']);
            $this->view->dataGrid = $orderModel->BuildDataGrid($orders,$params['id']);
            $this->view->id=$params['id'];
            $this->view->invoice=$params['invoice'];
            $this->view->archive=$params['archive'];
            if ($params['invoice']==2) {
                $this->view->saldo      =  $orderModel->getOpenstaandSaldo(0);
                $this->view->vervallen  =  $orderModel->getOpenstaandSaldo(1);
            }

        } catch(Exception $e) {
    		return NULL;
    	}
       
    }

    public function detailAction() {
        $form = new Application_Form_Order;
        $this->view->form = $form;
        if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();

            if ($this->view->form->isValid($postParams)) {
                $orderModel = new Application_Model_Order();
                $orderid=$orderModel->save($postParams, $postParams['ID']);

                $this->_redirect($this->view->url(array('controller'=> 'Order', 'action'=> 'detail', 'order_id'=>$orderid)));
            }
        }

        $orderid = (int) $this->_getParam('order_id');
        if ( !empty($orderid)) {
            $orderModel = new Application_Model_Order();
            $order = $orderModel->getOne( $this->_getParam('order_id') );
            $orderdetailModel = new Application_Model_OrderDetail();
            $where = "ID_Order=".$orderid;
            $this->view->orderProducts = $orderdetailModel->getAll($where);
            $this->view->order= $order;
            $id= $order['ID_Klant'];
            $form->populate($order);

            $formdetail = new Application_Form_OrderDetail;
                 $iddetail = (int) $this->_getParam('id_orderdetail');
                 if ( empty($iddetail)) {
                    $orderdetail=array('ID_Order'=>$order['ID']);
                    $formdetail->populate($orderdetail);
                 } else {
                     $detail=$orderdetailModel->getOne($iddetail);
                     $formdetail->populate($detail);
                 }

                $this->view->formDetail = $formdetail;
        } else {
            $id = (int) $this->_getParam('id');
            $fields=array("ID_Klant"=>$id,"OrderDate"=>date('Y-m-d'));
            $form->populate($fields);
            $this->view->formDetail = null;
        }
        $klantModel = new Application_Model_Klant();
        $this->view->klant = $klantModel->getOne($id);
        $this->view->orderid= $orderid;
    }

    public function ajaxSaveOrderdetailAction() {
                $this->_helper->layout->disableLayout();
     		$formData  = $this->_request->getPost();
                //print_r($formData);
                //die("ok");
    		parse_str($formData['data'], $data);
    		$error=0;
                $this->view->id= $data['ID_Order'];
                $form = new Application_Form_OrderDetail;
    		if (!$form->isValid($data)){
    			$error=1;
    		}
                else {
                    $orderdetailModel = new Application_Model_OrderDetail();
                    $orderdetailModel->save($data, $data['ID']);
                    $where = 'ID_Order='.$data['ID_Order'];
                    $orderdetail = $orderdetailModel->getAll($where);
                    $orderdata=$this->getOrderSubTotal($orderdetail,$data['ID_Order']);
                    $dbFields=$this->updatePrices($orderdata);
                    $orderModel = new Application_Model_Order();
                    $orderModel->update($dbFields,$data['ID_Order']);
                }
    		$this->view->error=$error;                
    }

    public function getOrderSubTotal($detail,$id) {
        $orderSubTotal = 0;
        $orderProductCounter = 0;
        foreach($detail as $v){
                $totalProductPriceExclVat = ($v['Prijs'] * $v['Aantal']);
		$orderSubTotal += $totalProductPriceExclVat;
                ++$orderProductCounter;
        }
        $data=array();
        if (!empty($orderProductCounter)){
            $orderModel = new Application_Model_Order();
            $order = $orderModel->getOne($id);
            $data['orderDiscountPercentage'] = !empty($order['KortingGlobaalInProcent']) && $order['KortingGlobaalInProcent'] > 0? (int)$order['KortingGlobaalInProcent'] : 0;
            $data['orderSubTotal']= $orderSubTotal;
            $data['BTWpercentage']=$order['BTWPercentage'];
            $data['BTWMedecontractant']=$order['BTWMedecontractant'];
        }
        return $data;
    }


    public function SaveContext()
    {
        $_SESSION ['context'] = $this->context;
    }

    public function productAction(){
        $this->_helper->viewRenderer->setNoRender();
        $formData  = $this->_request->getPost();


        $orderId    = (int)$formData['orderId'];    
        $data = $formData;

        $orderProductModel = new Application_Model_OrderDetail();
        if (isset($data['btn_deleteProduct']) || isset($data['btn_deleteProduct_x'])){

    	//delete selected products

    		if (!isset($data['orderProducts']) || empty($data['orderProducts'])){
    			$this->_redirect('/order/detail/order_id/' . $orderId);
    		}
                
    		$dbFields = array(
    				'IsDeleted' => 1, // 1 = product is deleted
    			);
                $totaal=0;
    		foreach ($data['orderProducts'] as $orderProductId){
                      
    			$orderProductModel->update($dbFields,$orderProductId);

                        $orderProduct = $orderProductModel->getOne((int)$orderProductId);
                        $totaal = $totaal + ($orderProduct['Prijs']*$orderProduct['Aantal']);
    		}
                //$dbFields=$this->updatePrices($data, $totaal);
                //$this->model->update($dbFields,$orderId);
    		$this->_redirect('/order/detail/order_id/' . $orderId);
    		//delete from database
    			//$orderProductModel->deleteById($data['orderProducts']);
    			//$this->_redirect('/order/' . $orderId);
    		return;
    	}
         else if (isset($data['btn_updatePrices'])){

    		$dbFields=$this->updatePrices($data);
                $orderModel = new Application_Model_Order();
                $orderModel->update($dbFields,$orderId);
    		$this->_redirect('/order/detail/order_id/' . $orderId);
    		return;
    	}
        else if (isset($data['btn_confirmOrder'])){
    		    $dbFields = array(
    				'ID_Type' => 2,  
    			);
                    $orderModel = new Application_Model_Order();
                    $orderModel->update($dbFields,$orderId);
                    $this->_redirect('/order/detail/order_id/' . $orderId);
                    return;
    	}
        else if (isset($data['btn_invoiceOrder'])){
                $formInvoiceOrder = new Application_Form_OrderFactuur();
                if (!$formInvoiceOrder->isValid($formData)) {
                    $formInvoiceOrder->populate($formData);// exit;
                    //Zend_Debug::dump($this->_getAllParams(),'all params');
                    //$this->setErrorMessages();
                    $formErrors = $formInvoiceOrder->getMessages();
                    if (!empty($formErrors)){
            		//var_dump($formErrors); exit;
            		$this->flashMessenger->setNamespace('formErrors');
            		foreach($formErrors as $k=>$v){
            			foreach ($v as $v2){
            				$label = $formInvoiceOrder->getElement($k)->getLabel();
            				$this->flashMessenger->addMessage($label. ' : ' . $v2);
            			}
            		}
                    }
                     $this->_redirect('/order/detail/order_id/' . $orderId);
                }
                    $formData['DatumFactuur']=strtotime($formData['DatumFactuur']);
                    $formData['VervalDatum'] =strtotime($formData['VervalDatum']);
    		    $dbFields = array(
    				'ID_Type' => 3, 
                                'Factuurnr' => $formData['Factuurnr'],
                                'DatumFactuur' => date("Y-m-d",$formData['DatumFactuur']),
                                'VervalDatum' => date("Y-m-d",$formData['VervalDatum']),
    			);
                        //Zend_Debug::dump($formData['DatumFactuur'],'Fields');
                        //exit();
                        $orderModel = new Application_Model_Order();
    			$orderModel->update($dbFields,$orderId);
                        $this->_redirect('/order/detail/order_id/' . $orderId);
                        return;
    	}
        else if (isset($data['btn_paidInvoice'])){
                $dbFields=$this->paidInvoice($data);
                $orderModel = new Application_Model_Order();
                $orderModel->update($dbFields,$orderId);
                $this->_redirect('/order/detail/order_id/' . $orderId);
                return;
        }
    }


     public function updatePrices($data = array() , $productDeleted = null){
                $BTWMedecontractant=0;
                if (isset($data['BTWMedecontractant'])) {
                    if ($data['BTWMedecontractant'] && $data['BTWpercentage']==0) {
                     $BTWMedecontractant=1;
                    }
                }
                //Korting
    		$data['orderDiscountPercentage'] =(int) $data['orderDiscountPercentage'];
    		$discount = (($data['orderSubTotal'] * $data['orderDiscountPercentage']) / 100);
                //TotaalExclBTW
                $orderSellPriceExclVAT = $data['orderSubTotal'] - $discount;
                
                //Productdetail verwijderen
                if (!empty($productDeleted)) {
                            $discount      = (($productDeleted * $data['orderDiscountPercentage']) / 100);
                            $productDeleted = $productDeleted-$discount;
                            $orderSellPriceExclVAT = $orderSellPriceExclVAT -$productDeleted;
                }
                //BTW
                $btwpercentage = $data['BTWpercentage'];
                $btw = 1 + ($btwpercentage/100);
                //TotaalInclBTW
                $totalOrderPrice = $orderSellPriceExclVAT * $btw;

    			$dbFields = array(
                                'BTWMedecontractant'      => $BTWMedecontractant,
    				'KortingGlobaalInProcent' => $data['orderDiscountPercentage'],
    				'Korting'                 => $discount,
                                'BTWPercentage'           => $btwpercentage,
                                'TotaalprijsExclBTW'      => $orderSellPriceExclVAT,
                                'BTW'                     => $totalOrderPrice-$orderSellPriceExclVAT,
                                'TotaalprijsInclBTW'      => $totalOrderPrice,
    			);

    		return $dbFields;
    }


     public function ajaxGetFactuurnrAction() {
        $this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
        // $this->_helper->json(array('status' => 1)); exit;
    	$params = $this->getRequest()->getParam('param');
        $param = explode(":::", $params);
    	if (empty($param[0])) {
    		return null;
    	}
        $jaar=substr($param[0], 6 , 4);
        $factuurnr1=$jaar.'000';
        $factuurnr2=$jaar.'999';

    	$orderModel = new Application_Model_Order();
    	$this->view->Factuur = $orderModel->getLastInvoice($factuurnr1, $factuurnr2);

        $betalingsvoorwaardeModel = new Application_Model_Betalingsvoorwaarde();
    	$betalingsvoorwaarde = $betalingsvoorwaardeModel->getOne($param[1]);

        $duedate =strtotime(date("d-m-Y", strtotime($param[0])));
        if ($betalingsvoorwaarde['EindeMaand']=='1') {
            $currentMonthNumeric = date('m', $duedate);
            $currentFullYear     = date('Y', $duedate);
            $lastDayOfTheMonth   = date('d-m-Y',mktime(0,0,0,$currentMonthNumeric + 1,0,$currentFullYear));
            //die($lastDayOfTheMonth.":::".date('d-m-Y',$duedate));
            $duedate =  $lastDayOfTheMonth;
        }
        else {
            $duedate =  date('d-m-Y',$duedate);  
        }
        $dagen="+".$betalingsvoorwaarde['AantalDagen']. " days";
        $duedate =strtotime(date("d-m-Y", strtotime($duedate. "$dagen")));
        
        $currentMonthNumeric = date('m', $duedate);
        $currentFullYear     = date('Y', $duedate);
        $lastDayOfTheMonth   = date('d-m-Y',mktime(0,0,0,$currentMonthNumeric + 1,0,$currentFullYear));

        if ($betalingsvoorwaarde['EindeMaand']=='E') {
            $this->view->Vervaldatum = $lastDayOfTheMonth;
        }
        else
        {
            $this->view->Vervaldatum = date("d-m-Y",$duedate);
        }

    }


    public function ajaxGetDetailAction() {
        $this->_helper->layout->disableLayout();
        $jaar  = $this->getRequest()->getParam('jaar');
        $maand = $this->getRequest()->getParam('maand')+1;
        $maand = str_pad($maand, 2, '0', STR_PAD_LEFT);
        $data1 = null;
        $date2 = null;
        if ($maand<>100) {
             $data1= $jaar.'-'.$maand;
        } else {
             $data2= $jaar;
        }
        $orderModel = new Application_Model_Order();
        $this->view->data1 = $data1;
        $this->view->data2 = $data2;
        $this->view->result= $orderModel->getTurnoverDetail($data1,$data2);
    }

     public function paidInvoice($data = array()){
        $status=3;
        if  (empty($data['BedragBetaald'])) {
            $data['BedragBetaald']=0;
        }
        $openstaand_bedrag= ($data['totalOrderPrice'] - $data['BedragBetaald']);
        if ($openstaand_bedrag<1) {
            $status=4;
        }
        $dbFields = array(
    			   'BedragBetaald' => $data['BedragBetaald'],
    			   'ID_Type' => $status,
    			 );
    	return $dbFields;
    }


    public function showPdfAction(){
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();

    	$orderId = (int)$this->getRequest()->getParam('id');
    	$orderModel = new Application_Model_Order();
    	$order    = $orderModel->getOne($orderId);

	$customerId    = $order['ID_Klant'];
	$customerModel = new Application_Model_Klant();
    	$klant = $customerModel->getOne($customerId);

        $orderProductModel = new Application_Model_OrderDetail();
    	$detail = $orderProductModel->getAll("ID_Order=".$orderId);
    	//Zend_Debug::dump($this->view->orderProducts); exit;
  
    	/*$htmlText = $this->view->render('/order/templates/pdf-default.phtml');*/
	//die($this->view->escape($htmlText));
        $paramPrijs = (int)$this->getRequest()->getParam('noprice');

    	$orderModel->buildPdf($order, $klant, $detail, $paramPrijs);

    }


    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $dbFields = array('ID_Status' => 99);
        $orderModel = new Application_Model_Order();
    	$orderModel->update($dbFields,$id);
        $this->_redirect('/order/list/');
    }

    public function copyAction()
    {
        $id = $this->getRequest()->getParam('id');

        $orderModel = new Application_Model_Order();
        $order      = $orderModel->getOne($id);

        $order['ID']=null;
        $copyorder=$orderModel->save($order);
        $data=array('ID_Parent'=>$id);
        $orderModel->update($data,$copyorder);

        $orderdetailModel = new Application_Model_OrderDetail();
        $detail           = $orderdetailModel->getAll("ID_Order=".$id);

        foreach($detail as $d){
                $d['ID']=null;
                $d['ID_Order']=$copyorder;
                $orderdetailModel->save($d);
        }
    	
        $this->_redirect('/order/detail/order_id/'.$copyorder);
    }

    public function rapportAction()
    {
        $orderModel = new Application_Model_Order();
        $data = $orderModel->getTurnoverByYearMonth();
        $this->context['graph']=$data;

        $this->SaveContext();
        $this->view->data = $data;
    }

       private function fillArray($array)   {
            for ($ii=0; $ii<=11 ; $ii++) {
                if (!isset($array[$ii]))  {
                    $array[$ii]=0;
                }
            }
            return $array;
        }

    public function getgrafiekAction() {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    		include_once ("jpgraph/jpgraph.php");
                include_once ("jpgraph/jpgraph_bar.php");
		include_once ("jpgraph/jpgraph_line.php");

         $year1 = date("Y");
         $year2 = $year1-1;
         $year3 = $year1-2;
         $year4 = $year1-3;
         $year5 = $year1-4;
         $year6 = $year1-5;
         $year7 = $year1-6;
         $year8 = $year1-7;


                $data=$_SESSION['context']['graph'];
                // Some data
                $data1=$data[$year1];
                $data1 = $this->fillarray($data1);
                $data2=$data[$year2];
                $data3=$data[$year3];
                $data4=$data[$year4];
                $data5=$data[$year5];


                $maand=array('Jan','Feb','Mar','Apr','Mei','Jun','Jul','Aug','Sept','Okt','Nov','Dec');

                $bplot=array();
                        if (isset($data1)){
                                        $bplot1 = new BarPlot($data1);
					$bplot1->SetFillColor('#DC143C');
					$bplot1->SetLegend($year1);
                                        $bplot1->value->Show();
                                        $bplot1->value->setformat('%01.0f');
                                        $bplot1->value->HideZero();
					//$bplot1-> SetPattern(PATTERN_DIAG1);
					$bplot[]=$bplot1;
                        }

    			if (isset($data2)){
                                        $bplot2 = new BarPlot($data2);
					$bplot2->SetFillColor('#11cccc');
					$bplot2->SetLegend($year2);
					$bplot[]=$bplot2;
                        }
    			if (isset($data3)){
                                        $bplot3 = new BarPlot($data3);
					$bplot3->SetFillColor('#1111cc');
					$bplot3->SetLegend($year3);
					$bplot[]=$bplot3;
                        }
                        if (isset($data4)){
                                        $bplot4 = new BarPlot($data4);
					$bplot4->SetFillColor('#BA55D3');
					$bplot4->SetLegend($year4);
					$bplot[]=$bplot4;
                        }
                        if (isset($data5)){
                                        $bplot5 = new BarPlot($data5);
					$bplot5->SetFillColor('#2E8B57');
					$bplot5->SetLegend($year5);
					$bplot[]=$bplot5;
                        }

                $label=$maand;
                // Size of graph
                $width=400;
                $height=500;
                // Set the basic parameters of the graph
                $gbarplot = new GroupBarPlot($bplot);
		//$accbplot->value->Show();
		//$accbplot->value->setformat('%01.0f');
                $graph = new Graph(1100,500,'auto');
 		$graph->SetScale("textlin");
		$graph->Add($gbarplot);
                $gbarplot->SetWidth(0.8);
                $graph->xaxis->SetTickLabels($label);
                $graph->yaxis->title->SetMargin(15);
				$graph->SetMargin(80,30,50,50);
                                $title='Omzet per maand (EUR)';
                                $graph->title->Set($title);
 				$graph->legend->Pos(0.01,0.01);

                $graph->Stroke();


                
    }


 


}




