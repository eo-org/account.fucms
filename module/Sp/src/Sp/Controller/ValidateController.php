<?php
namespace Sp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel, Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Application\Document\Website;

class ValidateController extends AbstractActionController
{
	public function subdomainAvailableAction()
	{
		$sm = $this->getServiceLocator();
		$getData = $this->getRequest()->getQuery();
		$subdomainName = $getData['subdomainName'];
		
		$result = 'false';
		if(!empty($subdomainName)) {
			$dm = $sm->get('DocumentManager');
			$website = $dm->getRepository('Application\Document\Website')->findOneByUniqueSubdomain($subdomainName);
			
			if($website == false) {
				$result = 'true';
			}
		}
		
		$viewModel = new ViewModel(array('result' => $result));
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	
// 	public function loginNameAvailableAction()
// 	{
// 		$sm = $this->getServiceLocator();
// 		$getData = $this->getRequest()->getQuery();
// 		$loginName = $getData['loginName'];
	
// 		$result = 'false';
// 		if(!empty($loginName)) {
// 			$config = $sm->get('Config');
// 			$idpDomain = $config['env']['domain']['idp'];
			
// 			$loginNameAvailableUrl = 'http://'.$idpDomain.'/validate.json/login-name-available';
// 			$curl = curl_init($loginNameAvailableUrl);
// 			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// 			curl_setopt($curl, CURLOPT_POST, true);
// 			curl_setopt($curl, CURLOPT_POSTFIELDS, array('loginName' => $loginName));
// 			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			
// 			$json = curl_exec($curl);
			
// 			print_r($json);
// 			die();
// 			$jsonObj = Json::decode($json);
// 			print_r ($jsonObj);
// 			die();
// 		}
	
// 		$viewModel = new ViewModel(array('result' => $result));
// 		$viewModel->setTerminal(true);
// 		return $viewModel;
// 	}
}