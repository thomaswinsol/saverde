<?php
class Application_Form_Order extends My_Form  {

    
    public function init()
    {
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);

         // element ID
        $this->addElement(new Zend_Form_Element_Hidden('ID',array(
            //'label'=>"ID",
            //'size'=>5,
            'readonly'=>true,
            )));

         $this->addElement(new Zend_Form_Element_Text('OrderDate',array(
            'label'=>"Datum",
            'size'=>10,
            'required'=>true,
            'validators' => array(
                array('Date', true, array('format'=>'yyyy-MM-dd')))
             )));
         

        // element Naam
        $this->addElement(new Zend_Form_Element_Text('Refk',array(
            'label'=>"Referentie",
            'size'=>50,
            'filters' => array('StringTrim')
            )));
        
        // element Opmerking
        $this->addElement(new Zend_Form_Element_Textarea('Opmerking',array(
            'label'=>"Opmerking",
            'rows'=>1,
            'filters' => array('StringTrim', 'StripTags'),
            'validators'=>array (),
            )));

            // element Hidden
        $this->addElement(new Zend_Form_Element_Hidden('ID_Klant',array(
            )));
        
         $this->setElementDecorators($this->elementDecorators);

      

         // element lastName
        $this->addElement(new Zend_Form_Element_Button('Opslaan', array(
            'type'=>"submit",
            'required'=> false,
            'ignore'=> true,
            'decorators'=>$this->buttonDecorators
            )));
        }

}
?>
