<?php
return array(
	'controllers' => array(
        'invokables' => array(
        	'website'		=> 'Rest\Controller\WebsiteController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'website' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/website',
                    'defaults' => array(
                        'controller'    => 'website',
                        'action'        => 'index',
                    ),
                ),
            	'may_terminate' => true,
            	'child_routes' => array(
            		'actionroutes' => array(
            			'type' => 'segment',
            			'options' => array(
            				'route' => '[:controller][/:action]',
            				'constraints' => array(
            					'controller' => '[a-z-]*',
            					'action' => '[a-z-]*'
            				),
            				'defaults' => array(
            					'controller' => 'website',
            					'action' => 'index'
            				)
            			),
            			'may_terminate' => true,
            			'child_routes' => array(
            				'wildcard' => array(
            					'type' => 'wildcard'
            				)
            			)
            		)
            	)
            )
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
        	'application/index/create'	=> __DIR__ . '/../view/application/index/create.phtml',
        	'application/index/edit'	=> __DIR__ . '/../view/application/index/edit.phtml',
        	'application/index/server-status' => __DIR__ . '/../view/application/index/server-status.phtml'
        )
    )
);