<?php
namespace Website\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Session\User;

class WebsiteController extends AbstractActionController
{
    public function indexAction()
    {
    	$sm = $this->getServiceLocator();
    	$user = $sm->get('Sp\User');
    	
    	$config = $sm->get('Config');
    	$dm = $sm->get('DocumentManager');
    }
    
    public function createAction()
    {
    	
    }
    
    public function editAction()
    {
    	
    }
    
    public function serverStatusAction()
    {
    	
    }
}