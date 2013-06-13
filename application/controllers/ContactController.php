<?php

class ContactController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
        {
        $form = new Application_Form_Contact();
        $this->view->form  = $form;

        //Zend_Debug::dump($form);

        if ($this->getRequest()->isPost()) {
            $postParams= $this->getRequest()->getPost();  // alle post parameters in een variabele stoppen

            if ($this->view->form->isValid($postParams)){
                // controle of alle velden correct zijn volgens de validator
                    die("ok");
                // verstuur e-mail
                $params = $this->view->form->getValues();

                $body  = 'Hallo, dit is een mail';
                $body .= "Voornaam =" . $params['firstName']. '</br>';
                $body .= "E-mail =" . $params['email']. '</br>';
                $body .= "Description =" . $params['description']. '<br />';

                /*$configSMTP = array(
                    'port'=>587,
                    'auth'=>'login',
                    'username'=>'xxx',
                    'password'=>'xxxx',
                );*/
                //die("ok");
                //$transport = new Zend_Mail_Transport_Smtp('smtp.telenet.be', $configSMTP);
                $transport = new Zend_Mail_Transport_Smtp('uit.telenet.be');

                $mail = new Zend_Mail();
                $mail->addto('thomas.vanhuysse@telenet.be');
                //$mail->addcc('info@example.com');
                //$mail->addbcc('info@example.com');
                $mail->setSubject('dit is een testmail .....');
                $mail->setBodyHtml($body);
                $mail->setBodyText('Kan je deze mail niet lezen? Lees hem online ... dus hier http://www....');
                $mail->setFrom($params['email']);

                $mail->send($transport);

                echo 'Uw mail werd verzonden';
            }

            //$this->view->form->populate($postParams); zf 1.12 doet dit reeds zelf

        }

    }





}

