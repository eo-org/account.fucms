<?php
return array(
	'controllers' => array(
        'invokables' => array(
            'Sp\IndexController'		=> 'Sp\Controller\IndexController',
        	'Sp\OperationController'	=> 'Sp\Controller\OperationController',
        	'Sp\UserController'			=> 'Sp\Controller\UserController',
        	'Sp\ValidateController'		=> 'Sp\Controller\ValidateController'
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
            		),
            		'validate' => array(
						'type'		=> 'segment',
            			'options'	=> array(
            				'route'		=> '/validate[/:action][.:format]',
            				'constraints' => array(
            					'action' => '[a-z-]*',
            					'format' => '(ajax|bone|iframe|html)',
            				),
            				'defaults'	=> array(
            					'controller'	=> 'Sp\ValidateController',
            					'format'		=> 'html'
            				)
            			)
					)
            	)
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
        	'sp/layout'			=> __DIR__ . '/../view/sp/layout/layout.phtml',
        	'sp/user/login'		=> __DIR__ . '/../view/sp/user/login.phtml',
        	'sp/user/register'	=> __DIR__ . '/../view/sp/user/register.phtml',
        	'sp/validate/subdomain-available' => __DIR__ . '/../view/sp/validate/terminal.phtml',
        	'sp/validate/login-name-available' => __DIR__ . '/../view/sp/validate/terminal.phtml',
        ),
    ),
);