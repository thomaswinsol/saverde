<?php
class Application_Model_Product extends My_Model
{
    protected $_name = 'product'; //table name
    protected $_sName = 'product_vertaling.product';
    protected $_id    = 'id';
    protected $model_fields = array('dec_eenheidsprijs','sel_homepagina');
    protected $lang_fields = array('titel', 'teaser', 'inhoud');

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


    /**
     *
     * Delete by id
     * @param mixed array|integer $id
     * @param string $primaryKey : name of primary key, default id specified in model
     */
    public function getProduct($locale=null, $status=null, $id=null)
    {
        $sql = $this->db
        ->select()
        ->from(array('a' => 'product'), array('ID', 'label', 'status' , 'prijs' ,  'homepagina') )
        ->joininner(array('b' => 'productlocale'), ' a.ID = b.IDProduct  ', array('titel','teaser','omschrijving','vertaald', 'locale') );

        If (!empty($locale)) {
            $sql->where ('locale = '."'".$locale."'");
        }
        If (!empty($status)) {
            $sql->where ('status = '.$status);
        }

        $sql->where ('a.ID = '.(int)$id);

        $data = $this->db->fetchRow($sql);

        return $data;
    }

    public function getLangFields()
    {
        return $this->lang_fields;
    }

    public function getModelFields()
    {
        return $this->model_fields;
    }

    public function save($data,$id = NULL)
    {
    	$currentTime =  date("Y-m-d H:i:s", time());
        $isUpdate = FALSE;
        $dbFields = array(
        	'label'      => $data['label'],
                'status'     => (int)$data['status'],
        );

        if (!empty($id)) {
        	$isUpdate = TRUE;
        	$this->update($dbFields,$id);
                $this->savetranslation($data, $id);
        	return $id;
        }
        $id = $this->insert($dbFields);
        $this->savetranslation($data, $id);
    }

    public function savetranslation($data,$id = NULL)
    {
        $vertalingModel = new Application_Model_Productvertaling();
        $vertalingModel->deleteById($id, "product_id");
        foreach ($data['translation'] as $key => $value) {
            $translated= !empty($value['titel'])?1:0;
            $dbFields=array(
                "product_id"   => $id,
                "taal_id"     => $key,
                "titel"       => trim($value['titel']),
                "teaser"      => trim($value['teaser']),
                "inhoud"      => trim($value['inhoud']),
                "vertaald"    => $translated
            );
            $vertalingModel->save($dbFields);
        }
    }

    public function getAutocomplete($where=NULL){
        $product = parent::getAll($where);

	$matches = array();
        foreach ( $product as $p ) {
                        $p['id']  =trim($p['id']);
        		$p['naam']=trim($p['label']);
        		$p['value'] = trim($p['id']);
                        if ($p['status']) {
                            $p['label'] = trim($p['label']);
                        } else {
                            $p['label'] = "<span style='text-decoration:line-through;'>".trim($p['label'])."</span>";
                        }
			$matches[] = $p;
        }
        return $matches;
     }
}