<?php
class Application_Model_Product extends My_Model
{
    protected $_name = 'product'; //table name
    protected $_id   = 'ID'; //primary key

     public function getProducten($locale=null, $status=null, $vertaald=null, $data=null)
    {
            $sql = $this->db
            ->select()
            ->from(array('a' => 'product'), array('ID', 'label', 'status' , 'prijs' ,  'homepagina') )
            ->join(array('b' => 'productlocale'), ' a.ID = b.IDProduct  ', array('titel','teaser','omschrijving','vertaald', 'locale') );

        If (!empty($locale)) {
            $sql->where ('locale = '."'".$locale."'");
        }
        If (!empty($status)) {
            $sql->where ('status = '.$status);
        }
        If (!empty($vertaald)) {
            $sql->where ('vertaald = '.(int)$vertaald);
        }
        if (!empty($data['Categorie'])){
            $sql->join(array('c' => 'categorieproduct'), ' b.IDProduct = c.IDProduct  ', array('c.IDCategorie') );
            $sql->where ('c.IDCategorie = '. $data['Categorie'] );
        }
        if (!empty($data['Label'])){
            $sql->where ('Label like '."'%".trim($data['Label'])."%'");
        }
        if (!empty($data['Titel'])){
            $sql->where ('b.Titel like '."'%".trim($data['Titel'])."%'");
        }

        $data = $this->db->fetchAll($sql);
        return $data;
    }

}