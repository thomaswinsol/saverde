<?php
class Admin_Form_Page extends My_Form
{

    protected $_langFields;
    protected $_languages;

    public function __construct($id = NULL, $params = NULL)
    {
	    $this->_langFields = $params['langFields'];
            $this->_languages = $params['languages'];
            parent::__construct($id);
    }


    public function init(){
        // set the defaults
        $this->setMethod(Zend_Form::METHOD_POST);
        //$this->setAttrib('enctype', 'multiparts/form-data');
        $this->setAttrib('enctype', Zend_Form::ENCTYPE_MULTIPART);
        $this->setAction('/Admin/page/addpage');

         // element ID
        $this->addElement(new Zend_Form_Element_Text('ID',array(
            'label'=>"ID",
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

        $elem = $this->createElement('select','status');
		   	$elem->setLabel("lblstatus")
			->addMultiOptions(array('1' => 'JA' , '0' => 'NEE') )
                        ->setRequired(true)
			->setSeparator('');
			$this->addElement($elem);

        foreach ($this->_languages as $language) {
            foreach ($this->_langFields as $langfield) {
                    $field=$langfield."_".$language;
                    $this->addElement(new Zend_Form_Element_Text($field,array(
                    'label'=>$field,
                    'size'=>50,
                    'filters' => array('StringTrim')
            )));
            }
        }
        /*print_r($this->_langFields);
        print_r($this->_languages);
        die("ok");*/

         $this->setElementDecorators($this->elementDecorators);

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
            array('HtmlTag', array('tag' => 'table' ,'class' => 'frm_01','style' => 'width:70%;')),
            'Form',
        ));
    }


}