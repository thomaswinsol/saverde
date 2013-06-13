<?php
class Application_Model_Klant extends My_Model 
{
    protected $_name = 'klanten'; //table name
    protected $_id = 'ID'; //primary key
    /*protected $_dependentTables = array('dealers','talen','orders', 'aanspreektitels');*/    
    protected $enableDataGrid = TRUE;
        
    public function save($data,$id = NULL)
    {
    	//ini
    		$currentTime =  date("Y-m-d H:i:s", time());
        	$isUpdate = FALSE;
        //VAT
        	/*$vat_countryCode = isset($data['BTWNummer']['countryCode']) ? $data['BTWNummer']['countryCode'] : '';
		$vat_number      = isset($data['BTWNummer']['vatNumber']) ? $data['BTWNummer']['vatNumber'] : '';        	
        	$vat = '';
        	if (!empty($vat_countryCode) && !empty($vat_number)){
        		$vat = $vat_countryCode . ' ' . $vat_number;
        	}*/

        $dbFields = array(
        	'ID_Land'             => (int)$data['ID_Land'],
        	'ID_Taal'             => (int)$data['ID_Taal'],
                'ID_Betvw'            => (int)$data['ID_Betvw'],
                'ID_BTW'              => (int)$data['ID_BTW'],
                'ID_Aanspreektitel'   => (int)$data['ID_Aanspreektitel'],
        	'Naam'                => $data['Naam'],
        	'Straat'              => $data['Straat'],
        	'Postcode'            => $data['Postcode'],
        	'Gemeente'            => $data['Gemeente'],
        	'Emailadres'          => $data['Emailadres'],
        	'Telefoon'            => $data['Telefoon'],
        	'Fax'                 => $data['Fax'],
        	'GSM'                 => $data['GSM'],
                'BTWNummer'           => $data['BTWNummer'],
                'Opmerking'           => $data['Opmerking'],
                'IsDeleted'           => 0,
        );
        
        if (!empty($id)) {
        	$isUpdate = TRUE;
        	$this->update($dbFields,$id);
        	return $id;
        }
        $dbFields['creationDate'] = $currentTime;
        return $this->insert($dbFields);        
    }

    public function import($data)
    {

         //$data[15] = str_replace("/","-",$data[15]);
         $explode  = explode("/",$data[15]);
         $datum= substr($explode[2],0,4)."-".$explode[1]."-".$explode[0];

         $dbFields = array(
                'ID'                  => (int)$data[0],
        	'ID_Land'             => 1,
        	'ID_Taal'             => 1,
                'ID_Betvw'            => 1,
                'ID_Aanspreektitel'   => (int)$data[1],
        	'Naam'                => $data[1],
        	'Straat'              => $data[2],
        	'Postcode'            => $data[3],
        	'Gemeente'            => $data[4],
        	'Emailadres'          => $data[8],
        	'Telefoon'            => $data[5],
        	'Fax'                 => $data[6],
        	'GSM'                 => $data[7],
                'BTWNummer'           => $data[13],
                'Opmerking'           => "",
                'IsDeleted'           => 0,
                'creationDate'        => $datum,
        );
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
    
     public function buildDataGrid() {
     	/*
     	//examples
     	1. array
   		   $source_array = array(
			array('Alex2', '12', 'M'),
			array('David', '1', 'M'),
			array('Emma', '2', 'F'),
			array('Jessica', '3', 'F'),
			array('Richard', '3', 'M'),
			array('Lucas', '3', 'M')
		   );
		   $source = new Bvb_Grid_Source_Array($source_array, array('name', 'age', 'sex')); 
        2. select
           $select = $this->db
                                          ->select('username')
                                          ->from('dealergebruikers')
                                          ->order('username')
                                         ;
    	   $source = new Bvb_Grid_Source_Zend_Select($select);
         3. table
           $source = new Bvb_Grid_Source_Zend_Table($this);
  		*/
     	//1. build source
                $select = $this->db
                                          ->select('ID')
                                          ->from('klanten')
                                          ->where('IsDeleted=0')
                                          /*->join(array('T' => 'talen'), 't.ID = ID_Taal', array('Taal' => 'Omschrijving'))
                                          ->join(array('A' => 'aanspreektitels'), 'a.ID = ID_Aanspreektitel', array('Titel' => 'Omschrijving'))*/
                                          ->order('CreationDate desc')
                                         ;
        	$source = new Bvb_Grid_Source_Zend_Select($select);

                   
                   
    		$this->dataGrid->setSource($source);
    
    	//2. specify columns
 		$this->dataGrid->setTableGridColumns(array('ID_Status', 'ID','Naam','Postcode','Straat',  'Gemeente', 'Telefoon' , 'GSM', 'Emailadres', 'BTWNummer', 'CreationDate' ));
                $this->dataGrid->updateColumn('ID_Status',array('title'=>'', 'position' => '10','style' => 'width:10px;text-align:center;','decorator' => '<a href="/klant/wijzigen/id/{{ID}}"><img src="/images/edit.png" title="wijzigen klant"></a>'));
                $this->dataGrid->updateColumn('ID',array('position' => '20','style' => 'width:10px;text-align:center;','decorator' => '<a href="/klant/wijzigen/id/{{ID}}">{{ID}}</a>'));
        	$this->dataGrid->updateColumn('Naam',array('position' => '30','title'=> 'Naam','decorator' => '<a href="/klant/detail/tab/1/id/{{ID}}">{{Naam}}</a>'));
                $this->dataGrid->updateColumn('Straat',array('position' => '40' ));
                $this->dataGrid->updateColumn('Postcode',array('position' => '50' ,'style' => 'width:10px;'));
                $this->dataGrid->updateColumn('Gemeente',array('position' => '60'));
                $this->dataGrid->updateColumn('Telefoon',array('position' => '70' ,'style' => 'width:10px;' , 'format'=>'FormatterPhone'));
                $this->dataGrid->updateColumn('GSM',array('position' => '80' ,'style' => 'width:10px;' , 'format'=>'FormatterPhone'));
                $this->dataGrid->updateColumn('Emailadres',array('position' => '90' ,'style' => 'width:50px;'));
                $this->dataGrid->updateColumn('BTWNummer',array('position' => '100' ,'style' => 'width:50px;'));
                $this->dataGrid->updateColumn('CreationDate',array('title'=> 'Datum','position' => '110', 'format'=>'date2'));
//			$this->dataGrid->updateColumn('ID_Dealer',array(
//							'title' => 'Dealer',
//							'position' => '20',
//							));   								    	
//			$this->dataGrid->updateColumn('Omschrijving',array(
//								'title' => 'Taal',			
//								'position' => '30',
//		$grid->getTotalRecord();						));
                //$this->dataGrid->setSqlExp(array('Naam'=>array('functions'=>array('COUNT'),'value'=>'Naam')));

  		//$this->dataGrid->setDetailColumns();
                
                $this->dataGrid->setRecordsPerPage(20);
        //3. build form	
			//$form   = new My_FormGrid();
			//$this->dataGrid->setForm($form);         	
        //4. deploy 	
        	return $this->dataGrid->deploy(); 	    	
    }
}

