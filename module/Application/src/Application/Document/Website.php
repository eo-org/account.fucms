<?php
namespace Application\Document;

use Core\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="website"
 * )
 * */
class Website extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\ReferenceOne(targetDocument="Application\Document\Server") */
	protected $server;
	
	/** @ODM\Field(type="int") */
	protected $globalSiteId;
	
	/** @ODM\Field(type="string") */
	protected $userId;
	
	/** @ODM\Field(type="string") */
	protected $uniqueSubdomain;
	
	/** @ODM\EmbedMany(targetDocument="Application\Document\Domain")  */
	protected $domains = array();
	
	/** @ODM\Field(type="date") */
	protected $created;
	
	/** @ODM\Field(type="date") */
	protected $expireDate;
	
	/** @ODM\Field(type="int") */
	protected $storageCapacity = 10;
	
	/** @ODM\Field(type="boolean") */
	protected $trial = true;

	/** @ODM\Field(type="boolean") */
	protected $active = true;
	
	/** @ODM\Field(type="boolean") */
	protected $removed = false;
	
	public function exchangeArray($data)
	{
		$this->userId = $data['userId'];
		$this->uniqueSubdomain = $data['uniqueSubdomain'];
		
		if(is_null($this->created)) {
			$this->created = new \DateTime();
			$expireDate = new \DateTime();
			$expireDate->modify('+1 month');
			$this->expireDate = $expireDate;
		}
	}
	
	public function getArrayCopy()
	{
		return array(
			'userId' => $this->userId,
			'uniqueSubdomain' => $this->uniqueSubdomain,
			'created' => $this->created,
			'expireDate' => $this->expireDate,
			'storageCapacity' => $this->storageCapacity,
			'trial'	=> $this->trial,
			'active' => $this->active,
			'removed' => $this->removed
		);
	}
	
	public function addDomain($domainDocument)
	{
		$this->domains[] = $domainDocument;
		return $this;
	}
	
	public function removeDomain($id)
	{
		foreach($this->domains as $key => $domainDoc) {
			if($domainDoc->getId() == $id) {
				if($domainDoc->getIsDefault()) {
					return false;
				} else {
					unset($this->domains[$key]);
					return true;
				}
			}
		}
		return false;
	}
	
	/** @ODM\PrePersist */
	public function prePersist()
	{
		if($this->isNew()) {
			$dm = self::getObjectManager();
			
			$counterDoc = $dm->createQueryBuilder('Application\Document\Counter')
				->findAndUpdate()
				->field('name')->equals('website')
				->field('val')->inc(1)
				->getQuery()
				->execute();
			$this->globalSiteId = $counterDoc->getVal();
			
			$domainDoc = new \Application\Document\Domain();
			$domainDoc->exchangeArray(array(
				'domainName' => $this->uniqueSubdomain.'.fucmsweb.com',
				'isDefault' => true
			));
			$this->addDomain($domainDoc);
			
			$serverDoc = $dm->createQueryBuilder('Application\Document\Server')
				->findAndUpdate()
				->where("function() {return this.activeWebsiteCount < this.activeWebsiteLimit}")
				->limit(1)
				->sort('activeWebsiteCount')
				->field('activeWebsiteCount')->inc(1)
				->getQuery()
				->execute();
			
			$this->server = $serverDoc;
		}
	}
}