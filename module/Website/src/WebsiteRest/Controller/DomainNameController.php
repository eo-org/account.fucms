<?php
namespace WebsiteRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Account\Document\Domain;

class DomainNameController extends AbstractRestfulController
{
	public function getList()
	{
		
	}
	
	public function get($id)
	{
		
	}
	
	public function create($data)
	{
		$sm = $this->getServiceLocator();
		$dm = $sm->get('DocumentManager');
		
		$websiteId = $data['websiteId'];
		$websiteDoc = $dm->getRepository('Account\Document\Website')->findOneById($websiteId);
		
		$domainDoc = new Domain();
		$domainDoc->exchangeArray($data);
		
		$websiteDoc->addDomain($domainDoc);
		$dm->persist($websiteDoc);
		$dm->flush();
		
		return new JsonModel($domainDoc->getArrayCopy());
	}
	
	public function update($id, $data)
	{
		$sm = $this->getServiceLocator();
		$dm = $sm->get('DocumentManager');
		
		$websiteId = $this->params()->fromQuery('websiteId');
		$websiteDoc = $dm->getRepository('Account\Document\Website')->findOneById($websiteId);
		
		$websiteDoc->updateDomain($id, $data);
		$dm->persist($websiteDoc);
		$dm->flush();
		
		return new JsonModel(array(
			'id' => $id
		));
	}
	
	public function delete($id)
	{
		$sm = $this->getServiceLocator();
		$dm = $sm->get('DocumentManager');
		
		$websiteId = $this->params()->fromQuery('websiteId');
		$websiteDoc = $dm->getRepository('Account\Document\Website')->findOneById($websiteId);
		
		$websiteDoc->removeDomain($id);
		$dm->persist($websiteDoc);
		$dm->flush();
		
		return new JsonModel(array(
			'id' => $id
		));
	}
}