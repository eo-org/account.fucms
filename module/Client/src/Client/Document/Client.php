<?php
namespace Client\Document;

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
	
	/** @ODM\Field(type="hash") */
	protected $websiteIds = array();
	
	public function exchangeArray($data)
	{
		if(isset($data['companyName'])) {
			$this->companyName = $data['companyName'];
		}
	}
	
	public function getArrayCopy()
	{
		return array(
			'companyName' => $this->companyName,
		);
	}
}