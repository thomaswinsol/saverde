<?php
class Application_Form_OrderDetail extends My_Form  {

    

    public function init()
    {
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);

         // element ID
        $this->addElement(new Zend_Form_Element_Text('ID',array(
            'label'=>"ID",
            'size'=>5,
            'readonly'=>true,
            )));
        
        // element Tekst
        $this->addElement(new Zend_Form_Element_Textarea('Omschrijving',array(
            'label'=>"Omschrijving",
            'rows'=>1,
            'required'=>true,
            'filters' => array('StringTrim', 'StripTags'),
            'validators'=>array (),
            )));

        // element Eenheid
        $eenheidModel = new Application_Model_Eenheid();
        $defaultOptions = array('key'=> 'Omschrijving', 'value' =>'Omschrijving', 'emptyRow' => False);
        $eenheid = $eenheidModel->buildSelect($defaultOptions, null, "Omschrijving");
        $elem = new Zend_Form_Element_Select('Eenheid');
        $elem->setLabel('Eenheid')
             ->setMultiOptions($eenheid)
	     ->setdecorators($this->elementDecorators);
        $this->addElement($elem);

         // element Aantal
        $this->addElement(new Zend_Form_Element_Text('Aantal',array(
            'label'=>"Aantal",
            'size'=>10,
            'class'=>'onlyDecimals',
            'filters' => array('StringTrim')
            )));

         // element Eenheidsprijs
        $this->addElement(new Zend_Form_Element_Text('Prijs',array(
            'label'=>"Eenheidsprijs",
            'size'=>10,
             'class'=>'onlyDecimals',
            'filters' => array('StringTrim')
            )));

        // element Hidden
        $this->addElement(new Zend_Form_Element_Hidden('ID_Order',array(
            )));
        $this->setElementDecorators($this->elementDecorators);

         

         // element lastName
        $this->addElement(new Zend_Form_Element_Button('Toevoegen', array(
            'type'=>"submit",
            'required'=> false,
            'ignore'=> true,
            'decorators'=>$this->buttonDecorators
            )));
        }

}
?>
