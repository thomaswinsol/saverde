<?php
/**
 * Abstract controller
 * Provides listing, edit and delete functionality
 */
abstract class My_Controller_Action extends Zend_Controller_Action
{
    protected $context;
    protected $baseUrl='/eindwerk/public';
    protected $flashMessenger = NULL;

    public function init()
    {
        
        $defaultNamespace = new Zend_Session_Namespace ();
        if(!array_key_exists('context', $_SESSION))
        {
            $_SESSION['context']=array('username'=>"",'lang'=>"nl_BE",'winkelmand'=>null);
        }
        if (!isset($_SESSION['context']['Firma'])) {
            $firmaModel = new Application_Model_Firma();
            $firma= $firmaModel->getOne(1);
            $_SESSION['context']['Firma']=$firma;            
        }
        $module = $this->getRequest()->getModuleName();
        if (strtolower($module)=="admin") {
           unset($_SESSION['context']['winkelmand']);
           unset($_SESSION['context']['Firma']);
        }
        $this->context = $_SESSION ['context'];        
        $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->flashMessenger->setNamespace('Errors');       
    }    
   

    public function __destruct()
    {
        $this->SaveContext ();
    }

    public function SaveContext()
    {
        $_SESSION ['context'] = $this->context;
    }

    /*public function IsAllowed($resource) {
        $registry = Zend_Registry::getInstance();
        $acl = $registry->get('Zend_Acl');
        if (!$acl->IsAllowed($this->context['userrole'],$resource )){
            return false;
        }
        return true;
    }*/
   
}
