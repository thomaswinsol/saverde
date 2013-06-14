<?php
class Admin_Form_Foto extends My_Form
{

    public function init()
    {

        $elem = new ZendX_JQuery_Form_Element_AutoComplete("Foto", array('label' => 'Foto', 'size'=>30 , 'maxlength'=>8));
	$elem->setJQueryParam('source', '/Admin/foto/autocompletefoto');
	$elem->setJQueryParams( array("select" => new Zend_Json_Expr(
	    							"function(event, ui) {
    									//$('#IDFoto').val(ui.item.ID);
                                                                        location.href='/index/home/';
                                                                }") ));
     	$elem->setDecorators($this->formJQueryElements);
  	$this->addElements( array ($elem));
        
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