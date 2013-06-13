<?php
class Application_Form_News extends Zend_Form  {
    
        public function init(){
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        //$this->setAttrib('enctype', 'multiparts/form-data');
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);

        // element titel
        $this->addElement(new Zend_Form_Element_Text('titel',array(
            'label'=>"Titel",
            'required'=>true,
            // filters
            'filters' => array('StringTrim')
            )));

        // element teaser
        $this->addElement(new Zend_Form_Element_Text('teaser',array(
            'label'=>"Adres",
            'required'=>true,
            //'maxlength'=> '255',
            // filters
            'filters' => array('StringTrim'),
            'validators' => array( array('StringLength',true, array('max'=>255)))

            )));

        // element lastName
        $this->addElement(new Zend_Form_Element_Textarea('description',array(
            'label'=>"Omschrijving",
            'required'=>true,
            // filters
            'filters' => array('StringTrim', 'StripTags'),
            'validators'=>array (),
            )));

      $this->addElement(new Zend_Form_Element_Text('newsdate',array(
            'label'=>"Datum",
            'required'=>true,
            //'maxlength'=> '255',
            // filters
            'filters' => array('StringTrim'),
            'validators' => array()
            )));

         // element lastName
        $this->addElement(new Zend_Form_Element_Button('versturen', array(
            'type'=>"submit",
            'value'=>'mailen',
            'required'=> false,
            'ignore'=> true
            )));
        //$btn = new Zend_Form_Element_Button();
        //$btn->setLabel('versturen')->setRequired(false);
        }
}
?>
