<?php
namespace Account\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="server"
 * )
 * */
class Server extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string") */
	protected $name;

	/** @ODM\Field(type="string") */
	protected $ipAddress;
	
	/** @ODM\Field(type="string") */
	protected $internalIpAddress;
	
	/** @ODM\Field(type="int") */
	protected $activeWebsiteLimit;
	
	/** @ODM\Field(type="int") */
	protected $activeWebsiteCount;
	
	/** @ODM\Field(type="float") */
	protected $version;
	
	public function exchangeArray($data)
	{
		$this->name = $data['name'];
		
		$this->ipAddress = $data['ipAddress'];
		
		$this->activeWebsiteLimit = $data['activeWebsiteLimit'];
		
		if($this->activeWebsiteLimit == null) {
			$this->activeWebsiteLimit = 0;
		}
		
		$this->version = $data['version'];
	}
	
	public function getArrayCopy()
	{
		return array(
			'name' => $this->name
		);
	}
}