<?php
namespace Client\Document;

use Application\Document\AbstractDocument;
use Client\Document\Payment\Settlement;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="client_payment"
 * )
 * */
class Payment extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string")  */
	protected $clientId;
	
	/** @ODM\Field(type="string")  */
	protected $purpose;
	
	/** @ODM\Field(type="int")  */
	protected $totalAmount = 0;
	
	/** @ODM\Field(type="int")  */
	protected $amountSettled = 0;
	
	/** @ODM\Field(type="int")  */
	protected $amountOverdue = 0;
	
	/** @ODM\Field(type="int")  */
	protected $amountUncollectible = 0;
	
	/** @ODM\EmbedMany(targetDocument="Client\Document\Payment\Settlement")  */
	protected $settlements = array();
	
	/** @ODM\Field(type="string")  */
	protected $status = 'in-progress';
	
	/** @ODM\Field(type="date")  */
	protected $created;
	
	public function exchangeArray($data)
	{
		if(isset($data['clientId'])) {
			$this->clientId = $data['clientId'];
		}
		if(isset($data['purpose'])) {
			$this->purpose = $data['purpose'];
		}
		if(isset($data['totalAmount'])) {
			$this->totalAmount = $data['totalAmount'];
		}
		if($this->amountOverdue == 0) {
			$this->amountOverdue = $data['totalAmount'];
		}
		if(is_null($this->created)) {
			$this->created = new \DateTime();
		}
	}
	
	public function getArrayCopy()
	{
		return array(
			'id'				=> $this->id,
			'clientId'			=> $this->clientId,
			'purpose'			=> $this->purpose,
			'totalAmount'		=> $this->totalAmount,
			'amountSettled'		=> $this->amountSettled,
			'amountOverdue'		=> $this->amountOverdue,
			'amountUncollectible' => $this->amountUncollectible,
			'created'			=> $this->created
		);
	}
	
	public function addSettlement($label, $description, $amount, $operator)
	{
		$settlement = new Settlement();
		$settlement->exchangeArray(array(
			'label' => $label,
			'description' => $description,
			'amount' => $amount,
			'operator' => $operator
		));
		$this->settlements[] = $settlement;
		$this->amountSettled+= $amount;
		$this->amountOverdue-= $amount;
	}
	
	public function getSettlements()
	{
		$settlementArr = array();
		foreach($this->settlements as $s) {
			$settlementArr[] = $s->getArrayCopy();
		}
		return $settlementArr;
	}
}