<?php
class GebruikerController extends My_Controller_Action
{   
	
    /*public function init()
    {
    	parent::init();
    	//override some default error messages
    }*/

    public function loginAction()
    {
	$this->_helper->layout->disableLayout();   
        $form = new Application_Form_Signup();
        if (!$this->getRequest()->isPost())
        {
        	$this->view->data = $this->getRequest()->getParams(); //$this->_getParam('page');
                return false;
        }
        
        $this->_helper->viewRenderer->setNoRender();

        $formData = $values = $this->_request->getPost();
        if (!$form->isValid($formData))
        {
               $formErrors = array('err' => 1); //$form->getErrors(); //
               $this->_helper->redirector('login','gebruiker',$this->getRequest()->getModuleName(),$formErrors);
              //  $this->view->loginForm = $form;
            return;
        }

        $adapter = new Zend_Auth_Adapter_DbTable(
            Zend_Db_Table::getDefaultAdapter() // set earlier in Bootstrap
        );

        //die(md5($values['password']));
        
        $adapter->setTableName('gebruiker'); //table 
        $adapter->setIdentityColumn('email'); //table username col
        $adapter->setCredentialColumn('paswoord'); //table password col
        $adapter->setIdentity($values['email']);//form username
        $adapter->setCredential($values['paswoord']); //form password
        //$adapter->setCredentialTreatment('md5(?) AND status = 1');
        $adapter->setCredentialTreatment('? AND status = 1');
        //die(md5($values['password']));
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter); 
       
        // authentication OK
        if ($result->isValid())
        { //auth OK
            $auth->getStorage()
                ->write($adapter->getResultRowObject(null, "password"));
            $identity = $adapter->getResultRowObject();
            $modelGebruiker = new Application_Model_Gebruiker();
            $result = $modelGebruiker->getOneByField("email", $formData['email']);
            $this->context['ID_Dealer']= $result['ID_Dealer'];
            $this->SaveContext();
            
        } else
        {        
            $this->flashMessenger->setNamespace('Errors');
            $this->flashMessenger->addMessage('Invalid user or password');
            //$this->_helper->redirector('login','gebruiker',false,$formErrors);
            //$this->view->loginForm = $form;
        }
        $this->_helper->redirector('home', 'index');
    }

    
    /**
     * Log out a user
     */
    public function logoutAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('home','index');
    }

    public function SaveContext()
    {
        $_SESSION ['context'] = $this->context;
    }
      
}