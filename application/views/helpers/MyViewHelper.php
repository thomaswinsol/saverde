<?php

/**
 * MyHelper helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_myViewHelper extends Zend_View_Helper_Abstract
{
	
	public $view;
	
	
	/**
	 * setView
	 * @see Zend_View_Helper_Abstract::setView()
	 * 
	 * If a helper class has a setView() method, it will be called when the helper class
	 * is first instantiated, and passed to the current view object. 
	 */
	public function setView(Zend_View_Interface $view){
		$this->view = $view;
	}
	
	

/**
 * Workaround to call multiple methods 
 * @param string $method
 * @param array $args
 */	
public function myViewHelper($method,$args=NULL)
 {
	 $thisClass    = get_class();
     $classMethods = get_class_methods($thisClass);
     // case the method exists into this class  //
     if(in_array($method,$classMethods))
     {
        $arrCaller = array($thisClass,$method);
        return call_user_func_array($arrCaller, $args );
     }
	 //method not found in this class
     	throw new Exception("Method ".$method." doesn't exist in class " .$thisClass."." ); 	
 }
	
/**
* Shows a dialog with the given text
*
* @param	str		text
* @param	str		icon type
* @return	void
* @access	public
*/
	public function getDialog($_type,$_txt)
	{
		// allowed types
			$allowed_types_arr = array("ok","error","warning","question","info","i","time","user");
		
		// if type exists show icon
		//$img = '';
		$img_src = '';
		//$_txt2 = nl2br(htmlentities_deep($_txt));
		$_txt2 = $_txt;
		$_linkIco = '/images/ico/';
		$_pathIco = realpath('/images/ico/');
		//$this->getHelper()
			if (in_array($_type,$allowed_types_arr))
				{
					$img_src = $_linkIco."24/dialog_".$_type.'.png';
					if (!file_exists($_pathIco."24/dialog_".$_type.'.png')){
						$img_src = $_linkIco."24/dialog_".$_type.'.gif';
					}
				}
		return '<div class="dialog" style="background-image:url('.$img_src.');">'.'
		<p>'.$_txt2.'</p></div>';
		}	

public function getTest($i,$j)
{
	return $i.$j.'<br />Nice job... dit werkt amai!';
}
		
/**
* Replace text
* Param is an array, key = search; value = replace
* @param array
* @param string : string that needs replacement
* @return string : new string, with replacements
* @access public
*/
public function replaceText($_replace_arr,$_text){
	if (!is_array($_replace_arr) || !count($_replace_arr)){
		return $_text;
	}
	$newText = $_text;
	foreach($_replace_arr as $k=>$v){
			$newText = str_replace($k,$v,$newText);
	}
	return $newText;
} //end function	



}

