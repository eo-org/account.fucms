<?php
namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="counter"
 * )
 * */
class Counter extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="int")  */
	protected $val;
	
	public function getArrayCopy()
	{
		return array(
			'val' => $this->val
		);
	}
}