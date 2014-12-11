<?php
namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Domain extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string")  */
	protected $domainName;
	
	/** @ODM\Field(type="boolean")  */
	protected $isActive = true;
	
	/** @ODM\Field(type="boolean")  */
	protected $isDefault = false;
	
	/** @ODM\Field(type="string")  */
	protected $redirect;
	
	public function exchangeArray($data)
	{
		$this->domainName = $data['domainName'];
		if(isset($data['isActive'])) {
			$this->isActive = $data['isActive'];
		}
		if(isset($data['isDefault'])) {
			$this->isDefault = $data['isDefault'];
		}
	}
}