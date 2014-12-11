<?php
return array(
	'controllers' => array(
		'invokables' => array(
			'Sp\UserController' => 'Sp\Controller\UserController'
		)
	),
	'router' => array(
		'routes' => array(
			'sp' => array(
				'type' => 'literal',
				'options' => array(
					'route' => '/sp',
					'defaults' => array(
						'controller' => 'Sp\UserController',
						'action' => 'login'
					)
				),
				'may_terminate' => true,
				'child_routes' => array(
					'login' => array(
						'type' => 'literal',
						'options' => array(
							'route' => '/login',
							'defaults' => array(
								'controller' => 'Sp\UserController',
								'action' => 'login'
							)
						),
						'may_terminate' => true
					),
					'remote-login' => array(
						'type' => 'literal',
						'options' => array(
							'route' => '/remote-login',
							'defaults' => array(
								'controller' => 'Sp\UserController',
								'action' => 'remote-login'
							)
						),
					),
					'logout' => array(
						'type' => 'literal',
						'options' => array(
							'route' => '/logout',
							'defaults' => array(
								'controller' => 'Sp\UserController',
								'action' => 'logout'
							)
						)
					)
				)
			)
		)
	),
	'view_manager' => array(
		'template_map' => array(
			'layout-sp/layout' => __DIR__ . '/../view/layout/layout.phtml',
			'sp/user/login' => __DIR__ . '/../view/sp/user/login.phtml',
			'sp/user/remote-login' => __DIR__ . '/../view/sp/user/remote-login.phtml',
			'sp/user/register' => __DIR__ . '/../view/sp/user/register.phtml'
		)
	),
	'service_manager' => array(
		'invokables' => array(
			'Sp\User' => 'Sp\FucmsSessionUser'
		)
	)
);