<?php
namespace Sp\Form\User;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct()
    {
    	parent::__construct('user-login');
    	
    	$this->add(array(
    		'name' => 'loginName',
    		'attributes' => array(
    			'type' => 'text',
    			'class' => 'input-element hint-element',
    			'value' => '请输入您注册时使用的邮箱',
    			'data-hint' => '请输入您注册时使用的邮箱',
    			'data-type' => 'email'
    		)
    	));
    	$this->add(array(
    		'name' => 'password',
    		'attributes' => array(
    			'type' => 'text',
    			'class' => 'input-element hint-element',
    			'value' => '请输入您的登录密码',
    			'data-hint' => '请输入您的登录密码',
    			'data-type' => 'password'
    		)
    	));
    	
    	$this->add(array(
    		'name' => 'login-button',
    		'attributes' => array(
    			'type' => 'submit',
    			'class' => 'form-submit-button',
    			'value' => '提交'
    		)
    	));
    }
}