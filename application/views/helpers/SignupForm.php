<?php
class Zend_View_Helper_SignupForm extends Zend_View_Helper_Abstract

{

    public function signupForm(Application_Form_Signup $form)

    {
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) {
            $username = $auth->getIdentity();
            $logoutUrl = $this->view->url(array('controller'=>'gebruiker', 'action'=>'logout'), null, true);

            return $this->view->translate('txtWelkom') .' , '. $username->naam . '. <a href="'.$logoutUrl.'">Logout</a>';
        }

        $html = '<p>'. $this->view->translate('txtInloggen') .'</p>';
        if($form->processed) {
            $html .= '<p>Bedankt om in te loggen</p>';
        } else {
            $html .= $form->render();
        }

        return $html;

    }

}

