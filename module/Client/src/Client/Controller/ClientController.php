<?php
namespace Client\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Session\User;
use Zend\View\Model\ViewModel;

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
		$viewModel = new ViewModel();
		if(is_null($clientDoc)) {
			$viewModel->setTemplate('client/client/create');
			$viewModel->setVariables(array(
				'websiteId' => $websiteId
			));
		} else {
			$viewModel->setVariables(array(
				'clientId' => $clientDoc->getId(),
				'companyName' => $clientDoc->getCompanyName()
			));
		}
		return $viewModel;
	}

}