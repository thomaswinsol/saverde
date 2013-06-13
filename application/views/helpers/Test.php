<?php
class Zend_View_Helper_Test
{
	public function test()
	{
		return '2/ ******** view helper OK **********';
		$fc = Zend_Controller_Front::getInstance();
 		return $fc->getBaseUrl();
 	}
 	
	public function test2()
	{
		return ' ******** test 2 OK **********';
		$fc = Zend_Controller_Front::getInstance();
 		return $fc->getBaseUrl();
 	}
 	
 	public function getFormValue($_data,$_field,$_safe=true)
 	{
 	    if (isset($_data[$_field]))
 	    {
 	        return $_safe?htmlspecialchars($_data[$_field]):$_data[$_field];
 	    }
 	    return '';
 	}
 	
public function getTest()
{
	return 'dit werkt é!';
} 	
}