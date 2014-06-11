<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'Application\IndexController' => 'Application\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'application' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller'    => 'Application\IndexController',
                        'action'        => 'index',
                    ),
                ),
            	'may_terminate' => true,
            	'child_routes' => array(
            	)
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'error/404'					=> __DIR__ . '/../view/error/404.phtml',
            'error/index'				=> __DIR__ . '/../view/error/index.phtml',
        	'layout/error'				=> __DIR__ . '/../view/layout/error.phtml',
        	'layout/layout'				=> __DIR__ . '/../view/layout/layout.phtml',
        	'application/index/index'	=> __DIR__ . '/../view/application/index/index.phtml',
        ),
    	'strategies' => array(
    		'ViewJsonStrategy'
    	),
    ),
	'view_helpers' => array(
		'invokables' => array(
			'path'					=> 'Application\View\Helper\Path',
		),
	),
	'service_manager' => array(
		'factories' => array('ConfigObject\EnvironmentConfig' => function($serviceManager) {
			$siteConfig = new Application\EnvConfig(include 'config/env.config.php');
			return $siteConfig;
		})
	),
);