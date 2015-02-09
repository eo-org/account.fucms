<?php
namespace Client\Controller;

use Core\Zh;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ClientController extends AbstractActionController
{

	public function indexAction()
	{
		
	}

	public function createAction()
	{
		
	}

	public function addInitAction()
	{
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$zh = new Zh(); 
		$clientDocs = $dm->getRepository('Client\Document\Client')->findAll();
		foreach($clientDocs as $cd) {
			$name = $cd->getCompanyName();
			$pyInit = $zh->getInitials($name);
			$pyInit = strtolower($pyInit);
			
			
			$gap = array(" ","　","\t","\n","\r");
			$rep = array("","","","","");
			$pyInit = str_replace($gap, $rep, $pyInit);
			
			$cd->setPyInitial($pyInit);
			$dm->persist($cd);
		}
		
		
		$websiteDocs = $dm->getRepository('Application\Document\Website')->findAll();
		foreach($websiteDocs as $wd) {
			$name = $wd->getLabel();
			$pyInit = $zh->getInitials($name);
			$pyInit = strtolower($pyInit);
				
				
			$gap = array(" ","　","\t","\n","\r");
			$rep = array("","","","","");
			$pyInit = str_replace($gap, $rep, $pyInit);
				
			$wd->setPyInitial($pyInit);
			$dm->persist($wd);
		}
		
		$dm->flush();
		return new JsonModel(array());
	}
	
	public function editAction()
	{
		$id = $this->params()->fromRoute('id');
		$websiteId = $this->params()->fromRoute('website-id');
		$dm = $this->getServiceLocator()->get('DocumentManager');
		
		if(empty($id)) {
			$clientDoc = $dm->getRepository('Client\Document\Client')->findOneByWebsiteIds($websiteId);
		} else {
			$clientDoc = $dm->getRepository('Client\Document\Client')->findOneById($id);
		}
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