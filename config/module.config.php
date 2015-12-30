<?php
/**
 * Contains all specific configuration for Error Listener
 *
 * PHP version 5.5
 *
 * @category Configuration
 * @package  ErrorManagement
 * @author   Tarun
 */
return array (
		'listeners' => array (
				'ErrorManagement\Listener\ErrorListenerService' 
		),
		
		'service_manager' => array (
				'invokables' => array (
						'ErrorManagement\Listener\ErrorListenerService' => 'ErrorManagement\Listener\ErrorListenerService' 
				) 
		) 
);
