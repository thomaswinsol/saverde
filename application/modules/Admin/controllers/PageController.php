<?php
class Admin_PageController extends My_Controller_Action
{

    public function listAction()
    {
        $form = new Admin_Form_Pagelist();
        $this->view->form = $form;
    }

    public function addpageAction()
    {
         $pagesModel = new Application_Model_Pages();
         $param['langFields']= $pagesModel->getLangFields();
         $param['languages']= array("nl","fr");

         $form = new Admin_Form_Page(null,$param);

         if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();
            if (!$form->isValid($postParams)) {
                return;
            }
            $formData  = $this->_request->getPost();
            /*echo '<pre>';
            print_r($formData);*/
            //die("ok");
           
            $data= $pagesModel->GetDataAndTranslation($formData);
            $pagesModel->save($data);
            $this->_helper->redirector('list', 'page');
        }

         $this->view->form = $form;

         /*$pagesModel = new Application_Model_Pages();
         $info= $pagesModel->forminfo(1);*/

        
    }

    
    public function autocompletepageAction() {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender();
 		$param= $this->_getParam('term');
 		$pagesModel = new Application_Model_Pages();
 		$data['naam']=trim($param);
 		$result=$pagesModel->getPage(null);
 		$this->_helper->json(array_values($result));
    }
}

