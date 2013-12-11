<?php
namespace Sp\Form\User;

use Zend\InputFilter\InputFilter;

class RegisterFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
			'name' => 'subdomainName',
			'required' => true,
			'filters' => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
				array('name' => 'StringToLower')
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding'	=> 'UTF-8',
						'min'		=> 4,
						'max'		=> 20
					)
				),
				array(
					'name' => 'Regex',
					'options' => array(
						'pattern' => '/^[a-z]{1}[a-z0-9]*$/i'
					)
				)
			)
		));
		
		$this->add(array(
			'name' => 'loginName',
			'required' => true,
			'filters' => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
				array('name' => 'StringToLower')
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding'	=> 'UTF-8',
						'min'		=> 8,
						'max'		=> 40
					)
				),
				array(
					'name' => 'EmailAddress',
					'options' => array()
				)
			)
		));
		
		$this->add(array(
			'name' => 'password',
			'required' => true,
			'filters' => array(
				array('name' => 'StringTrim')
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding'	=> 'UTF-8',
						'min'		=> 6,
						'max'		=> 40
					)
				)
			)
		));
		
		$this->add(array(
			'name' => 'password_2',
			'required' => true,
			'filters' => array(
				array('name' => 'StringTrim')
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'encoding'	=> 'UTF-8',
						'min'		=> 6,
						'max'		=> 40
					)
				),
				array(
					'name' => 'identical',
					'options' => array(
						'token' => 'password'
					)
				)
			)
		));
	}
}