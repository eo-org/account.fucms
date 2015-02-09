<?php
namespace ClientRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use MongoRegex;
use Client\Document\Client;

class ClientController extends AbstractRestfulController
{
	public function getList()
	{
		$filter = $this->getRequest()->getQuery();
		$pyInitial = $filter['pyi'];
		
		$pageSize = 20;
		$currentPage = 1;
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$qb = $dm->createQueryBuilder('Client\Document\Client')
			->field('pyInitial')->equals(new MongoRegex("/" . $pyInitial . "/"));
			
		$cursor =$qb->hydrate(false)
			->getQuery()
			->execute();
		$data = array();
		foreach($cursor as $client) {
			$key = $client['_id']->{'$id'};
			unset($client['_id']);
			$data[$key] = $client;
		}
		$dataSize = intval($qb->getQuery()->execute()->count());
		$maxPage = ceil($dataSize / $pageSize);
		$pageOption = array();
		for($i = 1; $i <= $maxPage; $i ++) {
			$pageOption[] = $i;
		}
		$result = array();
		$result['data'] = $data;
		$result['dataSize'] = $dataSize;
		$result['pageSize'] = $pageSize;
		$result['currentPage'] = $currentPage;
		$result['maxPage'] = $maxPage;
		$result['pageOption'] = $pageOption;
		return new JsonModel($result);
	}
	
	public function get($id)
	{
		
	}
	
	public function create($data)
	{
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$contact = new Client();
		$contact->exchangeArray($data);
		
		$dm->persist($contact);
		$dm->flush();
		return new JsonModel($contact->getArrayCopy());
	}
	
	public function update($id, $data)
	{
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$clientDoc = $dm->getRepository('Client\Document\Client')->findOneById($id);
		if(isset($data['bindWebsite'])) {
			$clientDoc->addWebsiteId($data['websiteId']);
		}
		
		$dm->persist($clientDoc);
		$dm->flush();
		
		return new JsonModel(array('id' => $clientDoc->getId()));
		
// 		$user = $this->getServiceLocator()->get('Sp\User');
// 		$opearator = $user->getUserData('loginName');
		
// 		$dm = $this->getServiceLocator()->get('DocumentManager');
// 		$paymentDoc = $dm->getRepository('Client\Document\Payment')->findOneById($id);
// 		$paymentDoc->addSettlement($data['label'], "", $data['amount'], $opearator);
// 		$dm->persist($paymentDoc);
// 		$dm->flush();
		
// 		$paymentArr = $paymentDoc->getArrayCopy();
// 		$paymentArr['settlements'] = $paymentDoc->getSettlements();
		
// 		return new JsonModel($paymentArr);
	}
	
	public function delete($id)
	{
		$result = array();
		return new JsonModel($result);
	}
}