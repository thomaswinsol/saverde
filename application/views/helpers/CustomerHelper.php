<?php
/**
 * Customer helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_CustomerHelper extends Zend_View_Helper_Abstract
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
	public function CustomerHelper($method,$args=NULL)
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
	
	public function customerInfo($data){
		if (empty($data)){
                    return NULL;
		}
		$this->view->setEscape('htmlentities');
		
		$html = '<div class="customerTitle">
		<strong> ' . $this->view->escape($data['Naam']) . '</strong>'
                . '('.
                        "<a href='/klant/wijzigen/id/".$this->view->escape($data['ID'])."'>".
                                $this->view->escape($data['ID']).
                                        "</a>".')'
                . '  -  '
                . $this->view->escape($data['Straat'].' ') . '  -  '
                . $this->view->escape($data['Postcode'] . ' ' . $data['Gemeente'] ) .' -  ';                
                if (!empty($data['Telefoon'])) {
                    $html .= 'Tel. '.$this->view->escape($data['Telefoon']) . ' - ';
                }
                if (trim($data['Emailadres'])<>"") {
                 $html .=
                 'E-mail <A HREF="mailto:'. $this->view->escape($data['Emailadres']).'">'.$this->view->escape($data['Emailadres']) . '</a> '.' -  ';
                }
                if (!empty($data['BTWNummer'])) {
                 $html .=
                 'BTW-nummer '.$this->view->escape($data['BTWNummer']);
                }
                $html .=
                '
                </div>';
                //reset to default
		$this->view->setEscape('htmlspecialchars');
                return $html;
         }
}

