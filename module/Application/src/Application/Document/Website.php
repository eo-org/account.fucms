<?php
namespace Application\Document;

use Application\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Core\Zh;

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
	protected $uniqueSubdomain;
	
	/** @ODM\Field(type="string") */
	protected $label;
	
	/** @ODM\Field(type="string") */
	protected $pyInitial = "";
	
	/** @ODM\EmbedMany(targetDocument="Application\Document\Domain")  */
	protected $domains = array();
	
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
		print_r($data);
		
		
		if(isset($data['uniqueSubdomain'])) {
			$this->uniqueSubdomain = $data['uniqueSubdomain'];
		}
		if(isset($data['label'])) {
			$this->label = $data['label'];
			$zh = new Zh();
			$this->pyInitial = $zh->getInitials($data['label']);
		}
		if(empty($this->id)) {
			$this->expireDate = new \DateTime();
		}
		
		
	}
	
	public function getArrayCopy()
	{
		return array(
			'globalSiteId' => $this->globalSiteId,
			'label' => $this->label,
			'uniqueSubdomain' => $this->uniqueSubdomain,
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
			->where("this.activeWebsiteCount < this.activeWebsiteLimit")
			->limit(1)
			->sort('activeWebsiteCount', 1)
			->field('activeWebsiteCount')->inc(1)
			->getQuery()
			->execute();
		$this->server = $serverDoc;
	}
}