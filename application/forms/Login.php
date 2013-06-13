<?php
class Application_Form_Login extends Zend_Form
{
    public function init()
    {
        $this->addElement('text', 'username', array(
            'label'      => 'Username',
            'required'   => true,
            'errorMessages' => array('Username is verplicht'),
            'filters'    => array('StringTrim','StripTags','StringToLower'),
            'validators' => array(
                            array('NotEmpty',true),
      //                      array('StringLength',true,array(6,20))
        
        ) //,true,array('messages' => '%s is required  pipo!'))
        ));

        $this->addElement('text', 'password', array(
            'label'      => 'Paswoord',
            'required'   => true,
        	'errorMessages' => array('Paswoord is verplicht'),
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                                array('NotEmpty',true),
                                array('StringLength',true,array(6,20))
                            ) 
            ));  
    }


}

