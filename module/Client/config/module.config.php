<?php
return array(
	'controllers' => array(
        'invokables' => array(
        	'client'		=> 'Client\Controller\ClientController',
        	'rest-payment'	=> 'ClientRest\Controller\PaymentController',
        	'rest-contact'	=> 'ClientRest\Controller\ContactController'
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
        	'client/client/edit'	=> __DIR__ . '/../view/client/edit.phtml'
        )
    )
);