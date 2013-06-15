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

public function delete($where)
{
    $tableSpec = ($this->_schema ? $this->_schema . '.' : ')' . $this->_name);

    $rows = $this->_db->fetchAll($this->_db->select()
        ->from($this->_name, array('id'))
        ->where(implode(' && ', $where)));
        $this->_db->delete($tableSpec, $where);

    $tableSpec = ($this->_schema ? $this->_schema . '.' : ')' . $this->getLangTable());
    foreach ($rows as $row) {
        $newWhere[] = $this->_db->quoteInto('{$this->getLangTable()}.{$this->_sName}_id = ?', $row['id'], 'INT(11)');
    }
    $this->_db->delete($tableSpec, $newWhere);

    return;
}

/**
* Inserts a new row.
*
* @param array $data Column-value pairs.
* @return mixed The primary key of the row inserted.
*/
public function insert(array $insertData)
{
    list($data, $langData) = $this->separateLangRows($insertData);

    echo '<pre>';
    print_r($data);
    echo '<hr>';
    print_r($langData);
    die("ok");
    
    if (is_array($data) && count($data)>0) {
        $tableSpec = ($this->_schema ? $this->_schema . '.' : ')' . $this->_name);
        $this->_db->insert($tableSpec, $data);
        $insertData['id'] = $this->_db->lastInsertId();
    }

    if (is_array($langData) && count($langData)>0) {
        $tableSpec = ($this->_schema ? $this->_schema . '.' : ')' . $this->getLangTable());

        foreach ($langData as $language_id=>$data) {
            $data[$this->_sName . '_id'] = $insertData['id'];
            $data['language_id'] = $language_id;
            $this->_db->insert($tableSpec, $data);
        }
    }

    return $this->buildLangRow($insertData);
}

/**
* Updates existing rows.
*
* @param array $data Column-value pairs.
* @param array|string $where An SQL WHERE clause, or an array of SQL WHERE clauses.
* @return int The number of rows updated.
*/
public function update(array $data, $where)
{
    list($data, $langData) = $this->separateLangRows($data);

    if (is_array($data) && count($data)>0) {
        $tableSpec = ($this->_schema ? $this->_schema . '.' : ')' . $this->_name);
        $result = $this->_db->update($tableSpec, $data, $where);
    }

    if (is_array($langData) && count($langData)>0) {
        $tableSpec = ($this->_schema ? $this->_schema . '.' : ')' . $this->getLangTable());
        $db = $this->getAdapter();

        $where[0] = str_replace($this->_name, $this->_sName, $where[0]);
        $where[0] = str_replace('`.`', '_', $where[0]);

        foreach ($langData as $language_id=>$data) {
            $where[1] = $db->quoteInto('{$tableSpec}.language_id = ?', $language_id, 'int(11)');
            $this->_db->update($tableSpec, $data, $where);
        }
    }

    return;
}

private function separateLangRows( array $data) {
    $languages = Application_Model_Languages::instance()->getList();
    $langData = array();

    foreach ($this->lang_fields as $field) {
        foreach ($languages as $language) {
        $colName = $field . '_' . $language->code;
            if (isset($data[$colName])) {
                $langData[$language->id][$field] = $data[$colName];
                unset($data[$colName]);
            }
        }
    }

    return array($data, $langData);
}

}
?>