<?php
class Admin_ProductController extends My_Controller_Action
{

    public function detailAction()
    {
         $detailModel = new Application_Model_Product;
         $param["langFields"]= $detailModel->getLangFields();

         $taalModel = new Application_Model_Taal();
         $param["languages"]= $taalModel->getTaal();


         $form = new Admin_Form_Product(null,$param);
         $this->processForm($form);
    }

    
}

