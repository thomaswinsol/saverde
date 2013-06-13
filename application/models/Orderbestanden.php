<?php
class Application_Model_Orderbestanden extends My_Model
{
    protected $_name = 'orderbestanden';
    protected $_id = 'ID'; //primary key

    protected $pathUpload;

    public function __construct(){
        $this->pathUpload =  'uploads/klanten/';
        parent::__construct();
    }

    public function getPathUpload(){
        return $this->pathUpload;
    }

    public function getFileExtension($_fileName){
    	$parts = explode('.',$_fileName);
    	$ext   = strtolower($parts[count($parts)-1]);
    	return $ext;
    }

    public function deleteFiles($orderId,array $filesId){
    	if (empty($filesId)) {
            return FALSE;
    	}
    	$totalOk = 0;

    	foreach($filesId as $fileId){
            $row = $this->getOne($fileId);
            if (empty($row) || $row['ID_Order']!=$orderId){
                continue;
            }
                //die($this->pathUpload.$row['fileName']);
            //if ($this->deleteById($fileId)) {
                $this->deleteById($fileId);
                $totalOk++;
                
                if (file_exists($this->pathUpload.$row['fileName'])) {                   
                    unlink($this->pathUpload.$row['fileName']);
                }
            //} //end if
    	} //end foreach
    	return $totalOk;
    }

    public function updateFiles(array $filesId, $typeDoc, $orderId){

        if (empty($filesId)) {
            return FALSE;
    	}
        $totalOk = 0;

        foreach($filesId as $fileId){
            
            $row = $this->getOne($fileId);
            if (empty($row) || $row['ID_Order']!=$orderId){
                continue;
            }
                //die("ok".$fileId);
                $dbFields= array("typeDoc"=>$typeDoc);
                $this->updateById($fileId, "ID",$dbFields);
                $totalOk++;
    	} 
    	return $totalOk;
    }

    public function getOrderFiles($orderId){
        if (empty($orderId)){
            return FALSE;
        }
        $where = 'ID_Order = '.(int)$orderId;
        return $this->getAll($where);
    }

    public function copyOrderFiles($sourceOrderId,$targetOrderId){
        if (empty($sourceOrderId) || empty($targetOrderId)){
            return FALSE;
        }
        $sourceFiles = $this->getOrderFiles($sourceOrderId);
        if (empty($sourceFiles)){
            return 0;
        }
        $totalCopy = 0;
        $currentTime = time();
        foreach($sourceFiles as $v){
            	$dbFields = $v;
            	unset($dbFields['ID']);
            	$dbFields['ID_Order'] = $targetOrderId;
            	$dbFields['creationDate'] = $currentTime;
            	$dbFields['lastUpdate'] = $currentTime;
            	if ($this->insert($dbFields)){
            	    $totalCopy++;
            	}
        }
        return $totalCopy;
    }

    public function saveFile(array $data)
    {
    	if (empty($data)){
    		return FALSE;
    	}
    	//$currentTime = time();
        $currentTime =  date("Y-m-d H:i:s", time());
    	$fileExtension = $this->getFileExtension($data['fileName']);
    	$dbFields = array(
    					'ID_Order'        => (isset($data['orderId']) ? (int)$data['orderId'] : null),
    					//'screenName'    => null,
    					'fileName'      => $data['fileName'],
    					'fileNameOrig'  => $data['fileNameOrig'],
    					'fileOrder'		=> (isset($data['fileOrder']) ? (int)$data['fileOrder'] : 0),
    					//'thumb'			=> null,
    					//'mimeType'		=> $data['mimeType'],
    					'fileSize'		=> $data['fileSize'],
    					'filePath'      => $this->pathUpload,
    					'identifier'    => 	(isset($data['identifier']) ? (int)$data['identifier'] : 0),
    					'creationDate'  => $currentTime,
    					'lastUpdate'    => $currentTime
    			);

        try {
            return $this->insert($dbFields);
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
             echo '<hr><pre>';
                print_r($dbFields);
                die("ok");
        }


    	
        
    }


    public function saveMultipleFiles($files,$data)
    {
    	if (empty($files)){
    		return false;
    	}

    	$currentTime = time();
    	$totalInsert = 0;
    	foreach($files as $k=>$v) {
    		$fileExtension = $this->getFileExtension($v['fileName']);
    		if ($k=='new'){
    			$thumb = null;
    			$dbFields = array(
    					'ID_Order'        => (isset($data['orderId']) ? (int)$data['orderId'] : null),
    					'screenName'    => null,
    					'fileName'      => $v['fileName'],
    					'fileNameOrig'  => $v['fileNameOrig'],
    					'fileOrder'		=> (!empty($data['files'][$k]['fileOrder']) ? (int)$data['files'][$k]['fileOrder'] : (isset($data['fileOrder']) ? (int)$data['fileOrder'] : null)),
    					'thumb'			=> $thumb,
    					'mimeType'		=> $v['mimeType'],
    					'fileSize'		=> $v['fileSize'],
    					'filePath'      => $this->pathUpload,
    					'identifier'    => 	(isset($data['identifier']) ? (int)$data['identifier'] : null),
    					'creationDate'  => $currentTime,
    					'lastUpdate'    => $currentTime
    			);
    			//smail('allan.groom@techtronix.be','test',var_dump($dbFields));
    			$fileId =  $this->insert($dbFields);
    			if (!empty($fileId)){
    			    $totalInsert++;
    			}
    		} //end if ($k == 'new')
    	}
    	//echo '..OK';
    	return $totalInsert;
    }

}

?>

