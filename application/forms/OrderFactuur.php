<?php

class Application_Form_OrderFactuur extends My_Form
{
	protected $_defaultFormOptions = array(
	                       'name' => 'frmOrderFactuur',
	                       'method' => 'post',
	 );


    public function __construct($id = NULL, $options = NULL, $params = NULL)
    {
	    $this->_defaultFormOptions['action'] = '/com/order/product'; //@todo: build action automatic, based on application and controller
   		$options = !empty($options) ? array_merge($this->_defaultFormOptions,(array)$options) : $this->_defaultFormOptions;
        parent::__construct($id, $options);


     
        $Factuurnr = new Zend_Form_Element_Text('Factuurnr');
        $Factuurnr->setLabel('Factuurnr')
              ->setRequired()
              ->addValidator('NotEmpty', TRUE)
        ;

        $DatumFactuur = new Zend_Form_Element_Text('DatumFactuur');
        $DatumFactuur->setLabel('DatumFactuur')
              ->setRequired()
              ->addValidator('NotEmpty', TRUE)
              ->addValidator('date', true, array('dd-MM-yyyy'))
        ;
       
        $VervalDatum = new Zend_Form_Element_Text('VervalDatum');
        $VervalDatum->setLabel('VervalDatum')
              ->setRequired()
              ->addValidator('NotEmpty', TRUE)
              ->addValidator('date', true, array('dd-MM-yyyy'))
        ;
        
         $this->addElements(array($Factuurnr, $DatumFactuur, $VervalDatum));
       	// save
       	 $submit = new Zend_Form_Element_Submit('btn_invoiceOrder');
       	 $submit->setLabel('Opslaan');
       	 $submit->setRequired(false)
               ->setIgnore(true)
               ->setDecorators($this->buttonDecorators)
               ;
        $this->addElement($submit);
      // }

    }

    /**
     * @Override
     *
     * Populate the form with form values
     *
     * @param $values array
     */
    /*
    public function populate($values)
    {
    	 $onlyView = isset($values['ID_Type']) && $values['ID_Type'] == 2 ? TRUE : FALSE;
    	 //var_dump($values);
    	 if ($onlyView){
            $elems = $this->getElements();
            foreach($elems as $elem){
            	//$this->removeElement($elem);
            	$elem->setAttribs(array("disabled" => true,"readonly" => true));
            	//$elem->removeDecorator("tfoot");
            	if ($elem->getName() == 'btn_save'){ //echo 'remove!';
            		//$elem->removeDecorator();
            		$this->removeElement($elem);
            	}
            	//echo '<br /> -> ' . $elem->getName();
            }
           // $this->removeElement(''):
         }
         parent::populate($values);
    }
    */

}


