<?php
class Application_Controller_Helper_Signup extends Zend_Controller_Action_Helper_Abstract
{

    public function preDispatch()

    {

        $view = $this->getActionController()->view;

        $form = new Application_Form_Signup();



        $request = $this->getActionController()->getRequest();

        if($request->isPost() && $request->getPost('submitsignup')) {

            if($form->isValid($request->getPost())) {

                $data = $form->getValues();

                // process data

                $form->processed = true;

            }

        }



        $view->signupForm = $form;

    }

}
