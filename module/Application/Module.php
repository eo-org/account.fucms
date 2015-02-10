<?php
namespace Application;

use Zend\Mvc\MvcEvent;

class Module
{
	public function init($moduleManager)
	{
		$eventManager = $moduleManager->getEventManager();
		$sharedEventManager = $eventManager->getSharedManager();
		
		$sharedEventManager->attach('Zend\Mvc\Application', 'dispatch', array($this, 'setController'), 100);
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
					'Account' => __DIR__ . '/src/Account'
				)
            ),
        );
    }
    
    public function setController(MvcEvent $e)
    {
    	$rm = $e->getRouteMatch();
    	
    	$viewModel = $e->getViewModel();
    	$viewModel->setVariable('controller', $rm->getParam('controller'));
    	$viewModel->setVariable('action', $rm->getParam('action'));
    }
}