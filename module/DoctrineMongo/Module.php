<?php
namespace DoctrineMongo;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Doctrine\Common\Persistence\PersistentObject,
Doctrine\ODM\MongoDB\DocumentManager,
Doctrine\ODM\MongoDB\Configuration,
Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver,
Doctrine\MongoDB\Connection;

class Module implements BootstrapListenerInterface
{
	public function onBootstrap(EventInterface $event)
	{
		$application = $event->getTarget();
		$sm = $application->getServiceManager();
		
		$fileConfig = $sm->get('Config');
		$env = $fileConfig['env'];
		
		AnnotationDriver::registerAnnotationClasses();
		$config = new Configuration();
		$config->setDefaultDB('account_fucms');
		
		$config->setProxyDir(__DIR__ . '/../../doctrineCache');
		$config->setProxyNamespace('DoctrineMongoProxy');
		$config->setHydratorDir(__DIR__ . '/../../doctrineCache');
		$config->setHydratorNamespace('DoctrineMongoHydrator');
		$config->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/../../doctrineCache/class'));
		
		if($env['usage']['server'] == 'production') {
			$config->setAutoGenerateHydratorClasses(false);
			$config->setAutoGenerateProxyClasses(false);
		}
		$connection = new Connection('127.0.0.1', array(
			'username' => 'craftgavin',
			'password' => 'whothirstformagic?',
			'db' => 'admin'
		));
		$connection->initialize();
		
		$dm = DocumentManager::create($connection, $config);
		PersistentObject::setObjectManager($dm);
		$sm->setService('DocumentManager', $dm);
	}
}