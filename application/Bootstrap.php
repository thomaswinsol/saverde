<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initRegisterControllerPlugins()
    {
        $this->bootstrap('frontController');
        $front = $this->getResource('frontController');
        $front->registerPlugin(new My_Controller_Plugin_Translate());
        $front->registerPlugin(new My_Controller_Plugin_Navigation());
        $front->registerPlugin(new My_Auth_Acl());
        $front->registerPlugin(new My_Auth_Auth());
    }


        /*protected function _initResourceAutoload() {
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
        	'basePath'  => MY_PATH . 'My/' . APPLICATION_NAME . '/',
        	'namespace' => 'My_',
    	));
        }*/

    	protected function _initView()
 	{
                $view = new Zend_View();
                $jqueryTheme = 'smoothness';
		$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
                $view->jQuery()->addStylesheet('/base/js/jquery/css/'.$jqueryTheme.'/jquery-ui.custom.css');
                $view->jQuery()->setLocalPath('/base/js/jquery/jquery.min.js');
                $view->jQuery()->setUiLocalPath('/base/js/jquery/jquery-ui.min.js');
                $view->jQuery()->enable();
                $view->jQuery()->uiEnable();
                $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
                $viewRenderer->setView($view);
                Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
		return $view;
 	}

}

