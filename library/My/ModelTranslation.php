<?php
abstract class My_Modeltranslation extends Zend_Db_Table
{

public function formInfo($id){
    $select = new Zend_Db_Table_Select($this);
    $row = $this->fetchRow($select->where('ID = ?', $id));
    $rowArray = $row->toArray();

    if ($row) {
        $rowArray = $this->getLangArray($rowArray);
    }
    // echo '<pre>';
       //  print_r($rowArray);
       //  die("ok");
         return ($rowArray);

   // return $this->buildLangRow($rowArray);
}

function getLangArray($rowArray){
  
    $langRows = $this->_db->fetchAll(
    $this->_db->select()
    ->from($this->getLangTable(), $this->lang_fields)
    ->join('languages',
    $this->getLangTable() . '.language_id = languages.id', 'code')
    ->where($this->_sName . '_id = ?', $rowArray['id']));


    foreach ($langRows as $langRow) {
        foreach ($this->lang_fields as $field) {
            $colName = $field . '_' . $langRow['code'];
            $rowArray[$colName] = $langRow[$field];
        }
    }
    return $rowArray;
}

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

public function getLangTable()
{
    return $this->_name . '_translation';
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