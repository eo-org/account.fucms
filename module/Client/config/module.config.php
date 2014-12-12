<?php
return array(
	'controllers' => array(
        'invokables' => array(
        	'client'		=> 'Client\Controller\ClientController',
        	'rs-client'		=> 'ClientRest\Controller\ClientController',
        	'rs-payment'	=> 'ClientRest\Controller\PaymentController',
        	'rs-contact'	=> 'ClientRest\Controller\ContactController'
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
        	'client/client/index'	=> __DIR__ . '/../view/client/client/index.phtml',
        	'client/client/create'	=> __DIR__ . '/../view/client/client/create.phtml',
        	'client/client/edit'	=> __DIR__ . '/../view/client/client/edit.phtml'
        )
    )
);