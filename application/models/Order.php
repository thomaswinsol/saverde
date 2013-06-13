<?php
class Application_Model_Order extends My_Model
{
    protected $_name = 'orders'; //table name
    protected $_id = 'ID'; //primary key
    /*protected $_dependentTables = array('dealers','talen','orders', 'aanspreektitels');*/    
    protected $enableDataGrid = TRUE;
        
    public function save($data,$id = NULL)
    {
    	//ini
    	$currentTime =  date("Y-m-d H:i:s", time());
        $isUpdate = FALSE;

        $dbFields = array(
        	'ID_Klant'    => (int)$data['ID_Klant'],
        	'Refk'        => $data['Refk'],
                'OrderDate'   => $data['OrderDate'],
                'Opmerking'   => $data['Opmerking'],
        );
        
        if (!empty($id)) {
        	$isUpdate = TRUE;
        	$this->update($dbFields,$id);
        	return $id;
        }
        $dbFields['CreationDate']  = $currentTime;
        $dbFields['BTWPercentage'] = 6;
        $dbFields['ID_Type']       = 1;
        $dbFields['ID_Parent']     = 0;

        $dbFields['Korting']   = 0;
        $dbFields['KortingGlobaalInProcent']   = 0;
        $dbFields['TotaalprijsExclBTW']   = 0;
        $dbFields['TotaalprijsInclBTW']   = 0;
        $dbFields['BTW']   = 0;
        $dbFields['BTWMedecontractant']   = 0;
        if (isset($data['TotaalprijsExclBTW']) && !empty($data['TotaalprijsExclBTW']) ) {
            $dbFields['Korting']                    = $data['Korting'];
            $dbFields['KortingGlobaalInProcent']    = $data['KortingGlobaalInProcent'];
            $dbFields['TotaalprijsExclBTW']         = $data['TotaalprijsExclBTW'];
            $dbFields['TotaalprijsInclBTW']         = $data['TotaalprijsInclBTW'];
            $dbFields['BTW']                        = $data['BTW'];
        }
        
        $klantModel = new Application_Model_Klant();
        $klant = $klantModel->getOne($dbFields['ID_Klant']);
        if (!empty($klant['ID_BTW'])) {
            if ($klant['ID_BTW']==3) {
                $dbFields['BTWPercentage']=21;
            }
            if ($klant['ID_BTW']==4) {
                $dbFields['BTWPercentage']=0;
                $dbFields['BTWMedecontractant'] =1;
            }
        }
        return $this->insert($dbFields);        
    }

    public function import($data)
    {
    	//ini
        $data[4] = str_replace(",",".",$data[4]);
        $data[7] = str_replace(",",".",$data[7]);
        $data[8] = str_replace(",",".",$data[8]);

        $isUpdate = FALSE;
         $explode  = explode("/",$data[2]);
         $datum= substr($explode[2],0,4)."-".$explode[1]."-".$explode[0];

        $dbFields = array(
        	'ID_Klant'    => (int)$data[1],
        	'Refk'        => $data[3],
                'OrderDate'   => $datum,
                'Opmerking'   => "",
        );
        $dbFields['ID']  = $data[0];
        $dbFields['CreationDate']  = $datum;
        $dbFields['BTWPercentage'] = $data[5];
        $dbFields['ID_Type']       = 3;
        $dbFields['ID_Parent']     = 0;

        $dbFields['Korting']   = 0;
        $dbFields['KortingGlobaalInProcent']   = 0;
        $dbFields['TotaalprijsExclBTW']   = $data[4];
        $dbFields['TotaalprijsInclBTW']   = $data[8];
        $dbFields['BTW']   = $data[7];
        $dbFields['BTWMedecontractant']   = 0;
        if ($data[6]=="M"){
           $dbFields['BTWMedecontractant']   = 1;
        }

         $dbFields['Factuurnr'] = $data[9];

         $explode  = explode("/",$data[10]);
         $datum= substr($explode[2],0,4)."-".$explode[1]."-".$explode[0];
         $dbFields['DatumFactuur'] = $datum;
         $explode  = explode("/",$data[11]);
         $datum= substr($explode[2],0,4)."-".$explode[1]."-".$explode[0];
         //$dbFields['Vervaldatum'] = $datum;
        return $this->insert($dbFields);
    }

