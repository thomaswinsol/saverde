<?php
class Admin_FotoController extends My_Controller_Action
{

    public function uploadAction()
    {
         $form = new Admin_Form_Foto();
         $this->view->form = $form;
         $pagesModel = new Application_Model_Pages();
         $info= $pagesModel->forminfo(1);
         echo '<pre>';
         print_r($info);
         die("ok");
         //$pagesModel->insert($info);

         $pagesModel = new Application_Model_Pages();
         print_r($pagesModel->getLangFields());
         die("ok");
    }

    public function ajaxUploadAction() {
        $this->_helper->layout->disableLayout();

        $data = $this->getRequest()->getPost();
        if (empty($_FILES) ) {
                echo 'No files to upload'; exit;
        }
        $fotoModel = new Application_Model_Foto();
        // ini
        $fileElem   = 'Filedata';
        $tempFile   = $_FILES[$fileElem]['tmp_name'];
        $targetPath = $fotoModel->getPathUpload();
        $prefix     = date('d.m.Y.H.i.s').'_';

        $fileNameOrig = $_FILES[$fileElem]['name']; //preg_replace("/(\s|%20)/","_",$_FILES['Filedata']['name']); # replace all white spaces and %20 with _
        $fileName    = $fileNameOrig;
        $fileName    = str_replace(" ","_", $fileName);
        $fileName    = str_replace("(","_", $fileName);
        $fileName    = str_replace(")","_", $fileName);
        $targetFile  = str_replace('//','/',$targetPath) . trim($fileName);
        $response = 0;
        if (move_uploaded_file($tempFile,$targetFile)){             
            $fileData = $v= array(
                            'fileName'     => trim($fileName),
                            'fileNameOrig' => $fileNameOrig,
                            'fileSize'	   => filesize($targetFile),
            );
            $fileId = $fotoModel->saveFile($fileData);
            if (!empty($fileId)){
                $response=1;
            }
    	}
        $this->view->response=$response;
   }

    public function autocompletefotoAction() {
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender();
 		$param= $this->_getParam('term');
 		$fotoModel = new Application_Model_Foto();
 		$data['naam']=trim($param);
 		$result=$fotoModel->getFoto(null);
 		$this->_helper->json(array_values($result));
    }
}

