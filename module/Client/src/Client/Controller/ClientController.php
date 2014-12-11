<?php
namespace Client\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Session\User;

class ClientController extends AbstractActionController
{

	public function indexAction()
	{
		
	}

	public function createAction()
	{
		
	}

	public function editAction()
	{
		$websiteId = $this->params()->fromRoute('website-id');
		$dm = $this->getServiceLocator()->get('DocumentManager');
		
		$clientDoc = $dm->getRepository('Client\Document\Client')->findOneByWebsiteIds($websiteId);
		if(is_null($clientDoc)) {
			throw new \Exception('client not found');
		}
		return array(
			'clientId' => $clientDoc->getId(),
			'companyName' => $clientDoc->getCompanyName()
		);
	}

}