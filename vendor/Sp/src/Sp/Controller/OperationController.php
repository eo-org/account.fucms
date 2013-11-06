<?php
namespace Sp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Sp\FucmsSessionAuth;
use Sp\FucmsSessionUser;
use Sp\Form\Operation\ChangePasswordForm;

class OperationController extends AbstractActionController
{
    public function logoutAction()
    {
    	$sm = $this->getServiceLocator();
    	
    	$fsUser = new FucmsSessionUser();
    	$fsAuth = new FucmsSessionAuth($sm);
    	
    	$fsAuth->logout($fsUser);
    }
    
    public function changePasswordAction()
    {
    	$form = new ChangePasswordForm();
    	
    	if($this->getRequest()->isPost()) {
//     		$postData = $this->getRequest()->getPost();
//     		$fsUser = new FucmsSessionUser();
//     		$fsAuth = new FucmsSessionAuth();
//     		$fsAuth->registerUser($fsUser, $postData);
    	}
    	
    	return array(
    		'form' => $form
    	);
    }
}