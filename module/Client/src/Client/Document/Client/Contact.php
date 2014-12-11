<?php
namespace Client\Document\Client;

use Application\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="client_contact"
 * )
 * */
class Contact extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string")  */
	protected $clientId;
	
	/** @ODM\Field(type="string")  */
	protected $name;
	
	/** @ODM\Field(type="string")  */
	protected $mobile;
	
	/** @ODM\Field(type="string")  */
	protected $qq;
	
	/** @ODM\Field(type="string")  */
	protected $email;
	
	/** @ODM\Field(type="string")  */
	protected $title;
	
	/** @ODM\Field(type="string")  */
	protected $address;
	
	/** @ODM\Field(type="string")  */
	protected $tel;
	
	public function exchangeArray($data)
	{
		$this->clientId = $data['clientId'];
		$this->name = $data['name'];
		$this->mobile = $data['mobile'];
	}
	
	public function getArrayCopy()
	{
		return array(
			'id' => $this->id,
			'clientId' => $this->clientId,
			'name' => $this->name,
			'mobile' => $this->mobile
		);
	}
}