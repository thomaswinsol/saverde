<?php
class Application_Model_Languages extends Zend_Db_Table
{
protected $_name = 'languages';
protected $_list = 'languages';

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
$instance = new Application_Model_Languages();
$instance->init();
}
return $instance;
}
}
?>
