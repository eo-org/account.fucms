<?php
namespace ClientRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Client\Document\Payment;

class PaymentController extends AbstractRestfulController
{
	public function getList()
	{
		$clientId = $this->params()->fromQuery('clientId');
		
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$payments = $dm->createQueryBuilder('Client\Document\Payment')
			->select('purpose', 'totalAmount', 'status', 'created')
			->field('clientId')->equals($clientId)
			->sort('_id', -1)
			->getQuery()
			->execute();
		$pl = array();
		foreach($payments as $p) {
			$pl[] = $p->getArrayCopy();
		}
		$cp = array();
		if(count($pl) > 0) {
			$currentPaymentId = $pl[0]['id'];
			$dm->clear();
			$currentPayment = $dm->createQueryBuilder('Client\Document\Payment')
				->field('_id')->equals($currentPaymentId)
				->getQuery()
				->getSingleResult();
			$cp = $currentPayment->getArrayCopy();
			$settlementDocs = $currentPayment->getSettlements();
// 			$settlementArr = array();
// 			foreach($settlementDocs as $s) {
// 				$settlementArr[] = $s->getArrayCopy();
// 			}
			$cp['settlements'] = $currentPayment->getSettlements();
		}
		$data = array(
			'paymentList' => $pl,
			'currentPayment' => $cp
		);
		
		return new JsonModel($data);
	}
	
	public function get($id)
	{
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$payments = $dm->createQueryBuilder('Client\Document\Payment')
			->select('totalAmount', 'status', 'created')
			->getQuery()
			->execute();
		
		
		
		return new JsonModel($payments);
	}
	
	public function create($data)
	{
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$payment = new Payment();
		$payment->exchangeArray($data);
		$dm->persist($payment);
		$dm->flush();
		return new JsonModel($payment->getArrayCopy());
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