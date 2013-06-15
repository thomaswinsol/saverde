<?php
abstract class My_Model extends Zend_Db_Table_Abstract
{
    protected $errors = array();    
    public $db;    
    protected $dataGrid;
    protected $enableDataGrid = FALSE;
    
 // -----------------------------------------
    public function init()
    {
    	$this->db = $this->getAdapter();
        if ($this->enableDataGrid){
        	$dataGrid       = new My_DataGrid();
        	$this->dataGrid = $dataGrid->getGrid();
        }
    }

    public function __construct($config = array())
    {
        parent::__construct($config);
    }    
    
 // -------------------------
 // CRUD
    public function getOne($id,$colName = 'ID')
    {
        $row = parent::fetchRow($colName. ' = ' .(int)$id);
        if (!$row) {
            return FALSE; 
        }
        return $row->toArray();
    }

    public function getRecordcount($where = null)
    {
        $data = $this->fetchAll($where)->count();
        return $data;
    }

    public function getOneByField($fieldName,$fieldValue){
    	$row = parent::fetchRow($fieldName .' = ' .$this->db->quote($fieldValue));            
        if (!$row) {
            return FALSE; 
        }
        return $row->toArray();    	
    }
    
    public function getOneByFields(array $fields,$operator = 'AND'){
    	$where = '0 = 0'; //dummy
    	foreach($fields as $k=>$v){
    		$where .= ' '. $operator . ' ' . $k . '=' . $this->db->quote($v);
    	}
    	$row = parent::fetchRow($where);            
        if (!$row) {
            return FALSE; 
        }
        return $row->toArray();    	
    }    
    
    public function getAll($where=null,$order=null)
    {
    	$data = $this->fetchAll($where,$order);
        return $data->toArray();
    }


    /**
     * 
     * Delete by id
     * @param mixed array|integer $id
     * @param string $primaryKey : name of primary key, default id specified in model
     */
    public function deleteById($id,$primaryKey = '')
    {
       $primaryKey = !empty($primaryKey) ? $primaryKey : $this->_id;
       if (!is_array($id)){
       		$id = array((int)$id);       	
       }
       if (empty($id)){
       		return FALSE;
       }
       parent::delete($primaryKey . ' IN (' . implode(',',$id) . ')');
    }
    

    public function buildSelect($options = NULL){
    	$defaultOptions = array(
    		'key'      => $this->_id,
    		'value'    => 'Omschrijving',
    		'emptyRow' => TRUE,
    	);
   		$options = !empty($options) && is_array($options) ? array_merge($defaultOptions,(array)$options) : $defaultOptions;
    	$data = $this->getAll();
    	if (empty($data)){
    		return array();
    	}
    	$returnData = array();
    	if ($options['emptyRow']){
    		$returnData[''] = '';
    	}
    	foreach($data as $row){
    		$returnData[$row[$options['key']]] = $row[$options['value']];
    	}    	
    	return $returnData;
    }   
    
    public function buildSelectFromArray($data = array(),$options = NULL){
    	$defaultOptions = array(
    		'key'      => $this->_id,
    		'value'    => 'Omschrijving',
    		'emptyRow' => TRUE,
    	);
   		$options = !empty($options) && is_array($options) ? array_merge($defaultOptions,(array)$options) : $defaultOptions;
    	//$data = $this->getAll();
    	if (empty($data)){
    		return array();
    	}
    	$returnData = array();
    	if ($options['emptyRow']){
    		$returnData[''] = '';
    	}
    	foreach($data as $row){
    		$returnData[$row[$options['key']]] = $row[$options['value']];
    	}    	
    	return $returnData;
    }      
  	
 // -------------------------   
    public function getTable()
    {    
    	return $this->_name;
    }
 
    public function fetchSearchResults($keyword)
    {
        $result = $this->getTable()->fetchSearchResults($keyword);
        return $result;
    } 

    public function SplitDataAndTranslation(array $insertData)
    {
        list($data, $langData) = $this->separateLangRows($insertData);
        $data['translation']=$langData;
        return $data;
    }

    private function separateLangRows( array $data) {
        $taalModel = new Application_Model_Taal;
        $languages = $taalModel->getTaal();
        $langData = array();
        foreach ($this->lang_fields as $field) {
            foreach ($languages as $key => $value) {
                $colName = $field . '_' . $value;
                if (isset($data[$colName])) {
                    $langData[$key][$field] = $data[$colName];
                    unset($data[$colName]);
                }
            }
        }
        return array($data, $langData);
    }
    

    public function GetDataAndTranslation($id){
        $select = new Zend_Db_Table_Select($this);
        $row = $this->fetchRow($select->where('ID = ?', $id));
        $rowArray = $row->toArray();
        if ($row) {
            $rowArray = $this->getLangArray($rowArray);
        }
        return ($rowArray);
    }

    function getLangArray($rowArray){
        $langRows = $this->_db->fetchAll(
        $this->_db->select()
        ->from($this->getLangTable(), $this->lang_fields)
        ->join('taal', $this->getLangTable() . '.taal_id = taal.id', 'code')
             ->where($this->_sName . '_id = ?', $rowArray['id']));
          
        foreach ($langRows as $langRow) {
            foreach ($this->lang_fields as $field) {
                $colName = $field . '_' . $langRow['code'];
                $rowArray[$colName] = $langRow[$field];
            }
        }
        return $rowArray;
    }

    public function getLangTable()
    {
        return $this->_name . '_vertaling';
    }

    /**
     * Check on errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        if (!empty($this->errors)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Get errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set 1 error message
     *
     * @param string $msg
     */
    public function addError($msg)
    {
        if (!empty($msg)) {
            $this->errors = (string) $msg;
        }
    }

    /**
     * Set error messages
     *
     * @param array $msg
     */
    public function addErrors($msg)
    {
        if (!empty($msg) && is_array($msg)) {
            $this->errors = array_merge($this->errors, $msg);
        }
    }

    
    /**
     * Checks if 2 arrays are equal
     * @param array $a, array 1
     * @param array $b, array 2
     * @param bool $strict, true if you want to type check
     */
    function array_equal($a, $b, $strict = FALSE)
    {
        if (count($a) !== count($b)) {
            return FALSE;
        }   
        sort($a);
        sort($b);
        return ($strict && $a === $b) || $a == $b;
    }
    
   

}
