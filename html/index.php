<?php
$requestHost = $_SERVER['HTTP_HOST'];
$localConfig = include '../config/autoload/local.php';

define("BASE_PATH", $localConfig['env']['base_path']);
define("HOST_NAME", $requestHost);

chdir(dirname(__DIR__));
include BASE_PATH.'/inc/Zend/Loader/StandardAutoloader.php';

$autoLoader = new Zend\Loader\StandardAutoloader(array(
    'namespaces' => array(
        'Zend'		=> BASE_PATH . '/inc/Zend',
    	'Doctrine'	=> BASE_PATH . '/inc/Doctrine',
    	'Core'		=> BASE_PATH . '/inc/Core'
    )
));
$autoLoader->register();

$application = Zend\Mvc\Application::init(include 'config/application.config.php')->run();