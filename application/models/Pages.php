<?php
class Application_Model_Pages extends My_Modeltranslation
{
    protected $_name = 'pages';
    protected $_sName = 'pages_translation.page';
    protected $_id   = 'id';

    protected $lang_fields = array('title', 'teaser', 'content');
 
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


}