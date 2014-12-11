<?php
namespace Sp;

use Zend\Mvc\MvcEvent;
use Sp\FucmsSessionUser;
use Sp\FucmsSessionAuth;

class Module
{
	public function init($moduleManager)
	{
		$eventManager = $moduleManager->getEventManager();
		$sharedEventManager = $eventManager->getSharedManager();
		$sharedEventManager->attach('Zend\Mvc\Application', 'dispatch', array(
			$this,
			'userAuth'
		), 1000);
		$sharedEventManager->attach(__NAMESPACE__, 'dispatch', array(
			$this,
			'setLayout'
		), - 100);
	}
	
	public function userAuth(MvcEvent $e)
	{
		$sm = $e->getApplication()->getServiceManager();
		$config = $sm->get('Config');
		
		$routeMatched = $e->getRouteMatch();
		$routeName = $routeMatched->getMatchedRouteName();
		$routeNameArr = explode('/', $routeName);
		
		$fsUser = $sm->get('Sp\User');
		if($routeNameArr[0] != 'sp') {
			if(! $fsUser->isLogin()) {
				header("Location: /sp/login");
				exit(0);
			}
		}
		if(($routeMatched->getParam('controller') == 'Sp\UserController' && $routeMatched->getParam('action') != 'logout') && $fsUser->isLogin()) {
			// $routeMatched->setParam('controller', 'Application\IndexController');
			// $routeMatched->setParam('action', 'index');
			echo "user loggedin";
			exit(0);
		}
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
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				)
			)
		);
	}
	public function setLayout(MvcEvent $e)
	{
		// $rm = $e->getRouteMatch();
		// $matchedRouteName = $rm->getMatchedRouteName();
		// $matchedRouteNameParts = explode('/', $matchedRouteName);
		$viewModel = $e->getViewModel();
		$viewModel->setTemplate('layout-sp/layout');
	}
}