<?php
class Admin_ProductController extends My_Controller_Action
{
    public function detailAction()
    {
         $id = (int) $this->_getParam('id');
         if (!empty($id)) {
              $params['controller'] = $this->getRequest()->getControllerName();
              $params['productid']  = $id;
              $this->view->fotoform = new Admin_Form_Autocomplete(null,$params);
              $productfotoModel = new Application_Model_Productfoto();
              $this->view->productfotos=$productfotoModel->getAll("idproduct=".$id);
         }
         $this->view->id=$id;
         parent::detailAction();
    }

    public function selecteerAction()
    {
        $productfotoModel = new Application_Model_Productfoto();
        $productid = (int) $this->_getParam('productid');
        $id = (int) $this->_getParam('id');
        $dbFields=array("idproduct"=>$productid,"idfoto"=>$id);
        $productfotoModel->save($dbFields);
        $params = array('id' => $productid);
        $this->_helper->redirector('detail', 'product', 'Admin', $params);

    }
    
}

