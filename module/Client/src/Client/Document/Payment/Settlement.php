<?php
namespace Client\Document\Payment;

use Application\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class Settlement extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string")  */
	protected $label;
	
	/** @ODM\Field(type="string")  */
	protected $description;
	
	/** @ODM\Field(type="int")  */
	protected $amount = 0;
	
	/** @ODM\Field(type="string")  */
	protected $operator;
	
	/** @ODM\Field(type="date")  */
	protected $created;
	
	public function exchangeArray($data)
	{
		$this->label = $data['label'];
		$this->description = $data['description'];
		$this->amount = $data['amount'];
		$this->operator = $data['operator'];
		if(is_null($this->id)) {
			$this->created = new \DateTime();
		}
	}
	
	public function getArrayCopy()
	{
		return array(
			'label' => $this->label,
			'description' => $this->description,
			'amount' => $this->amount,
			'created' => $this->created,
			'operator' => $this->operator
		);
	}
}