<?php
namespace Sp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Sp\FucmsLocalAuth;
use Sp\FucmsRemoteAuth;
use Sp\Form\User\RegisterForm;
use Application\Document\Website;

class UserController extends AbstractActionController
{
	public function loginAction()
	{
		$sm = $this->getServiceLocator();
		$dm = $sm->get('DocumentManager');
		
		$fsUser = $sm->get('Sp\User');
		$fsAuth = new FucmsLocalAuth($sm);
		
		if(isset($getData['tokenReady']) && $getData['tokenReady'] == 'ready') {
			$fsAuth->readToken($fsUser);
		}
		
		if($this->getRequest()->isPost()) {
			$postData = $this->getRequest()->getPost();
			$loginName = $postData['loginName'];
			$password = $postData['password'];
			$result = $fsAuth->auth($fsUser, $loginName, $password);
			if($result) {
				return $this->redirect()->toRoute('admin/actionroutes/wildcard', array(
					'action' => 'index',
					'controller' => 'index'
				));
			} else {
				return array(
					'info' => '用户名或密码错误，请重新填写！'
				);
			}
		}
		return array();
	}
	
	public function remoteLoginAction()
	{
		$sm = $this->getServiceLocator();
		
		$getData = $this->getRequest()->getQuery();
		$fsUser = $sm->get('Sp\User');
		
		$fsAuth = new FucmsRemoteAuth($sm);
		
		if(isset($getData['tokenReady']) && $getData['tokenReady'] == 'ready') {
			$fsAuth->readToken($fsUser);
		} else {
			$fsAuth->requestToken($fsUser);
		}
		return array();
	}
	
	public function logoutAction()
	{
		$sm = $this->getServiceLocator();
		$fsUser = $sm->get('Sp\User');
		
		$fsUser->logout();
		
		return $this->redirect()->toRoute('sp/login');
	}
}