<?php
class Admin_Form_Paginalijst extends My_Form
{

    public function init()
    {

        $elem = new ZendX_JQuery_Form_Element_AutoComplete("Pagina", array('label' => 'lblPagina', 'size'=>30 , 'maxlength'=>8));
	$elem->setJQueryParam('source', '/Admin/pagina/autocompletepagina');
	$elem->setJQueryParams( array("select" => new Zend_Json_Expr(
	    							"function(event, ui) {
                                                                        location.href='/Admin/pagina/addpagina/id/'+ui.item.id;                                                                }") ));
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