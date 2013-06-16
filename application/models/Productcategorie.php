<?php
class Application_Model_Productcategorie extends My_Model
{
    protected $_name = 'product_categorie'; //table name
    protected $_id   = 'id'; //primary key

    public function save($data,$id = NULL)
    {
        $dbFields = array(
                'idcategorie'=> (int)$data['idcategorie'],
                'idproduct'  => (int)$data['idproduct'],
        );
        $this->deleteCategorieByProductId($data['idcategorie'],$data['idproduct']);
        $id = $this->insert($dbFields);
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

    public function deleteCategorieByProductId($id,$productid)
    {
        return parent::delete('idcategorie = '. (int)$id. ' and idproduct='.(int)$productid );
    }


    public function getCategorieForProduct($id=null)
    {
            $sql = $this->db
            ->select()
            ->from(array('a' => 'categorie'), array('id', 'label', 'status') )
            ->join(array('b' => 'product_categorie'), ' a.id = b.idcategorie  ', array('id as b.id', 'idproduct') );

        If (!empty($id)) {
            $sql->where ('b.idproduct = '.$id);
        }
        $data = $this->db->fetchAll($sql);
        return $data;
    }


}