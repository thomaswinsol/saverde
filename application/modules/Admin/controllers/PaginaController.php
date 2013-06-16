<?php
class Admin_PaginaController extends My_Controller_Action
{
    
    public function detailAction()
    {
         $detailModel = new Application_Model_Pagina;
         $param["langFields"]= $detailModel->getLangFields();
 
         $taalModel = new Application_Model_Taal();
         $param["languages"]= $taalModel->getTaal();

         $form = new Admin_Form_Pagina(null,$param);
         $this->processForm($form);
    }

    
}

