<?php

namespace Sp\Form\Operation;

use Zend\Form\Form;

class ChangePasswordForm extends Form
{
	public function __construct()
	{
		parent::__construct('user-change-password');
		
		$this->add(array(
			'name' => 'password_old',
			'attributes' => array(
				'type' => 'password'
			),
			'options' => array(
				'label' => '原密码'
			)
		));
		$this->add(array(
			'name' => 'password',
			'attributes' => array(
				'type' => 'password'
			),
			'options' => array(
				'label' => '新密码'
			)
		));
		$this->add(array(
			'name' => 'password_2',
			'attributes' => array(
				'type' => 'password'
			),
			'options' => array(
				'label' => '再次输入新密码'
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