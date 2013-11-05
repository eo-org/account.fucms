<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'Sp\IndexController'		=> 'Sp\Controller\IndexController',
        	'Sp\OperationController'	=> 'Sp\Controller\OperationController',
        	'Sp\UserController'			=> 'Sp\Controller\UserController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'sp' => array(
                'type'		=> 'literal',
                'options'	=> array(
                    'route'		=> '/sp',
                    'defaults'	=> array(
                        'controller'    => 'Sp\UserController',
                        'action'        => 'login',
                    ),
                ),
            	'may_terminate' => true,
            	'child_routes' => array(
            		'login' => array(
            			'type'		=> 'literal',
            			'options'	=> array(
            				'route'    => '/login',
            				'defaults' => array(
            					'controller'    => 'Sp\UserController',
            					'action'        => 'login',
            				),
            			),
            			'may_terminate' => true,
            		),
            		'register' => array(
            			'type'		=> 'literal',
            			'options'	=> array(
            				'route'    => '/register',
            				'defaults' => array(
            					'controller'    => 'Sp\UserController',
            					'action'        => 'register',
            				),
            			),
            			'may_terminate' => true,
            		),
            		'logout' => array(
            			'type'		=> 'literal',
            			'options'	=> array(
            				'route'		=> '/logout',
            				'defaults'	=> array(
            					'controller'	=> 'Sp\OperationController',
            					'action'		=> 'logout'
            				),
            			),
            		)
            	)
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
        	'sp/user/login'		=> __DIR__ . '/../view/sp/user/login.phtml',
        	'sp/user/register'	=> __DIR__ . '/../view/sp/user/register.phtml',
        ),
    ),
);