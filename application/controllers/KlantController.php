<?php

class KlantController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function listAction() {
        $klant = new Application_Model_Klant();
    	$this->view->dataGrid = $klant->buildDataGrid();
    }

    public function addklantAction() {
        $form = new Application_Form_Klant;
        $this->view->form = $form;

        if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();

            if ($this->view->form->isValid($postParams)) {
                /*unset($postParams['Opslaan']);*/
                $klantModel = new Application_Model_Klant();
                $klantModel->save($postParams);

                $this->_redirect($this->view->url(array('controller'=> 'Klant', 'action'=> 'list')));
            }
        }
    }


    public function wijzigenAction()
    {
        $id = (int) $this->_getParam('id'); //$_GET['id];

        $klantModel = new Application_Model_Klant();
        /*$product = $productModel->find($id)->current();*/
        $klant= $klantModel->getOne($id,'ID');

        $form = new Application_Form_Klant($id);
        $form->populate($klant);

        $this->view->form = $form;
        $this->view->id   = $id;

        if ($this->getRequest()->isPost()){
            $postParams= $this->getRequest()->getPost();
            if ($this->view->form->isValid($postParams)) {
                $klantModel->save($postParams, $id);
                $this->_redirect($this->view->url(array('controller'=> 'klant', 'action'=> 'list')));
            }
        }
    }


     public function detailAction() {
        try
        {
            $this->view->params = $this->_getAllParams();
            $id = (int) $this->_getParam('id');
            $klantModel = new Application_Model_Klant();
            $this->view->klant = $klantModel->getOne($id);

            
            $this->view->selectedTabIndex = (int) $this->_getParam('tab');
            

        } catch(Exception $e) {
    		return NULL;
    	}
    }

    public function fileuploadAction()
    {
            $id = (int) $this->_getParam('id');
            $klantModel = new Application_Model_Klant();
            $this->view->klant = $klantModel->getOne($id);
             $orderFilesModel = new Application_Model_Orderbestanden();
             $this->view->files = $orderFilesModel->getOrderFiles($id);
            $this->view->totalFiles=count($this->view->files);
    }
    
    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $dbFields = array('IsDeleted' => 1);
        $klantModel = new Application_Model_Klant();
    	$klantModel->update($dbFields,$id);
        $this->_redirect('/klant/list/');
    }

    public function infoAction()
    {
        $params = $this->_getAllParams();
        $orderModel = new Application_Model_Order();
        $this->view->result=$orderModel->getTurnoverByCustomer($params['id']);
    }


     public function ajaxUploadAction() {
        $this->_helper->layout->disableLayout();

        $data = $this->getRequest()->getPost();
        $orderId = isset($data['orderId']) ? (int)$data['orderId']: '';
        if (empty($_FILES) || empty($orderId)) {
                echo 'No files to upload or no orderId'; exit;
        }
        $orderBestandModel = new Application_Model_Orderbestanden();
        // ini
        $fileElem   = 'Filedata';
        $tempFile   = $_FILES[$fileElem]['tmp_name'];
        $targetPath = $orderBestandModel->getPathUpload();
        $prefix   = $orderId.'_'.date('d.m.Y.H.i.s').'_';

        $fileNameOrig = $_FILES[$fileElem]['name']; //preg_replace("/(\s|%20)/","_",$_FILES['Filedata']['name']); # replace all white spaces and %20 with _
        //$fileName    = urlencode($fileName); // replace non-alphanumeric with safe entities,
        $fileName    = $prefix . $fileNameOrig;
        $targetFile  =  str_replace('//','/',$targetPath) . $fileName;

        if (move_uploaded_file($tempFile,$targetFile)){
            //success
            //$finfo = new finfo;
            //$fileMimeType = $finfo->file($targetFile, FILEINFO_MIME);

            $fileData = $v= array(
                            'fileName'     => $fileName,
                            'fileNameOrig' => $fileNameOrig,
                            'fileSize'	   => filesize($targetFile),
                            //'mimeType'   => $fileMimeType,
                            'orderId' 	   => $orderId,
                            'identifier'   => $orderId,
            );
            $fileId = $orderBestandModel->saveFile($fileData);

            if (empty($fileId)){
                echo 0; exit;
            }
            $this->view->orderFile = $orderBestandModel->getOne($fileId);
            $this->view->orderPath = $targetPath;

    	}
   }

    public function deletefileuploadAction()
    {
        $formData = $this->_request->getPost();
        $data = $formData;
        $klantId = (int) $data['klantId'];
        $typeDoc    = $data['typeDoc'];
        if (!empty($data)) {
            if (isset($data['btn_deleteFiles_x']) || isset($data['btn_deleteFiles'])){
                if (isset($data['cbx_fileId']) && !empty($data['cbx_fileId'])){
                    $orderFileModel = new Application_Model_Orderbestanden();
                    $totalDeleted = $orderFileModel->deleteFiles($klantId,$data['cbx_fileId']);
                }
            }
        }
         $url = '/' . $this->getRequest()->getControllerName().'/detail/tab/0/id/'.$klantId;
        $this->_redirect($url);
    }




}





