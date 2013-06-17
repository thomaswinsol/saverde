<?php
class GebruikerController extends My_Controller_Action
{   
    public function loginAction()
    {
	$this->_helper->layout->disableLayout();   
        $form = new Application_Form_Signup();
        if (!$this->getRequest()->isPost())
        {
        	$this->view->data = $this->getRequest()->getParams(); 
                return false;
        }
        
        $this->_helper->viewRenderer->setNoRender();

        $formData = $values = $this->_request->getPost();
        if (!$form->isValid($formData))
        {
            $this->flashMessenger->setNamespace('Errors');
            $this->flashMessenger->addMessage('-Invalid user or password');
            $this->_helper->redirector('home', 'index');
        }

        $adapter = new Zend_Auth_Adapter_DbTable(
            Zend_Db_Table::getDefaultAdapter() // set earlier in Bootstrap
        );
       
        $adapter->setTableName('gebruiker'); 
        $adapter->setIdentityColumn('email'); 
        $adapter->setCredentialColumn('paswoord'); 
        $adapter->setIdentity($values['email']);
        $adapter->setCredential($values['paswoord']); 
        //$adapter->setCredentialTreatment('md5(?) AND status = 1');
        $adapter->setCredentialTreatment('? AND status = 1');
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter); 
       
        // authentication OK
        if ($result->isValid())
        { //auth OK
            $auth->getStorage()
                ->write($adapter->getResultRowObject(null, "password"));
            $identity = $adapter->getResultRowObject();           
        } else
        {        
            $this->flashMessenger->setNamespace('Errors');
            $this->flashMessenger->addMessage('-Invalid user or password');
        }
        $this->_helper->redirector('home', 'index');
    }

    public function registerAction()
    {
        $this->flashMessenger->setNamespace('Errors');
        $this->view->flashMessenger = $this->flashMessenger->getMessages();
         $form = new Application_Form_Registreer;
         $this->view->form = $form;
         if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();
            if (!$form->isValid($postParams)) {
                return;
            }
            $formData  = $this->_request->getPost();
            
            $this->flashMessenger->setNamespace('Errors');
            $tr= Zend_Registry::get('Zend_Translate');
            $this->flashMessenger->addMessage($tr->translate('txtRegisterEmail'));
            $this->_helper->redirector('home', 'index');
        }

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

      
}