    public function insert($data)
    {
	     return parent::insert($data);       
    }

    /**
     * Update
     * @return int numbers of rows updated
     */
    public function update($data,$id)
    {
        return parent::update($data, 'id = '. (int)$id);
    }
    
     public function buildDataGrid($source_array,$id=null) {
                if (empty($id)) {
                    $source = new Bvb_Grid_Source_Array($source_array, array('ID', 'ID_Type', 'OrderDate' ,'ID_Klant','Naam','Gemeente','TotaalprijsExclBTW','BTW','TotaalprijsInclBTW','Factuurnr','DatumFactuur','Refk'));
                    $this->dataGrid->setTableGridColumns(array('ID','ID_Type','OrderDate','ID_Klant','Naam','Gemeente','TotaalprijsExclBTW','BTW','TotaalprijsInclBTW','Factuurnr','DatumFactuur','Refk'));
                }
                else {
                    $source = new Bvb_Grid_Source_Array($source_array, array('ID_Type','ID',  'OrderDate','TotaalprijsExclBTW','BTW','TotaalprijsInclBTW','Factuurnr','DatumFactuur','Refk'));
                    $this->dataGrid->setTableGridColumns(array('ID_Type','ID','OrderDate','TotaalprijsExclBTW','BTW','TotaalprijsInclBTW','Factuurnr','DatumFactuur','Refk'));
                }
    		$this->dataGrid->setSource($source);
    
    	//2. specify columns
 		$filters = new Bvb_Grid_Filters();
                $filters->addFilter('ID_Type',array('values' => array(1 => '1=Offerte', 2 => '2=Bestelling', 3 => '3=Factuur', 4 => '4=Factuur betaald') ));

                $filters->addFilter('Factuurnr');                
                $this->dataGrid->updateColumn('ID',array('style' => 'width:10px;text-align:center;','decorator' => '<a href="/order/detail/order_id/{{ID}}">{{ID}}</a>'));
                $this->dataGrid->updateColumn('ID_Type',array('title'=> 'Status', 'style' => 'width:50px;text-align:center;'));
        	$this->dataGrid->updateColumn('Refk',array('title'=> 'Referentie','style' => 'width:40px;','decorator' => '<a href="/order/detail/order_id/{{ID}}">{{Refk}}</a>'));
                $this->dataGrid->updateColumn('OrderDate',array('title'=> 'Datum', 'format'=>'Date2','style' => 'width:50px;text-align:center;'));
                $this->dataGrid->updateColumn('TotaalprijsExclBTW',array('title'=> 'Totaal(Excl.BTW)', 'format'=>'Number','style' => 'width:50px;text-align:center;'));
                $this->dataGrid->updateColumn('BTW',array('title'=> 'BTW', 'format'=>'Number','style' => 'width:50px;text-align:center;'));
                $this->dataGrid->updateColumn('TotaalprijsInclBTW',array('title'=> 'Totaal(Incl.BTW)', 'format'=>'Number','style' => 'width:50px;text-align:center;'));
                    $this->dataGrid->updateColumn('Factuurnr',array('title'=> 'Factuurnr', 'format'=>'FormatterFactuur' , 'style' => 'width:50px;text-align:center;'));
                $this->dataGrid->updateColumn('DatumFactuur',array('title'=> 'Factuurdatum', 'format'=>'Date2','style' => 'width:50px;text-align:center;'));
                if (empty($id)) {
                    $filters->addFilter('ID');
                    $filters->addFilter('ID_Klant');
                    $filters->addFilter('Naam');
                    $filters->addFilter('Gemeente');
                    $this->dataGrid->updateColumn('ID_Klant',array('title'=> 'Klantnr', 'style' => 'width:50px;text-align:center;'));
                }
        //3. build form	
			//$form   = new My_FormGrid();
			//$this->dataGrid->setForm($form);
                $this->dataGrid->addFilters($filters);
                $this->dataGrid->setRecordsPerPage(25);
        //4. deploy 	
        	return $this->dataGrid->deploy(); 	    	
    }

