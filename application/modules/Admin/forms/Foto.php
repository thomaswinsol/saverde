<?php
class Admin_Form_Foto extends My_Form
{
    protected $_langFields;
    protected $_languages;

    public function __construct($id = NULL, $params = NULL)
    {
	    $this->_langFields = $params['langFields'];
            $this->_languages  = $params['languages'];
            parent::__construct($id);
    }

    public function init(){
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        //$this->setAttrib('enctype', 'multiparts/form-data');
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
        $this->setAction('/Admin/pagina/detail');

         // element ID
        $this->addElement(new Zend_Form_Element_Hidden('ID',array(
            'size'=>5,
            'readonly'=>true,
            )));

        // element label
        $this->addElement(new Zend_Form_Element_Text('label',array(
            'label'=>"lbllabel",
            'required'=>true,
            'size'=>20,
            'filters' => array('StringTrim')
            )));
        // element status
        $elem = $this->createElement('select','status');
		   	$elem->setLabel("lblstatus")
			->addMultiOptions(array('1' => 'Actief' , '0' => 'Inactief') )
                        ->setRequired(true)
			->setSeparator('');
			$this->addElement($elem);
        $this->setElementDecorators($this->elementDecorators);

        // translation fields
        foreach ($this->_languages as $language) {
   
                    $fields=array();
            foreach ($this->_langFields as $langfield) {
                    $field=$langfield."_".$language;
                    $fields[]=$field;
                    $this->addElement(new Zend_Form_Element_Text($field,array(
                    'label'=>$langfield,
                    'size'=>50,
                    'maxlength'=>50,
                    'filters' => array('StringTrim')
            )));
            }
            $this->addDisplayGroup($fields, 'groups'.$language, array("legend" => $language));

            $group = $this->getDisplayGroup('groups'.$language);
            $group->setDecorators(array(
                'FormElements',
                'Fieldset',
                array('HtmlTag',array('tag'=>'div','closeOnly'=>true))
        ));



        }

         // element button
        $this->addElement(new Zend_Form_Element_Button('Opslaan', array(
            'type'=>"submit",
            'label'=>'btnOpslaan',
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