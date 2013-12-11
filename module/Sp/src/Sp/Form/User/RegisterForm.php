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
    			'type' => 'text',
    			'id' => 'subdomainName',
    			'class' => 'input-element hint-element',
    			'value' => '由4个以上字母和数字组成',
    			'data-hint' => '由4个以上字母和数字组成',
    			'data-type' => 'text'
    		),
    		'options' => array(
    			'label' => '子域名'
    		)
    	));
    	$this->add(array(
    		'name' => 'loginName',
    		'attributes' => array(
    			'type' => 'text',
    			'id' => 'loginName',
    			'class' => 'input-element hint-element',
    			'value' => '请输入常用邮箱',
    			'data-hint' => '请输入常用邮箱',
    			'data-type' => 'text'
    		),
    		'options' => array(
    			'label' => '登录名'
    		)
    	));
    	$this->add(array(
    		'name' => 'password',
    		'attributes' => array(
    			'type' => 'password',
    			'id' => 'password',
    			'class' => 'input-element'
    		),
    		'options' => array(
    			'label' => '请设置密码'
    		)
    	));
    	$this->add(array(
    		'name' => 'password_2',
    		'attributes' => array(
    			'type' => 'password',
    			'id' => 'password_2',
    			'class' => 'input-element'
    		),
    		'options' => array(
    			'label' => '再确认密码'
    		)
    	));
    	$this->add(array(
    		'name' => 'register-button',
    		'attributes' => array(
    			'type' => 'submit',
    			'class' => 'form-submit-button',
    			'value' => '立即注册'
    		)
    	));
    }
}