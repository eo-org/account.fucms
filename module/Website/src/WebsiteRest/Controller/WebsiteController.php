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
// 		if(empty($status) || $status == "all"){
// 			$qb->field("status")->notEqual('trash');
// 		}else if(!empty($status)){
// 			$qb->field("status")->equals($status);
// 		}
// 		if(!empty($filter['groupId']) && $filter['groupId'] != 'all') {
// 			$qb->field("groupId")->equals($filter['groupId']);
// 		}
		if(!empty($label)) {
			$qb->field("label")->equals(new MongoRegex("/" . $label . "/"));
		}
// 		if(!empty($author)){
// 			$qb->field("author.name")->equals(new MongoRegex("/" . $author . "/"));
// 		}
		
// 		$csd = $this->getServiceLocator()->get('Cms\Service\DocumentEvents');
// 		$csd->triggerEvent('query.pre', $qb);
		
		$cursor = $qb->limit($pageSize)->skip($skip)->sort($sIndex, $sOrder)->hydrate(false)->getQuery()->execute();
		$data = array();//$this->formatData($cursor);
		foreach($cursor as $val) {
			//echo $val;
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
		if($id == 'opts') {
			$data = array();
		} else {
			$dm = $this->getServiceLocator()->get('DocumentManager');
			$doc = $dm->getRepository('Cms\Document\Article')->findOneById($id);
			$data = $doc->getArrayCopy();
		}
		
		$opts = (object) array();
		$optsEvents = $this->getServiceLocator()->get('Cms\Service\OptsEvents');
		$optsEvents->triggerEvent('opts.getArticleOpts', $opts);
		
		return new JsonModel(array(
			'data' => $data,
			'opts' => $opts
		));
	}
	
	public function create($data)
	{
		$sm = $this->getServiceLocator();
// 		$user = $sm->get('Sp\User');
//		$adminId = $user->getUserData('id');
		$dm = $sm->get('DocumentManager');
		
		$websiteDoc = new Website();
		$websiteDoc->exchangeArray($data);
		
		$dm->persist($websiteDoc);
		$dm->flush();
//		$adminDoc = $dm->getRepository('Cms\Document\Admin')->findOneById($adminId);
// 		$author = array('id' => $user->getUserData('id'), 'name' => $user->getUserData('loginName'));
// 		$doc = new Article();
// 		$doc->exchangeArray($data);
// 		$currentDateTime = new \DateTime();
// 		$doc->setAuthor($author);
// 		$doc->setCreated($currentDateTime);
// 		$doc->setModified($currentDateTime);
// 		$csd = $this->getServiceLocator()->get('Cms\Service\DocumentEvents');
// 		$csd->triggerEvent('create.pre', $doc);
		
		return new JsonModel(array(
			'id' => $websiteDoc->getId()
		));
	}
	
	public function update($id, $data)
	{
		$sm = $this->getServiceLocator();
		$user = $sm->get('Sp\User');
// 		$adminId = $adminUsers->getUserData('id');
		$dm = $sm->get('DocumentManager');
		$result = array();
		if($id == 'batches'){
			if($adminUsers->hasPrivilege('article.status')){
				$dm->createQueryBuilder('Cms\Document\Article')
					->update()->multiple(true)
					->field('id')->in($data)
					->field('status')->set('pending')
					->getQuery()->execute();
				$result['status'] = 'success';
				$result['info']	= '还原文章成功！';				
			}else {
				$result['status'] = 'error';
				$result['info']	= '暂时没有权限恢复文章，请联系管理员!';
			}
			return new JsonModel($result);
		} else {
			$doc = $dm->getRepository('Cms\Document\Article')->findOneById($id);
			if($adminUsers->hasPrivilege('article.status') || $doc->getStatus() != 'publish'){
				$doc->exchangeArray($data);
				if(!$doc->getAuthor()){
// 					$adminDoc = $dm->getRepository('Cms\Document\Admin')->findOneById($adminId);
					$author = array('id' => $user->getUserData('id'), 'name' => $user->getUserData('loginName'));
					$doc->setAuthor($author);
				}
				$currentDateTime = new \DateTime();
				$doc->setModified($currentDateTime);
				$csd = $this->getServiceLocator()->get('Cms\Service\DocumentEvents');
				$csd->triggerEvent('create.pre', $doc);
				$dm->persist($doc);
				$dm->flush();
			}
			return new JsonModel(array(
				'id' => $id
			));
		}		
	}
	
	public function delete($id)
	{
		$result = array();
		$sm = $this->getServiceLocator();
		$dm = $sm->get('DocumentManager');
		$adminUsers = $sm->get('Sp\User');
		$filter = $this->getRequest()->getQuery();
		if($id == 'batches'){
			$qb = $dm->createQueryBuilder('Cms\Document\Article')->remove()->multiple(true);			
			if($adminUsers->hasPrivilege('article.delete')){
				if($filter['ids'] != 'all'){
					$ids = explode(',',$filter['ids']);
					$qb->field('id')->in($ids)->getQuery()->execute();
					$result['info']	= count($ids).'篇文章删除成功!';
				}else {
					$qb->field('status')->equals('trash')->getQuery()->execute();
					$result['info']	= '文章回收站已清空!';
				}
				$result['status'] = 'success';
			}else{
				$result['status'] = 'error';
				$result['info']	= '暂时没有权限删除文章，请联系管理员!';
			}
		}else {
			$doc = $dm->getRepository('Cms\Document\Article')->findOneById($id);
			if($adminUsers->hasPrivilege('article.delete') || $doc->getStatus() != 'publish'){
				$qb = $dm->createQueryBuilder('Cms\Document\Article');
				if($doc->getStatus() != 'trash'){
					$qb->update()->field('id')->equals($id)->field('status')->set('trash')->getQuery()->execute();
				}else {
					$qb->remove()->field('id')->equals($id)->getQuery()->execute();
				}
				$result['status'] = 'success';
				$result['info']	= '文章《'.$doc->getLabel().'》删除成功!';
			}else if(!$adminUsers->hasPrivilege('article.delete') || $doc->getStatus() != 'pending'){
				$result['status'] = 'error';
				$result['info']	= '暂时没有权限删除文章，请联系管理员!';
			}
		}		
		return new JsonModel($result);
	}
}