    public function getList($klant=null, $factuur=null, $archief=null)
    {
        $sql = 'SELECT o.ID, o.ID_Type , o.OrderDate, o.ID_Klant, k.Naam, k.Gemeente, o.TotaalprijsExclBTW, o.BTW, o.TotaalprijsInclBTW, o.Factuurnr, o.DatumFactuur, o.Refk from orders o, klanten k where o.ID_Klant = k.ID';
        $sql .=' and o.ID_Status < 99';
        if (!empty($klant)) {
            $sql .=' and o.ID_Klant='.$klant;
        }
        if (!empty($archief)) {
            $sql .=' and o.ID_Type =5';
        }
        else {
            if ($klant==null) {
                $sql .=' and o.ID_Type >=1 AND o.ID_Type <5';
            }
        }
        if (!empty($factuur)) {
            if ($factuur==2) {
                $sql .=' and o.Factuurnr>0 and o.ID_Type=3 order by DatumFactuur desc, o.Factuurnr desc';
            } else {
                $sql .=' and o.Factuurnr>0 order by DatumFactuur desc, o.Factuurnr desc';
            }
        }
        else {
            $sql .=' order by o.OrderDate desc, o.ID desc';
        }
    	$result = $this->db->fetchAll($sql);

    	return $result;
    }

    public function getTurnoverByYearMonth($klant=null, $factuur=null)
    {
         $sql = 'SELECT substr(o.DatumFactuur, 1 , 7) as datum, sum(TotaalprijsExclBTW) as som from orders o where Factuurnr>0 group by substr(o.DatumFactuur, 1 , 7) ';
         $result = $this->db->fetchAll($sql);

         $t=array();
         foreach($result as $r){
            $year  = substr($r['datum'],0, 4);
            $month = substr($r['datum'],5, 2);
            $t[$year][$month-1]= $r['som'];
         }
      	 return $t;
    }

     public function getTurnoverDetail($jaarmaand=null, $jaar=null)
     {
          if (!empty($jaarmaand)) {
                $sql = "SELECT substr(o.DatumFactuur, 1 , 7) as datum, k.ID, k.Naam, k.Gemeente, sum(o.TotaalprijsExclBTW) as som from orders o , klanten k where o.ID_Klant=k.ID and o.Factuurnr>0 and substr(o.DatumFactuur, 1 , 7)='".$jaarmaand . "' group by substr(o.DatumFactuur, 1 , 7), k.ID, k.Naam, k.Gemeente order by sum(TotaalprijsExclBTW) desc";
          } else {
                $sql = "SELECT substr(o.DatumFactuur, 1 , 4) as datum, k.ID, k.Naam, k.Gemeente, sum(o.TotaalprijsExclBTW) as som from orders o , klanten k where o.ID_Klant=k.ID and o.Factuurnr>0 and substr(o.DatumFactuur, 1 , 4)='".$jaar . "' group by substr(o.DatumFactuur, 1 , 4), k.ID, k.Naam, k.Gemeente order by sum(TotaalprijsExclBTW) desc";
          }
          $result = $this->db->fetchAll($sql);
          return $result;
     }

      public function getTurnoverByCustomer($klant=null)
      {
           $sql = "SELECT substr(o.DatumFactuur, 1 , 4) as jaar, sum(o.TotaalprijsExclBTW) as TotaalprijsExclBTW  , sum(BTW) as BTW  ,sum(o.TotaalprijsInclBTW) as TotaalprijsInclBTW from orders o where o.ID_Klant=" .$klant ." and o.Factuurnr>0  group by substr(o.DatumFactuur, 1 , 4) order by substr(o.DatumFactuur, 1 , 4) desc";
           $result = $this->db->fetchAll($sql);
           return $result;
      }

      public function getOpenstaandSaldo($vervallen=null)
      {
         if ($vervallen ) {
             $huidigedatum = date("Y-m-d");
             $sql = 'SELECT sum(TotaalprijsInclBTW) as som from orders where Factuurnr>0 and ID_Type=3 and Vervaldatum>='.$huidigedatum;
         }
         else {
             $sql = 'SELECT sum(TotaalprijsInclBTW) as som from orders where Factuurnr>0 and ID_Type=3 ';
         }
         $result = $this->db->fetchAll($sql);
      	 return $result;
      }

    public function getLastInvoice($factuurnr1, $factuurnr2){

    	if (empty($factuurnr1)){
    		return FALSE;
    	}
        $sql = 'SELECT MAX(a.Factuurnr) as Factuurnr FROM orders a where Factuurnr >='.$factuurnr1 . ' and Factuurnr <='. $factuurnr2;
        
        //return $sql;
    	$factuur = $this->db->fetchAll($sql);
       if (empty($factuur[0]['Factuurnr'])) {
           $factuur[0]['Factuurnr']=$factuurnr1+1;
       }
       else
       {
           $factuur[0]['Factuurnr']=$factuur[0]['Factuurnr']+1;
       }
    	return $factuur;
    }



