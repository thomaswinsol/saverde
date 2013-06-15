<?php
class Application_Model_Pages extends My_Model
{
    protected $_name = 'pages';
    protected $_sName = 'pages_translation.page';
    protected $_id   = 'id';

    protected $lang_fields = array('title', 'teaser', 'content');

    public function save($data,$id = NULL)
    {
    	//ini
    	$currentTime =  date("Y-m-d H:i:s", time());
        $dbFields = array(
        	'label'      => $data['label'],
                'status'     => (int)$data['status'],
        );
        $id = $this->insert($dbFields);

        $pagestranslationModel = new Application_Model_Pagestranslation();
        foreach ($data['translation'] as $key => $value) {
            $translated= !empty($value['title'])?1:0;
            $dbFields=array(
                "page_id"=> $id,
                "language_id"=>$key,
                "title"=>$value['title'],
                "teaser"=>$value['teaser'],
                "content"=>$value['content'],
                "translated"=>$translated
            );
            $pagestranslationModel->save($dbFields);
        }
        
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


    public function getLangFields()
    {
        return $this->lang_fields;
    }


     public function getPage($where=NULL){
        $page = parent::getAll($where);

	$matches = array();
        foreach ( $page as $f ) {
        		$p['value'] = trim($f['id']);
                        if ($f['status']) {
                            $p['label'] = trim($f['label']);
                        } else {
                            $p['label'] = "<span style='text-decoration:line-through;'>".trim($f['label'])."</span>";
                        }
                        
			$matches[] = $p;
        }
        return $matches;
     }


}