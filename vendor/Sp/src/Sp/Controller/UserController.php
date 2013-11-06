<?php
namespace Sp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Sp\FucmsSessionAuth;
use Sp\FucmsSessionUser;
use Sp\Form\User\RegisterForm;
use Application\Document\Website;

class UserController extends AbstractActionController
{
    public function loginAction()
    {
    	$sm = $this->getServiceLocator();
    	
    	$getData = $this->getRequest()->getQuery();
    	
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
    }
    
    public function registerAction()
    {
    	$sm = $this->getServiceLocator();
    	
    	$form = new RegisterForm();
    	
    	if($this->getRequest()->isPost()) {
    		$postData = $this->getRequest()->getPost();
    		$subdomainName = $postData['subdomainName'];
    		$fsAuth = new FucmsSessionAuth($sm);
    		$response = $fsAuth->registerUser($postData);
    		if($response->result == true) {
    			//send validation email
    			
    			
    			//create default website, first time login give some hint!!
    			$dm = $sm->get('DocumentManager');
		    	$website = new Website();
		    	$website->exchangeArray(array(
		    		'userId'			=> $response->userId,
		    		'uniqueSubdomain'	=> $subdomainName,
		    	));
		    	$dm->persist($website);
		    	$dm->flush();
		    	
		    	$loginName = $postData['loginName'];
		    	$loginPass = $postData['password'];
		    	$fsUser = new FucmsSessionUser();
		    	$fsAuth->requestToken($fsUser, $loginName, $loginPass);
    		}
    		
    	}
    	
    	return array(
    		'form' => $form
    	);
    }
}