     public function buildPdf($order, $klant, $detail, $noPrice=null){
		// create new PDF document
			$pdf = new My_Tcpdf('L', PDF_UNIT, PDF_PAGE_FORMAT, true); //default is UTF-8
			$currentDate = date('d.m.Y');
                // set document information
			$pdf->SetCreator(PDF_CREATOR);
			$pdf->SetAuthor("Keppens Patrick");
			$pdf->SetTitle('Offerte, bestelbon');
			$pdf->SetSubject('Offerte, bestelbon');
			$pdf->SetPrintHeader(false);

		// set header and footer fonts
			$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN,'', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(array(PDF_FONT_NAME_DATA,'', PDF_FONT_SIZE_DATA));

		//set margins
			$pdf->SetMargins(5, 2, PDF_MARGIN_RIGHT); //PDF_MARGIN_TOP
			$pdf->SetHeaderMargin(0);
                        $pdf->SetFooterMargin(0);
			/*$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);*/

		//set auto page breaks
                        /*$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);*/
		$pdf->SetAutoPageBreak(true,5);
		//set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		//initialize document
                        $pdf->AliasNbPages();
                        

                       
		/*$pdf->Image('images/winsol-logo.jpg',7,6);*/

                $counter=0;
                $totaal =0;
                $pdf->AddPage("P","A4");
                $this->printHeader($pdf, $klant, $order, $noPrice);
                foreach ($detail as $d) {
                        $current_x = $pdf->GetX();
			$current_y = $pdf->GetY();
			if ($current_y>=220) {
                                $this->printFooter($pdf);
                                $pdf->SetFont('helvetica','',10);
                                $pdf->AddPage("P","A4");
				$this->printHeader($pdf, $klant, $order);
                                $current_x = $pdf->GetX();
                                $current_y = $pdf->GetY();
			}
			$counter++;

                        $isOld=false;
                        if ($order['ID']>8528) {
                            $pdf->Cell(10,5,$counter,0,0,'C');
                            $pdf->Cell(15,5,"",0,0,'C');
			
                            $cell_width = 100;
                            $pdf->MultiCell($cell_width, 3, trim(substr($d['Omschrijving'],0,1000)),0,'T', false,'T');
                            $pdf->SetXY($current_x + $cell_width+28, $current_y);
                            $lines=$this->nblines(100, $d['Omschrijving']);
                        }
                        else {
                            $pdf->Cell(10,5,"",0,0,'C');
                            $pdf->Cell(15,5,"",0,0,'C');
                            $pdf->Cell(100,5,$d['Omschrijving'],0,0,'L');
                            $isOld=true;
                        }

                        if ($d['Aantal']>0) {
                            $pdf->Cell(20,5,number_format($d['Aantal'],2,",",""),0,0,'C');
                        } else {
                            $pdf->Cell(20,5,'',0,0,'C');
                        }
                        $square = $pdf->unichr(178);
                        $d['Eenheid']=str_replace("M2","m".$square,$d['Eenheid']);
			$pdf->Cell(15,5,$d['Eenheid'],0,0,'C');
                        if ($d['Prijs']>0 && !$noPrice) {
                            $pdf->Cell(20,5,number_format($d['Prijs'],2,",",""),0,0,'C');
                        }
                        else {
                             $pdf->Cell(13,5,'',0,0,'C');
                        }

                        if ($d['Totaal']>0 && !$noPrice) {
                            $pdf->Cell(13,5,number_format($d['Totaal'],2,",",""),0,0,'C');
                        }
                        else {
                             $pdf->Cell(13,5,'',0,0,'C');
                        }
			
                        if (!$isOld) {
                            if ($lines<=2) $lines=3;
                            $lines=($lines)*4;
                            $pdf->ln($lines);
                        }
                        else {
                            $pdf->ln(5);
                        }

			$totaal = $totaal + $d['Totaal'];

		}

                if ($order['Korting']>0 && !$noPrice) {
                    $pdf->Cell(10,5,"",0,0,'C');
                    $pdf->Cell(15,5,"",0,0,'C');
                    $pdf->Cell(100,5,"Korting ".number_format($order['KortingGlobaalInProcent'],0)."%",0,0,'C');
                    $pdf->Cell(20,5,"",0,0,'C');
                    $pdf->Cell(20,5,"",0,0,'C');
                    $pdf->Cell(20,5,"",0,0,'C');
                    $pdf->Cell(12,5,"-".$order['Korting'],0,0,'C');
                }
                $pdf->SetFont('helvetica','',8);
                if (!$noPrice) {
                    $this->printTotaal($pdf, $order);
                }
                $this->printFooter($pdf);

                if (!$noPrice) {
                    if ($order['ID_Type']>=3) {
                        $datum=date("d.m.Y");
                        $fileName = "Factuur_".trim($order['Factuurnr']).'.pdf';
                    }
                    else {
                        $fileName = "Offerte_".trim($order['ID']).'.pdf';
                    }
                } else {
                       $fileName = "Werkbon_".trim($order['ID']).'.pdf';
                } 

		$pdf->Output($fileName,'D');
    }

