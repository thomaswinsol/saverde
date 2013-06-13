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
        $this->flashMessenger->setNamespace('Errors');
        $this->view->flashMessenger = $this->flashMessenger->getMessages();
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

    public function productinfoAction()
    {
        $id = (int) $this->_getParam('id');
        $productModel = new Application_Model_Product();
        $this->view->product= $productModel->getProduct(Zend_Registry::get('Zend_Locale'),1,$id);

        $productfotoModel = new Application_Model_ProductFoto();
        $this->view->fotos= $productfotoModel->getFotosForProductId($id);

        $form = new Application_Form_Voegtoe($id);
        $this->view->form = $form;
    }


    public function ajaxVoegtoeWinkelmandAction() {
        $this->_helper->layout->disableLayout();
        $formData  = $this->_request->getPost();
        parse_str($formData['data'], $data);
        $form = new Application_Form_Voegtoe();
        $error=0;
        if (!$form->isValid($data)){
    	    $error=1;
    	}
        else {
            $this->context['winkelmand'][$data['id']]=$data['Aantal'];
            $this->SaveContext();
        }
        $this->view->winkelmand=$this->context['winkelmand'];
        $this->view->error=$error;
    }

}





