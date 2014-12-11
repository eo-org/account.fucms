<?php
namespace Rest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ServerController extends AbstractRestfulController
{
	public function getList()
	{
		return new JsonModel($result);
	}
	
	public function get($id)
	{
		return new JsonModel(array(
			'data' => $data,
			'opts' => $opts
		));
	}
	
	public function create($data)
	{
	}
	
	public function update($id, $data)
	{
	}
	
	public function delete($id)
	{
		$result = array();
		return new JsonModel($result);
	}
}