<?php
namespace Account\Document;

use Zend\InputFilter\InputFilterAwareInterface, Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Persistence\PersistentObject;

class AbstractDocument extends PersistentObject implements InputFilterAwareInterface
{
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}
	
	public function getInputFilter()
	{
		throw new \Exception("Not used");
	}
}