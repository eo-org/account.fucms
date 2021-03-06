<?php
namespace Sp;

use Exception;
use SimpleXMLElement;
use Zend\Json\Json;
use Zend\Session\Container;
use Sp\SsoUser;
use Cms\Document\Admin;

class FucmsSessionUser extends SsoUser
{
	private static $_md5salt = 'Hgoc&639Jgo';
	private static $_md5salt2 = 'jiohGY6&*9';
	protected $_sessionContainerName = 'sso\eo_user';
	
	public function getServiceType()
	{
		return 'cms';
	}
	
	public function getServiceKey()
	{
		return 'zvmiopav7BbuifbahoUifbqov541huog5vua4ofaweafeq98fvvxreqh';
	}
	
	public function localLogin($userData, $permissions)
	{
		if($userData instanceof Admin) {
			$user = $userData;
		} else {
			return false;
		}
		
		$startTimeStamp = time();
		$userDataArr = array();
		$userDataArr['id'] = $user->getId();
		$userDataArr['loginName'] = $user->getLoginName();
		$userDataArr['roleId'] = $user->getRoleId();
		$userDataArr['permissions'] = $permissions;
		
		$this->setUserData($userDataArr);
		$this->isLogin(true);
		return true;
	}
	
	public function login($xml)
	{
		if($xml instanceof SimpleXMLElement) {
			$user = $xml;
		}
		if(is_null($user)) {
			return false;
		}
		$startTimeStamp = time();
		$userDataArr = array();
		foreach($user->children() as $tag => $val) {
			$userDataArr[$tag] = ( string ) $val;
		}
		$userDataArr['permissions'] = '*';
		
		$this->setUserData($userDataArr);
		
		$this->isLogin(true);
		return true;
	}
	
	public function getLoginUrl()
	{
		return 'http://' . $_SERVER["SERVER_NAME"] . '/sp/login';
	}
	
	public function getServerHost()
	{
		return 'http://' . $_SERVER["SERVER_NAME"];
	}
	
	public function setRedirect($redirectUrl)
	{
		$this->setSessionValue('redirect', $redirectUrl);
	}
	
	public function getRedirectUrl()
	{
		if($this->hasSessionValue('redirect')) {
			$redirect = $this->getSessionValue('redirect');
		} else {
			$redirect = '/';
		}
		return $redirect;
	}
	
	public function hasPrivilege($permissonKey)
	{
		$permissons = $this->getUserData('permissions');
		
		if($permissons == '*') {
			return true;
		}
		
		return in_array($permissonKey, $permissons);
	}
	
	public function getHomeLocation()
	{
		return '/';
	}
}