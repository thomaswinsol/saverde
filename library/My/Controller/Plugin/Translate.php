<?php
/**
 * 
 * Select language
 *
 */
class My_Controller_Plugin_Translate extends Zend_Controller_Plugin_Abstract
{   
	
    //protected $auth;
    protected $languages = array('nl' => 1, 'fr' => 2);
    private $_excludeActions = array('product'  => array('ajax-calculate-price'),);


    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {

        /*if(!Zend_Auth::getInstance()->hasIdentity()) {
            return;
        }*/
        //$this->auth = Zend_Auth::getInstance()->getIdentity();

        $lang_default = 'nl';
        $locale_default = 'nl_BE';

        $session = new Zend_Session_Namespace('translation');
        if (isset($session->language) && !empty($session->language)) {
            $langValue   = strtolower( substr($session->language,0,2));
            $localeValue = $session->language;
        }
        else {
            //retrieve lang selection from user
            //@todo, locale can also be retrieved from database, low low low priority
            $langValue   = $lang_default;
            $localeValue = $locale_default;
        }

        $locale = new Zend_Locale($localeValue);
        Zend_Registry::set('Zend_Locale', $locale);


        $translator = new Zend_Translate(
                'csv',
                APPLICATION_PATH . '/configs/lang/' . $langValue . '.csv',
                $langValue
        );
 
        $controllerName = $request->getControllerName();
        $actionName     = $request->getActionName();
        if (array_key_exists($controllerName,$this->_excludeActions)
            && in_array($actionName,$this->_excludeActions[$controllerName])) {
            $translator_db = null;
        } 

        Zend_Registry::set('Zend_Translate', $translator);

    }
    
}