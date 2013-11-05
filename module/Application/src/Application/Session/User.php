<?php
namespace Application\Session;

use Zend\Session\Container;

class User
{	
	protected $sessionContainerName = 'sso\eo_user';
	protected $container;
	protected $userData;
	
	protected $dm;
	protected $websiteDocs;
	
	public function __construct($dm)
	{
		$this->dm = $dm;
		$this->container = new Container($this->sessionContainerName);
		$this->userData = $this->container->offsetGet('userData');
	}
	
	public function getUserId()
	{
		return $this->userData['userId'];
	}
	
	public function getUserLoginName()
	{
		return $this->userData['userLoginName'];
	}
	
	public function getWebsiteDocs()
	{
		if($this->websiteDocs == null) {
			$userId = $this->getUserId();
	 		$websiteDocs = $this->dm->getRepository('Application\Document\Website')->findByUserId($userId);
			$this->websiteDocs = $websiteDocs;
		}
		
		return $this->websiteDocs;
	}
}