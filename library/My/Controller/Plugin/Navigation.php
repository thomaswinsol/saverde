<?php
class My_Controller_Plugin_Navigation extends Zend_Controller_Plugin_Abstract
{
/**
 * 
 * @param \Zend_Controller_Request_Abstract $request
 * @return \Zend_Navigation
 */
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        //make navigation
        $container = new Zend_Navigation;
        
        $urls = array (
         array ( 'label'=> 'menuHome', 'module'=>'default', 'action'=> 'home', 'controller'=> 'index', 'params'=> array() ),
         array ( 'label'=> 'menuProduct', 'module'=>'Admin', 'action'=> 'lijst', 'controller'=> 'product', 'params'=> array() ),
         array ( 'label'=> 'menuCategorie', 'module'=>'Admin', 'action'=> 'lijst', 'controller'=> 'categorie', 'params'=> array() ),
         array ( 'label'=> 'menuFotoUpload', 'module'=>'Admin', 'action'=> 'lijst', 'controller'=> 'foto', 'params'=> array() ),
         array ( 'label'=> 'menuPages', 'module'=>'Admin', 'action'=> 'lijst', 'controller'=> 'pagina', 'params'=> array() ),
        );
        
        foreach  ($urls as $url) {
            $page = new Zend_Navigation_Page_Mvc(array(
                'label' => $url['label'] ,
                'module' => $url['module'],
                'action'=> $url['action'],
                'controller'=> $url['controller'],
                //'route'=> 'default',
                'params'=> $url['params'],               
            ));
            $container->addPage($page);
        }
        Zend_Registry::set('Zend_Navigation', $container);
        return $container;
        
    }
}

?>
