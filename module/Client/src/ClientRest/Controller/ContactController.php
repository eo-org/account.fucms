<?php
namespace ClientRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Client\Document\Client\Contact;

class ContactController extends AbstractRestfulController
{
	public function getList()
	{
		$clientId = $this->params()->fromQuery('clientId');
		
		$dm = $this->getServiceLocator()->get('DocumentManager');
		
		/*
		 * @todo: return array with natural keys!
		 */
		$contacts = $dm->createQueryBuilder('Client\Document\Client\Contact')
			->field('clientId')->equals($clientId)
			->sort('_id', -1)
			->hydrate(false)
			->getQuery()
			->execute();
		
		return new JsonModel($contacts);
	}
	
	public function get($id)
	{
		
	}
	
	public function create($data)
	{
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$contact = new Contact();
		$contact->exchangeArray($data);
		$dm->persist($contact);
		$dm->flush();
		return new JsonModel($contact->getArrayCopy());
	}
	
	public function update($id, $data)
	{
		$user = $this->getServiceLocator()->get('Sp\User');
		$opearator = $user->getUserData('loginName');
		
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$paymentDoc = $dm->getRepository('Client\Document\Payment')->findOneById($id);
		$paymentDoc->addSettlement($data['label'], "", $data['amount'], $opearator);
		$dm->persist($paymentDoc);
		$dm->flush();
		
		$paymentArr = $paymentDoc->getArrayCopy();
		$paymentArr['settlements'] = $paymentDoc->getSettlements();
		
		return new JsonModel($paymentArr);
	}
	
	public function delete($id)
	{
		$result = array();
		return new JsonModel($result);
	}
}