<?php
class Admin_PaginaController extends My_Controller_Action
{

    public function lijstAction()
    {
        $form = new Admin_Form_Paginalijst();
        $this->view->form = $form;
    }

    public function addpaginaAction()
    {
         $paginaModel = new Application_Model_Pagina();
         $param['langFields']= $paginaModel->getLangFields();
         $param['languages']= array("nl","fr");

         $form = new Admin_Form_Pagina(null,$param);

         $id = (int) $this->_getParam('id');
         If (!empty($id)) {
             $formData= $paginaModel->GetDataAndTranslation($id);
             $formData['ID']=$id;
             $form->populate($formData);
         }
         $this->view->form = $form;
         if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();
            if (!$form->isValid($postParams)) {
                return;
            }
            $formData  = $this->_request->getPost();
           
            $data= $paginaModel->SplitDataAndTranslation($formData);
            $paginaModel->save($data, $data['ID']);
            $this->_helper->redirector('lijst', 'pagina');
         }
         
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

