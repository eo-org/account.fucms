<?php

namespace Sp;

use SimpleXMLElement;
use Zend\Json\Json;

class FucmsLocalAuth
{
	public function __construct($sm)
	{
		$this->sm = $sm;
	}
	public function auth($user, $loginName, $password)
	{
		$dm = $this->sm->get('DocumentManager');
		$userDoc = $dm->createQueryBuilder('Cms\Document\Admin')->field('loginName')->equals($loginName)->field('password')->equals($password)->getQuery()->getSingleResult();
		if(is_null($userDoc)) {
			return false;
		} else {
			$roleId = $userDoc->getRoleId();
			if($roleId != 'Administrator') {
				$roleDoc = $dm->getRepository('Cms\Document\Admin\Role')->findOneById($roleId);
				if(is_null($roleDoc)) {
					throw new \Exception('permission not found with user:' . $userDoc->getLoginName());
				}
				$permissions = $roleDoc->getPermissions();
			} else {
				$permissions = '*';
			}
			
			return $user->localLogin($userDoc, $permissions);
		}
	}
}