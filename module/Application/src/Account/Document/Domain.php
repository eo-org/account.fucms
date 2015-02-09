<?php
namespace Account\Document;

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
		if(isset($data['domainName'])) {
			$this->domainName = $data['domainName'];
		}
		if(isset($data['isActive'])) {
			$this->isActive = $data['isActive'];
		}
		if(isset($data['isDefault'])) {
			$this->isDefault = $data['isDefault'];
		}
		if(isset($data['redirect'])) {
			$this->redirect = $data['redirect'];
		}
	}
	
	public function getArrayCopy()
	{
		return array(
			'id' => $this->id,
			'domainName' => $this->domainName,
			'isActive' => $this->isActive,
			'isDefault' => $this->isDefault,
			'redirect' => $this->redirect
		);
	}
}