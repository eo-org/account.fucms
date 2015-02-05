<?php
namespace WebsiteRest\Controller;

use MongoRegex;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Application\Document\Website;

class WebsiteController extends AbstractRestfulController
{
	public function getList()
	{
		$filter = $this->getRequest()->getQuery();
		$currentPage = intval($filter['page']);
		$sIndex = $filter['sIndex'];
		$sOrder = intval($filter['sOrder']);
		$label = $filter['label'];
		$author = $filter['author'];
		$status = $filter['status'];
		if(empty($currentPage)) {
			$currentPage = 1;
		}
		if(empty($sOrder)) {
			$sOrder = - 1;
		}
		if(empty($sIndex)) {
			$sIndex = '_id';
		}
		
		$pageSize = 20;
		$skip = $pageSize * ($currentPage - 1);
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$qb = $dm->createQueryBuilder('Application\Document\Website')->select('id', 'active', 'created', 'globalSiteId', 'label', 'expireDate');

		if(!empty($label)) {
			$qb->field("label")->equals(new MongoRegex("/" . $label . "/"));
		}
		
		$cursor = $qb->limit($pageSize)->skip($skip)->sort($sIndex, $sOrder)->hydrate(false)->getQuery()->execute();
		$data = array();
		foreach($cursor as $val) {
			$data[] = $val;
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
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$doc = $dm->getRepository('Application\Document\Website')->findOneById($id);
		
		$data = $doc->getArrayCopy();
		return new JsonModel($data);
	}
	
	public function create($data)
	{
		$sm = $this->getServiceLocator();
		$dm = $sm->get('DocumentManager');
		
		$websiteDoc = new Website();
		$websiteDoc->exchangeArray($data);
		
		$dm->persist($websiteDoc);
		$dm->flush();
		
		return new JsonModel(array(
			'id' => $websiteDoc->getId()
		));
	}
	
	public function update($id, $data)
	{
		$dm = $this->getServiceLocator()->get('DocumentManager');
		$doc = $dm->getRepository('Application\Document\Website')->findOneById($id);
		
		$doc->exchangeArray($data);
		$dm->persist($doc);
		$dm->flush();
		return new JsonModel($data);
	}
	
	public function delete($id)
	{
		
	}
}