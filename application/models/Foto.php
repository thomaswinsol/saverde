<?php
class Application_Model_Foto extends My_Model
{
    protected $_name = 'foto';
    protected $_id = 'ID'; //primary key

    protected $pathUpload;

    public function __construct(){
        $this->pathUpload =  'uploads/foto/';
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

                $this->deleteById($fileId);
                $totalOk++;
                
                if (file_exists($this->pathUpload.$row['fileName'])) {                   
                    unlink($this->pathUpload.$row['fileName']);
                }
            
    	} 
    	return $totalOk;
    }
 

    public function saveFile(array $data)
    {
    	if (empty($data)){
    		return FALSE;
    	}

        $currentTime =  date("Y-m-d H:i:s", time());
    	$fileExtension = $this->getFileExtension($data['fileName']);
    	$dbFields = array(
        				'fileName'      => $data['fileName'],
    					'fileNameOrig'  => $data['fileNameOrig'],    					
    					'fileSize'	=> $data['fileSize'],
    					'filePath'      => $this->pathUpload,
    					'identifier'    => (isset($data['identifier']) ? (int)$data['identifier'] : 0),
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

