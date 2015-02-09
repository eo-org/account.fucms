<?php
namespace Client\Document;

use Core\Zh;
use Application\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="client"
 * )
 * */
class Client extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string") */
	protected $companyName = "";
	
	/** @ODM\Field(type="string") */
	protected $pyInitial = "";
	
	/** @ODM\Field(type="hash") */
	protected $websiteIds = array();
	
	public function exchangeArray($data)
	{
		if(isset($data['companyName'])) {
			$this->companyName = $data['companyName'];
			$zh = new Zh();
			$this->pyInitial = $zh->getInitials($data['companyName']);
		}
		if(isset($data['websiteId'])) {
			$this->addWebsiteId($data['websiteId']);
		}
	}
	
	public function getArrayCopy()
	{
		return array(
			'companyName' => $this->companyName
		);
	}
	
	public function addWebsiteId($websiteId)
	{
		if(is_string($websiteId)) {
			$this->websiteIds[] = $websiteId;
		}
	}
}