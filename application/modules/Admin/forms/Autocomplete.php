<?php
class Admin_Form_Autocomplete extends My_Form
{

    protected $_controller;
    protected $_productid;

    public function __construct($id = NULL, $params = NULL)
    {
	    $this->_controller = $params['controller'];
            if (isset($params['productid'])) {
                $this->_productid = $params['productid'];
            }
            parent::__construct($id);
    }

    public function init()
    {
        if ( !empty($this->_productid) ) {
            $source  ='/Admin/foto/autocomplete';
            $location='/Admin/product/selecteer/productid/'.$this->_productid.'/id/';
            $label = "lblFoto";
        }
        else {
            $source  ='/Admin/'. trim($this->_controller). '/autocomplete';
            $location='/Admin/'. trim($this->_controller). '/detail/id/';
            $label = "lbl".trim(ucfirst($this->_controller));
        }
        
        $elem = new ZendX_JQuery_Form_Element_AutoComplete("Autocomplete", array('label' => $label, 'size'=>30 , 'maxlength'=>8));
	$elem->setJQueryParam('source', $source);
	$elem->setJQueryParams( array("select" => new Zend_Json_Expr(
	    							"function(event, ui) {
                                                                        location.href='$location'+ui.item.id; }") ));
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