<?php
class Zend_View_Helper_AuthInfo extends Zend_View_Helper_Abstract
{
	protected $_authService;
	public function authInfo ($info = null)
	{
		if (null === $this->_authService) {
		   $this->_authService = new
				Storefront_Service_Authentication();
	}
	if (null === $info) {
		return $this;
	}
	if (false === $this->isLoggedIn()) {
		return null;
	}
	return $this->_authService->getIdentity()->$info;
	}
	public function isLoggedIn()
	{
	return $this->_authService->getAuth()->hasIdentity();
	}
}