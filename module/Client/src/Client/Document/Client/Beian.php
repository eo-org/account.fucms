<?php
namespace Application\Document;

use Application\Document\AbstractDocument;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** 
 * @ODM\Document(
 * 		collection="beian"
 * )
 * */
class Beian extends AbstractDocument
{
	/** @ODM\Id */
	protected $id;
	
	/** @ODM\Field(type="string") */
	protected $websiteId;
	
}