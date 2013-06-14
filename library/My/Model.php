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

    public function getOneLocale($id,$colName = 'ID')
    {
        $row = parent::fetchRow($colName. ' = ' .(int)$id);
        if (!$row) {
            return FALSE;
        }
        return $row->toArray();
        var_dump($this->getTable());
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
