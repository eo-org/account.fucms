<?php
namespace Website;

use Zend\EventManager\StaticEventManager;
use Zend\Mvc\MvcEvent;

class Module
{
	public function init($moduleManager)
	{
		$sharedEvents = StaticEventManager::getInstance();
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
					'WebsiteRest' => __DIR__ . '/src/ClientRest'
				)
            ),
        );
    }
}