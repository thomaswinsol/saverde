<?php
class Application_Model_Gebruiker extends My_Model
{
    protected $_name = 'gebruiker'; //table name
    protected $_id   = 'ID'; //primary key

    public function register($data,$id = NULL)
    {
        $dbFields = array(
        	'IDGebruiker'       => $data['userID'],
                'datumbestelling'   => $currentTime,
                'leveringsadres'    => "xxxx",
        );

        return $this->insert($dbFields);
    }

    /**
     * Insert
     * @return int last insert ID
     */
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
    


}