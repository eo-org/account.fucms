<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Session\User;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$sm = $this->getServiceLocator();
    	$config = $sm->get('Config');
    	$dm = $sm->get('DocumentManager');
    	
    	$user = new User($dm);
    	$websiteDocs = $user->getWebsiteDocs();
    	
    	return array(
    		'websiteDocs' => $websiteDocs,
    		'domainClient' => $config['env']['domain']['client']
    	);
    }
}