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
        $categorieModel = new Application_Model_Categorie();
        $defaultOptions = array('key'=> 'id', 'value' =>'titel', 'emptyRow' => True);
        $categorie = $categorieModel->buildSelect($defaultOptions, null, "label");
        $elem = new Zend_Form_Element_Select('Categorie');
        $elem->setLabel('txtCategorie')
             ->setMultiOptions($categorie);
        $this->addElement($elem);

         // element label
        $this->addElement(new Zend_Form_Element_Text('label',array(
            'label'=>"txtLabel",
            'filters' => array('StringTrim'),
            'validators' => array( array('StringLength',true, array('max'=>255)))
            )));

          // element titel
        $this->addElement(new Zend_Form_Element_Text('titel',array(
            'label'=>"txtTitel",
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

    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table' ,'class' => 'frm_01','style' => 'width:30%;')),
            'Form',
        ));
    }
}
?>
