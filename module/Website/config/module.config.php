<?php
return array(
	'controllers' => array(
        'invokables' => array(
        	'website'			=> 'Website\Controller\WebsiteController',
        	'rs-website'		=> 'WebsiteRest\Controller\WebsiteController',
        	'rs-domain-name'	=> 'WebsiteRest\Controller\DomainNameController'
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
        	'website/website/index'	=> __DIR__ . '/../view/website/website/index.phtml',
        	'website/website/create'	=> __DIR__ . '/../view/website/website/create.phtml',
        	'website/website/edit'	=> __DIR__ . '/../view/website/website/edit.phtml',
        	'website/website/server-status' => __DIR__ . '/../view/website/website/server-status.phtml'
        )
    )
);