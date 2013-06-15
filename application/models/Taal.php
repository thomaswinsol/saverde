<?php
class Application_Model_Taal extends Zend_Db_Table
{
protected $_name = 'taal';
protected $_list = 'taal';

public function init()
{
$this->_list = $this->fetchAll();
}

public function getList()
{
return $this->_list;
}

public function findByCode($code)
{
$lang = $this->fetchRow($this->select()->where('code = ?', $code));
return $lang;
}

public function listArray($key = 'id')
{
$languages = $this->getList();
$return = array();
foreach ($languages as $language) {
$return[$language->$key] = $language->name;
}
return $return;
}

static public function instance()
{
static $instance;
if (!($instance instanceof Languages)) {
$instance = new Application_Model_Taal();
$instance->init();
}
return $instance;
}
}
?>
