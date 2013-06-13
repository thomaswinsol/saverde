<?php

class Application_Form_Signup extends Zend_Form {

    public function init(){
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        //$this->setAttrib('enctype', 'multiparts/form-data');
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
        $this->setAction('/gebruiker/login');

        // element naam
        $this->addElement(new Zend_Form_Element_Text('email',array(
            'label'=>"lblEmail",
            'required'=>true,
            'size'=>35,
            // filters
            'filters' => array('StringTrim')
            )));

        // element wachtwoord
        $this->addElement(new Zend_Form_Element_Password('paswoord',array(
            'label'=>"lblPaswoord",
            'required'=>true,
            // filters
            'filters' => array('StringTrim')
            )));

         // element button
        $this->addElement(new Zend_Form_Element_Button('inloggen', array(
            'type'=>"submit",
            'label'=>'btnInloggen',
            'required'=> false,
            'ignore'=> true
            )));

    }

}

?>