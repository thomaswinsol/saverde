<?php
/**
* Setup view variables
*/
class Syntra_Controller_Plugin_Mail extends Zend_Controller_Plugin_Abstract
{
	
     const TEMPLATE_OA = 'Onderaannemer';
    
     public $view;
    
     public function __construct()
     {
     	$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
     	$viewRenderer->init();
     	$this->view = $viewRenderer->view;
     }     
     
     
     private function hasMailAccess(){
     	if (APPLICATION_ENV == 'production'){
     		return TRUE;
     	}
     	return false;
     }
     
     
     public function send($templateName,$data = null){ 
     	/*if (!$this->hasMailAccess()){
     		return FALSE; 
     	}*/
     	
     	$templateMethod = 'template_'.$templateName;
     	if (!method_exists($this,$templateMethod)){
     		throw new Exception('Mail template not found');
     	}
     	 
     	return $this->$templateMethod($data);
     	// mail('allan.groom@techtronix.be','testing','het werkt');
     	// echo 'mail model é: ';
     	//Zend_Debug::dump($orderProduct);     
     }
     
     
     // ---------
     // TEMPLATES
     // ---------     
     protected function template_Onderaannemer($data){
     	if (empty($data) ){
     		return FALSE;
     	}
                ini_set("SMTP", "10.10.101.38");
		ini_set("sendmail_from", "thomas.vanhuysse@winsol.be");
		ini_set("smtp_port", "25");
	
     		$mail = new Zend_Mail('ISO-8859-1');
     		$mail->setFrom($_SESSION['context2']['useremail'], $_SESSION['context2']['username']);

     		$mail->addTo('thomas.vanhuysse@winsol.eu', 'Winsol');     		
     		$mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
     		$this->view->data = $data;     	
     		$html = $this->view->render('/mail/onderaannemer.phtml');
     		$result=explode(":::",$html);
     		$mail->setBodyHtml($result[0],'ISO-8859-1',Zend_Mime::ENCODING_BASE64);
     		$mail->setSubject($result[1]);
     		$mail->send(); 
     		
     		return TRUE;
     }     
     
	 
} 
