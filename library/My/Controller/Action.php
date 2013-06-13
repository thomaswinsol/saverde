<?php
/**
 * Abstract controller
 * Provides listing, edit and delete functionality
 */
abstract class My_Controller_Action extends Zend_Controller_Action
{
    public $context;
    public $aantalafbeeldingperrij=3;

    public function init()
    {
        $defaultNamespace = new Zend_Session_Namespace ();
        if(!array_key_exists('context', $_SESSION))
        {
            $_SESSION['context']=array('username'=>"",'lang'=>"nl_BE");
        }
        if (!isset($_SESSION['context']['Firma'])) {
            $firmaModel = new Application_Model_Firma();
            $firma= $firmaModel->getOne(1);
            $_SESSION['context']['Firma']=$firma;
            
        }
        $this->context = $_SESSION ['context'];
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
