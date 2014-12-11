<?php

namespace Sp;

use ArrayObject;
use Zend\Session\Container;

abstract class SsoUser
{
	protected $_ssoUser = null;
	protected $_sessionContainerName = 'admin';
	public function __construct()
	{
		$this->_ssoUser = new Container($this->_sessionContainerName);
	}
// 	public function hasSSOToken()
// 	{
// 		return $this->hasSessionValue('sso-token');
// 	}
// 	public function getSSOToken()
// 	{
// 		if($this->hasSSOToken()) {
// 			return $this->getSessionValue('sso-token');
// 		} else {
// 			$token = md5(time() . rand(0, 10000));
// 			$this->setSessionValue('sso-token', $token);
// 			return $token;
// 		}
// 	}
	public function setSessionValue($key, $value)
	{
		$this->_ssoUser->offsetSet($key, $value);
	}
	public function getSessionValue($key)
	{
		return $this->_ssoUser->offsetGet($key);
	}
	public function hasSessionValue($key)
	{
		return $this->_ssoUser->offsetExists($key);
	}
	public function isLogin($boolValue = null)
	{
		if(is_null($boolValue)) {
			return $this->getSessionValue('isLogin');
		} else {
			$this->setSessionValue('isLogin', $boolValue);
		}
	}
	public function logout()
	{
		$this->_ssoUser->getManager()->getStorage()->clear($this->_sessionContainerName);
	}
	public function setUserData($dataArr, $value = null)
	{
		if(is_array($dataArr)) {
			$this->setSessionValue('userData', $dataArr);
		} else if(is_string($dataArr)) {
			$userData = $this->getUserData();
			$userData[$dataArr] = $value;
			$this->setSessionValue('userData', $userData);
		}
	}
	public function getUserData($key = null)
	{
		if($key == null) {
			$userData = $this->getSessionValue('userData');
			return $userData;
		} else {
			$userData = $this->getSessionValue('userData');
			if(isset($userData[$key])) {
				return $userData[$key];
			} else {
				return null;
			}
		}
		
		return null;
	}
	public function addUserData($key, $val)
	{
		$userData = $this->getUserData();
		$userData[$key] = $val;
		$this->setUserData($userData);
	}
	public function __toString()
	{
		$it = $this->_ssoUser->getIterator();
		$str = "";
		foreach($it as $key => $val) {
			if(is_array($val)) {
				$str .= $key . ' => {<br />';
				foreach($val as $k => $v) {
					$str .= '&nbsp;&nbsp;&nbsp;&nbsp;' . $k . ' => ' . $v . '<br />';
				}
				$str .= '}<br />';
			} else {
				$str .= $key . ' => ' . $val . '<br />';
			}
		}
		return $str;
	}
	abstract public function getServiceKey();
	abstract public function getServiceType();
	abstract public function hasPrivilege($permissonKey);
	abstract public function login($data);
}