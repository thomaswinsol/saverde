<?php
class Admin_PaginaController extends My_Controller_Action
{

    public function lijstAction()
    {
        $form = new Admin_Form_Paginalijst();
        $this->view->form = $form;
    }
    
    public function detailAction()
    {
         $detailModel = new Application_Model_Pagina;
         $param["langFields"]= $detailModel->getLangFields();
 
         $taalModel = new Application_Model_Taal();
         $param["languages"]= $taalModel->getTaal();

         $form = new Admin_Form_Pagina(null,$param);
         $this->processForm($form);
    }

    public function autocompletepaginaAction() {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender();
 		$param= $this->_getParam('term');
 		$paginaModel = new Application_Model_Pagina();
 		$data['naam']=trim($param);
 		$result=$paginaModel->getPagina(null);
 		$this->_helper->json(array_values($result));
    }
}

