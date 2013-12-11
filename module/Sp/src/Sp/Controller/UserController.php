<?php
namespace Sp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Sp\FucmsSessionAuth;
use Sp\FucmsSessionUser;
use Sp\Form\User\RegisterForm, Sp\Form\User\LoginForm, Sp\Form\User\RegisterFilter;
use Application\Document\Website;

class UserController extends AbstractActionController
{
    public function loginAction()
    {
    	$sm = $this->getServiceLocator();
    	$form = new LoginForm();
    	
    	$errorMsg = null;
    	$requestLoginName = null;
    	
    	$getData = $this->getRequest()->getQuery();
    	if(isset($getData['errorCode'])) {
    		switch($getData['errorCode']) {
    			case 'user-not-found':
    				$errorMsg = '用户没有注册,您可以免费注册一个新用户';
    				break;
    			case 'password-not-match':
    				$errorMsg = '用户密码错误,您可以通过邮件找回密码';
    				break;
    		}
    		$requestLoginName = $getData['requestLoginName'];
    		$form->setData(array('loginName' => $requestLoginName));
    	}
    	$fsUser = new FucmsSessionUser();
    	$fsAuth = new FucmsSessionAuth($sm);
    	
    	if(isset($getData['tokenReady']) && $getData['tokenReady'] == 'ready') {
    		$fsAuth->readToken($fsUser);
    	}
    	
    	if($this->getRequest()->isPost()) {
    		$postData = $this->getRequest()->getPost();
    		$loginName = $postData['loginName'];
    		$password = $postData['password'];
    		$fsAuth->requestToken($fsUser, $loginName, $password);
    	}
    	
    	return array(
    		'form' => $form,
    		'errorMsg' => $errorMsg
    	);
    }
    
    /*
     * case ERROR
     * 1.check form
     * break
     * 
     * 2.register user on idp
     * break
     * 
     * 3.subdomain name not available, use random subdomain
     * break
     * 
     * case SUCCESS
     * 1.get register info from idp
     * 
     * 2.create local web service
     */
    
    public function registerAction()
    {
    	$sm = $this->getServiceLocator();
    	$form = new RegisterForm();
    	$form->setInputFilter(new RegisterFilter());
    	
    	$errorMsg = null;
    	
    	if($this->getRequest()->isPost()) {
    		$postData = $this->getRequest()->getPost();
    		
    		$form->setData($postData);
    		$subdomainName = $postData['subdomainName'];
    		if($form->isValid()) {
    			$fsAuth = new FucmsSessionAuth($sm);
	    		$response = $fsAuth->registerUser($postData);
	    		if($response->result == 'success') {
	    			/**
	    			 * @todo send validation email
	    			 */
	    			//create default website, first time login give some hint!!
	    			$dm = $sm->get('DocumentManager');
	    			$subdomainName = $postData['subdomainName'];
	    			$website = $dm->getRepository('Application\Document\Website')->findOneByUniqueSubdomain($subdomainName);
	    			
	    			if($website != false) {
	    				$subdomainName = md5(time());
	    				$website->exchangeArray(array(
	    					'userId'			=> $response->userId,
	    					'uniqueSubdomain'	=> $subdomainName,
	    				));
	    			} else {
	    				$website = new Website();
						$website->exchangeArray(array(
							'userId'			=> $response->userId,
							'uniqueSubdomain'	=> $subdomainName,
						));
	    			}
	    			$dm->persist($website);
	    			$dm->flush();
	    			
			    	$loginName = $postData['loginName'];
			    	$loginPass = $postData['password'];
			    	$fsUser = new FucmsSessionUser();
			    	$fsAuth->requestToken($fsUser, $loginName, $loginPass);
	    		} else {
	    			$errorCode = $response->errorCode;
	    			if($errorCode == 'user-existed') {
	    				$errorMsg = '用户邮箱已被注册';
	    			}
	    		}
    		}
    	}
    	return array(
    		'form' => $form,
    		'errorMsg' => $errorMsg
    	);
    }
}