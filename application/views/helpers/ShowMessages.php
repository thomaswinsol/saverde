<?php
class Zend_View_Helper_ShowMessages extends Zend_View_Helper_Abstract
{

    public function ShowMessages($flashmessenger)
    {
        if (empty($flashmessenger)) {
            return null;
        }
        $message=null;
        if (!empty($flashmessenger)){
		foreach($flashmessenger as $v){
			$message .= $this->view->escape($v) . '<br />' .  PHP_EOL;
		}
        }
        return $message;
    }

}

