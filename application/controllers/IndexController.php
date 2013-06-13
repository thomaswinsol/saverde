<?php

class IndexController extends My_Controller_Action
{

    public function indexAction()
    {
        // action body

        $this->_helper->redirector('home', 'index');
    }

    public function homeAction()
    {
        // action body
        $form = new Application_Form_ZoekProduct;
                
        if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();
            if (!$form->isValid($postParams)) {
                return;
            }
                $formData  = $this->_request->getPost();
                $this->context['Zoeken']=$formData;
                $this->SaveContext();
        }
        if (!empty($this->context['Zoeken'])) {
            $form->populate( $this->context['Zoeken'] );
        }
        else {
            $this->context['Zoeken']=null;
        }
        $this->view->form = $form;
        $productModel = new Application_Model_Product();
        $this->view->producten=$productModel->getProducten(Zend_Registry::get('Zend_Locale'),1,1,$this->context['Zoeken']);
    }

}





