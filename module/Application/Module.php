<?php
namespace Application;

use Zend\EventManager\StaticEventManager;
use Zend\Mvc\MvcEvent;

class Module
{
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', array($this, 'userAuth'), 100);
	}
	
	public function userAuth(MvcEvent $e)
	{
		//echo "user auth before this module dispatch<br />";
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