    private function nbLines($w, $txt)
    {
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
   // $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $wmax=0;
    $s=str_replace("\r", '', $txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
    }

    private function printHeader($pdf, $klant,$order, $noPrice)
    {  	
                
              
		$pdf->SetFillColor(256);
                $pdf->RoundedRect(5, 2, 200, 290, 5, '1234', 'FD');
		/*$pdf->SetFillColor(192);
		$pdf->RoundedRect(155, 8, 48, 12, 5, '1234', 'FD');*/
		$pdf->SetFillColor(256);
                
		/*$pdf->RoundedRect(10, 25, 90, 12, 5, '1234', 'FD');*/

		/*$pdf->RoundedRect(120, 27, 68, 32, 5, '1234', 'FD');
		$pdf->SetFillColor(255,255,0);
		$pdf->RoundedRect(10, 255, 142, 25, 5, '1234', 'FD');
		$pdf->SetFillColor(256);
		$pdf->RoundedRect(155, 255, 45, 25, 5, '1234', 'FD');*/

                /*$pdf->RoundedRect(6, 65, 148, 9, 5, '1234', 'FD');*/

                /*$pdf->RoundedRect(6, 85, 198, 9, 5, '1234', 'FD');*/
                $pdf->SetFont('helvetica','B',22);
		$pdf->ln(1);

                 
                

                 $pdf->Line(5, 92, 205, 92);
                 $pdf->Line(5, 86, 205, 86);


                 $pdf->Line(5, 86, 205, 86);
                 $pdf->Line(20, 70, 130, 70);
                 $pdf->Line(20, 70, 20, 82);
                 $pdf->Line(130, 70, 130, 82);
                 $pdf->Line(20, 82, 130, 82);

                $pdf->Cell(80,5,"",0,0,'L');
                $pdf->Cell(20,5,"BVBA KEPPENS PATRICK",0,0,'L');
                $pdf->ln(6);
                $pdf->Image('images/logopdf2.jpg',10,3,75);
                $pdf->SetFont('helvetica','',11);
		$pdf->ln(3);

                if (!empty($klant['ID_Aanspreektitel'])) {
                    $titelModel = new Application_Model_Titel();
                    $titel= $titelModel->getOne($klant['ID_Aanspreektitel']);
                    $klant['Naam']=$titel['Omschrijving']. " ". trim($klant['Naam']);
                }
		$pdf->ln(21);
		$pdf->Cell(120,5,"",0,0,'C');
		$pdf->Cell(10,5,$klant['Naam'],0,0,'L');
		$pdf->ln(5);
		$pdf->Cell(120,5,"",0,0,'C');
		$pdf->Cell(10,5,$klant['Straat'],0,0,'L');
		$pdf->ln(5);
		$pdf->Cell(120,5,"",0,0,'C');
		$pdf->Cell(10,5,$klant['Postcode'],0,0,'L');
		$pdf->Cell(10,5,$klant['Gemeente'],0,0,'L');
		$pdf->ln(10);
		$pdf->Cell(120,5,"",0,0,'C');
		If (trim($klant['Telefoon'])<>"" && $noPrice) {
			$pdf->Cell(35,5,"Tel.: ".strtolower($klant['Telefoon']),0,0,'L');
		}
                If (trim($klant['GSM'])<>"" && $noPrice) {
			$pdf->Cell(10,5,"GSM.: ".strtolower($klant['GSM']),0,0,'L');
		}
                $pdf->ln(5);
		$pdf->Cell(120,5,"",0,0,'C');
		If (trim($klant['Emailadres'])<>"" && $noPrice) {
			$pdf->Cell(10,5,"E-mail: ".strtolower($klant['Emailadres']),0,0,'L');
		}


		$pdf->ln(1);
                   
                $titel= array (1=>"Offerte",2=>"Bestelbon",3=>"Factuur",4=>"Factuur",5=>"Factuur");
                $doc = $titel[$order['ID_Type']];
                $docnr = date('Y',strtotime($order['OrderDate'])).'-'.$order['ID'];

                if ($order['ID_Type']>=3 && !$noPrice) {
                    If ($order['TotaalprijsInclBTW']<0) {
                        $doc = "Creditnota";
                    }
                    $doc = $doc . " ". $order['Factuurnr'];
                    $docnr= $order['Factuurnr'];
                }
                if ($noPrice) {
                    $doc = "Werkbon". ' - '.$order['ID'];
                }

                $pdf->SetFont('helvetica','B',16);
                $pdf->Cell(15,5,'',0,0,'L');
                $pdf->Cell(60,5,$doc,0,0,'L');
                $pdf->SetFont('helvetica','',10);
                $pdf->Cell(45,5,"",0,0,'C');
		If (!empty($klant['BTWNummer'])) {
			$pdf->Cell(10,5,"Uw BTW-nummr: "."BE".trim($klant['BTWNummer']),0,0,'L');
		}

                
		$pdf->ln(5);
                $pdf->SetFont('helvetica','',8);    
		$pdf->ln(8);
                $pdf->Cell(20,5,'',0,0,'L');
                $pdf->Cell(40,5,'Uw referentie',0,0,'L');
                 if ($order['ID_Type']>=3) {
                    $pdf->Cell(25,5,'Factuurnr',0,0,'L');
                    $pdf->Cell(20,5,'Datum factuur',0,0,'L');
                    $datum=date('d-m-Y',strtotime($order['DatumFactuur']));
                    $pdf->Cell(20,5,'Vervaldatum',0,0,'L');
                    $vervaldatum=date('d-m-Y',strtotime($order['Vervaldatum']));
                 }
                 else {
                     $pdf->Cell(25,5,'Offertenr',0,0,'L');
                     $pdf->Cell(20,5,'Datum offerte',0,0,'L');
                     $datum=date('d-m-Y',strtotime($order['OrderDate']));
                     $vervaldatum=null;
                 }
                $pdf->SetFont('helvetica','',10); 
                $pdf->ln(5);
                $pdf->Cell(20,5,'',0,0,'L');
                $pdf->Cell(40,5,$order['Refk'],0,0,'L');
                $pdf->Cell(25,5,$docnr,0,0,'L');
                $pdf->Cell(20,5,$datum,0,0,'L');
                $pdf->Cell(15,5,$vervaldatum,0,0,'L');

                $pdf->ln(10);
		$pdf->Cell(10,5,'Nr.',0,0,'C');
		$pdf->Cell(15,5,'',0,0,'C');
		$pdf->Cell(100,5,'Omschrijving',0,0,'C');
		$pdf->Cell(20,5,'Aantal',0,0,'C');
		$pdf->Cell(15,5,'Eenheid',0,0,'C');
                if (!$noPrice) {
                    $pdf->Cell(20,5,'Eenheidsprijs',0,0,'C');
                    $pdf->Cell(17,5,'Totaal',0,1,'C');
                }
		$pdf->ln(5);

    }

    private function printTotaal($pdf, $order)
    {
                $pdf->SetFont('helvetica','',12);
                $btwpercentage=!empty($order['BTWPercentage'])?" ".$order['BTWPercentage']."%":"";

                $pdf->text(54,237,'Excl.BTW');
                $pdf->text(81,237,'BTW'.$btwpercentage);
                $pdf->text(118,237,'Incl.BTW');

                $pdf->SetFont('helvetica','U',12);
                $pdf->text(160,237,'Te Betalen');
                $pdf->SetFont('helvetica','',12);



                $pdf->text(52,244,number_format($order['TotaalprijsExclBTW'],2,",","."));
                $pdf->text(82,244,number_format($order['BTW'],2,",","."));
                $pdf->text(117,244,number_format($order['TotaalprijsInclBTW'],2,",","."));
                 $euro = $pdf->unichr(8364);
                 $pdf->text(174,244,$euro.number_format($order['TotaalprijsInclBTW'],2,",","."));

                 $pdf->Line(45, 236, 140, 236);
                 $pdf->Line(45, 242, 140, 242);
                 $pdf->Line(45, 250, 140, 250);

                 $pdf->Line(45, 236, 45, 250);
                 $pdf->Line(75, 236, 75, 250);
                 $pdf->Line(105, 236, 105, 250);
                 $pdf->Line(140, 236, 140, 250);

                 $pdf->Line(160, 236, 200, 236);
                 $pdf->Line(160, 250, 200, 250);

                 $pdf->Line(160, 236, 160, 250);
                 $pdf->Line(200, 236, 200, 250);

                 $pdf->SetFont('helvetica','',8);
                 if ($order['BTW']==0 && $order['BTWMedecontractant']) {
                     $pdf->text(23,230,"MEDECONTRACTANT-BTW VERLEGD");
                 }
                 if ($order['ID_Type']<3) {
                    $pdf->text(23,252,"Geldigheidsduur offerte: 2 maand. Onder voorbehoud van opmeting en situatie ter plaatse.  Deze offerte wordt U in het dubbel opgestuurd.");
                    $pdf->text(23,256,"In geval van akkoord vragen wij U beleefd bijgaand dubbel getekend te willen terugsturen.");
                    $pdf->text(23,260,"Steeds tot uw dienst voor alle verdere inlichtingen en in de hoop U gunstig te mogen lezen verblijven wij,");

                    $pdf->text(150,263,"Hoogachtend,");
                    $pdf->text(150,267,"Keppens Patrick");
                 }
                   /*$pdf->Line(20, 70, 125, 70);
                 $pdf->Line(20, 70, 20, 82);
                 $pdf->Line(125, 70, 125, 82);
                 $pdf->Line(20, 82, 125, 82);*/
                 $pdf->SetFont('helvetica','',8);
    }
    private function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));

        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        if (strpos($corners, '2')===false)
            $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        if (strpos($corners, '3')===false)
            $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        if (strpos($corners, '4')===false)
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        if (strpos($corners, '1')===false)
        {
            $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k ));
            $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }


    private function printFooter($pdf)
    {
                $pdf->SetFont('helvetica','',8); 
		$pdf->text(10,274,'BVBA KEPPENS PATRICK');
                $pdf->text(10,277,'Nijverheidsstraat 14');
                $pdf->text(10,280,'8740 Pittem');

                $pdf->text(10,284,'www.dakwerkenkeppens.be');
                $pdf->text(10,287,'info@dakwerkenkeppens.be');

                $pdf->text(50,275,'Tel. 051/31.88.16');
                $pdf->text(50,280,'Fax  051/30.29.08');
                $pdf->text(50,285,'Gsm  0474/49.03.99');

              
                $pdf->SetFont('helvetica','',9);
                $pdf->text(78,275,'DEXIA 068-2375454-48');
                $pdf->text(78,280,'IBAN BE 11 0682 3754 5448');
                $pdf->text(78,285,'BIC  GKCCBEBB');
                
                $pdf->text(123,275,'KBC 738-0340785-46');
                $pdf->text(123,280,'IBAN BE 33 7380 3407 8546');
                $pdf->text(123,285,'BIC KREDBEBB');

                $pdf->SetFont('helvetica','',8);
                $pdf->text(170,275,'BTW BE0479.306.197');
                $pdf->text(170,280,'Reg.Nr. 05.25.1.1');
                $pdf->text(170,285,'RPR Brugge');

                 $pdf->Line(5, 272, 205, 272);
                 $pdf->Line(50, 272, 50, 292);
                 $pdf->Line(78, 272, 78, 292);
                 //$pdf->Line(128, 272, 128, 292);
                 $pdf->Line(168, 272, 168, 292);
		/*$pdf->text(10,265,$msg[2][2]);
		$pdf->text(10,270,$msg[2][3]);*/
    	
    	$pdf->SetFont('helvetica','',8);
    }


}

