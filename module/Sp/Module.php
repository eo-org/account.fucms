<?php
namespace Sp;

use Zend\EventManager\StaticEventManager;
use Zend\Mvc\MvcEvent;
use Sp\FucmsSessionUser;
use Sp\FucmsSessionAuth;

class Module
{
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach('Zend\Mvc\Application', 'dispatch', array($this, 'userAuth'), 100);
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'setLayout'), 100);
	}
	
	public function userAuth(MvcEvent $e)
	{
		$sm = $e->getApplication()->getServiceManager();
		$fsUser = new FucmsSessionUser();
		
		$routeMatched = $e->getRouteMatch();
		$controllerName = $routeMatched->getParam('controller');
		$allowedControllers = ['Sp\UserController', 'Sp\ValidateController'];
		
		if($controllerName == 'Sp\UserController' && $fsUser->isLogin()) {
			$routeMatched->setParam('controller', 'Application\IndexController');
			$routeMatched->setParam('action', 'index');
		} else if(!in_array($controllerName, $allowedControllers) && !$fsUser->isLogin()) {
			$fsAuth = new FucmsSessionAuth($sm);
			$fsAuth->requestToken($fsUser);
		}
	}
	
	public function setLayout(MvcEvent $e)
	{
		$e->getViewModel()->setTemplate('sp/layout');
	}
	
    public function getConfig()
    {
    	return include __DIR__ . '/config/module.config.php';
    }
    
	public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__	=> __DIR__ . '/src/' . __NAMESPACE__,
				)
            ),
        );
    }
}