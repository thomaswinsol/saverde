<?php
class Application_Form_ZoekProduct extends My_Form  {

    
    public function init()
    {
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);

        // element ID
        $this->addElement(new Zend_Form_Element_Hidden('ID',array( )));
        // element Categorie
        $titelModel = new Application_Model_Categorie();
        $defaultOptions = array('key'=> 'ID', 'value' =>'Omschrijving', 'emptyRow' => True);
        $titel = $titelModel->buildSelect($defaultOptions, null, "Omschrijving");
        $elem = new Zend_Form_Element_Select('Categorie');
        $elem->setLabel('Categorie')
             ->setMultiOptions($titel);
        $this->addElement($elem);

         // element label
        $this->addElement(new Zend_Form_Element_Text('Label',array(
            'label'=>"Label",
            'filters' => array('StringTrim'),
            'validators' => array( array('StringLength',true, array('max'=>255)))
            )));

          // element titel
        $this->addElement(new Zend_Form_Element_Text('Titel',array(
            'label'=>"Titel",
            'filters' => array('StringTrim'),
            'validators' => array( array('StringLength',true, array('max'=>255)))
            )));

        $this->setElementDecorators($this->elementDecorators);

        // button zoeken
        $this->addElement(new Zend_Form_Element_Button('Zoeken', array(
            'type'=>"submit",
            'required'=> false,
            'ignore'=> true,
            'decorators'=>$this->buttonDecorators
            )));
        }

}
?>
