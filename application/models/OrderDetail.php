<?php
class Application_Model_OrderDetail extends My_Model
{
    protected $_name = 'orderdetail'; //table name
    protected $_id = 'ID'; //primary key
   
    protected $enableDataGrid = False;
        
    public function save($data,$id = NULL)
    {
    	$currentTime =  date("Y-m-d H:i:s", time());
        $isUpdate = FALSE;
        $data['Aantal'] =  trim($data['Aantal'])==''?0:$data['Aantal'];
        $data['Prijs']  =  trim($data['Prijs'])=='' ?0:$data['Prijs'];

        $data['Totaal']= $data['Prijs']*$data['Aantal'];
        $dbFields = array(
                'ID_Order'      => (int)$data['ID_Order'],
        	'Omschrijving'  => $data['Omschrijving'],
                'Aantal'        => $data['Aantal'],
                'Prijs'         => $data['Prijs'],
                'Eenheid'       => $data['Eenheid'],
                'Totaal'        => $data['Totaal'],
                'IsDeleted'     => 0,
        );
        
        if (!empty($id)) {
        	$isUpdate = TRUE;
        	$this->update($dbFields,$id);
        	return $id;
        }
        $dbFields['CreationDate'] = $currentTime;
        return $this->insert($dbFields);        
    }

    public function import($data,$id = NULL)
    {
    	$currentTime =  date("Y-m-d H:i:s", time());
        $isUpdate = FALSE;
        $data[4] = str_replace(",",".",$data[4]);
        $data[6] = str_replace(",",".",$data[6]);
        $data['Aantal'] =  trim($data[4])==''?0:$data[4];
        $data['Prijs']  =  trim($data[6])=='' ?0:$data[6];

        $data['Totaal']= $data['Prijs']*$data['Aantal'];
        $dbFields = array(
                'ID_Order'      => (int)$data[0],
        	'Omschrijving'  => $data[2],
                'Aantal'        => $data['Aantal'],
                'Prijs'         => $data['Prijs'],
                'Eenheid'       => $data[5],
                'Totaal'        => $data['Totaal'],
                'IsDeleted'     => 0,
        );

        $dbFields['CreationDate'] = $currentTime;
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


    public function getAll($where=null, $order=null)
    {
             $where = trim($where). ' AND IsDeleted = 0 ';
	     return parent::getAll($where, $order);
    }
}

