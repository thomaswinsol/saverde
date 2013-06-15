<?php
abstract class My_Modeltranslation extends Zend_Db_Table
{


function buildLangRow($array) {
    $rowClass = $this->getRowClass();
    return new $rowClass(array('table' => $this, 'data' => $array, 'stored' => true));
}

public function getInfo($id){
    $row = $this->fetchRow($this->select()->where('id = ?', $id));
    return $row;
}

public function select()
{
    $select = new Zend_Db_Table_Select($this);
    $select->setIntegrityCheck(false)
    ->from($this->_name)
    ->join($this->getLangTable(),
    "{$this->_name}.id = {$this->getLangTable()}.{$this->_sName}_id",
    $this->lang_fields)
    ->where($this->getLangTable() . '.language_id = ?', Zend_Registry::get('language')->id);
    return $select;
}



public function countRecords($where = false)
{
    $select = new Zend_Db_Table_Select($this);
    $select->setIntegrityCheck(false)
    ->from($this->_name,'COUNT(*) AS num');

    if($where != false) {
        $select->where($where);
    }

    $row = $this->fetchRow($select);
    return $row->num;
}






}
?>