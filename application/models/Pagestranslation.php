<?php
class Application_Model_Pagestranslation extends My_Model
{
    protected $_name = 'pages_translation'; //table name
    protected $_id   = 'id'; //primary key

    
    public function save($data,$id = NULL)
    {
    	//ini
    	$currentTime =  date("Y-m-d H:i:s", time());
        $dbFields = array(
        	'page_id'       => $data['page_id'],
                'language_id'   => $data['language_id'],
                'title'         => $data['title'],
                'teaser'        => $data['teaser'],
                'content'       => $data['content'],
                'translated'    => $data['translated']
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