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
		$dbName = 'account_fucms';
		
		AnnotationDriver::registerAnnotationClasses();
		$config = new Configuration();
		$config->setDefaultDB($dbName);
		
		$config->setProxyDir(BASE_PATH . '/account.fucms/doctrineCache');
		$config->setProxyNamespace('DoctrineMongoProxy');
		$config->setHydratorDir(BASE_PATH . '/account.fucms/doctrineCache');
		$config->setHydratorNamespace('DoctrineMongoHydrator');
		$config->setMetadataDriverImpl(AnnotationDriver::create(BASE_PATH . '/account.fucms/doctrineCache/class'));
		
		$config->setAutoGenerateHydratorClasses(true);
		$config->setAutoGenerateProxyClasses(true);
		
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