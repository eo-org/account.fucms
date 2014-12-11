<?php

namespace Sp\Form\User;

use Zend\Form\Form;

class RegisterForm extends Form
{
	public function __construct()
	{
		parent::__construct('user-register');
		
		$this->add(array(
			'name' => 'subdomainName',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => '子域名'
			)
		));
		$this->add(array(
			'name' => 'loginName',
			'attributes' => array(
				'type' => 'text'
			),
			'options' => array(
				'label' => '邮箱'
			)
		));
		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type' => 'password'
			),
			'options' => array(
				'label' => '密码'
			)
		));
		$this->add(array(
			'name' => 'password_2',
			'attributes' => array(
				'type' => 'password'
			),
			'options' => array(
				'label' => '再次输入密码'
			)
		));
		$this->add(array(
			'name' => 'register-button',
			'attributes' => array(
				'type' => 'submit',
				'id' => 'form-submit-button',
				'value' => '提交'
			)
		));
	}
}