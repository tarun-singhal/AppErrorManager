<?php

/**
 * To handle the Exception in API
 * @author Tarun
 */
namespace ErrorManagement\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use ZF\ApiProblem\ApiProblemResponse;
use Zend\Mvc\Router\RouteMatch;
use Zend\EventManager\EventInterface;

class ErrorListenerService extends AbstractListenerAggregate {
	protected $listeners = array ();
	protected $apiDocUrl = '';
	protected $module = '';
	
	/**
	 * Attach listners
	 *
	 * @param EventManagerInterface $emi        	
	 *
	 * @return void
	 */
	public function attach(EventManagerInterface $events) {
		$this->listeners [] = $events->attach ( MvcEvent::EVENT_FINISH, array (
				$this,
				'handleApiProblem' 
		) );
	}
	
	/**
	 * detach the attached listener
	 *
	 * @see \Zend\EventManager\AbstractListenerAggregate::detach()
	 */
	public function detach(\Zend\EventManager\EventManagerInterface $events) {
		foreach ( $this->listeners as $index => $listener ) {
			if ($events->detach ( $listener )) {
				unset ( $this->listeners [$index] );
			}
		}
	}
	
	/**
	 * To handle the api problem
	 *
	 * @param MvcEvent $mvcEvent        	
	 */
	public function handleApiProblem(MvcEvent $mvcEvent) {
		$app = $mvcEvent->getApplication ();
		$request = $mvcEvent->getRequest ();
		$services = $app->getServiceManager ();
		
		$router = $services->get ( 'router' );
		$routeMatch = $router->match ( $request );
		
		$this->getModuleName ( $routeMatch );
		
		$errorResponse = $services->get ( 'coreError' );
		
		
		$resp = $mvcEvent->getResponse ();
		
		if (! $resp instanceof ApiProblemResponse) {
			return $this;
		}
		$this->setDocumentLink ( $request );
		
		$exception = $resp->getApiProblem ();
		$this->setErrorResponse ( $errorResponse, $exception );
	}
	
	/**
	 * Get the Module Name
	 * @param Object $routeMatch
	 */
	public function getModuleName($routeMatch) {
		if ($routeMatch instanceof RouteMatch) {
			$this->module = $routeMatch->getMatchedRouteName ();
		}
		return $this->module;
	}
	
	/**
	 * Get the Document Link
	 * @param Object $request
	 */
	public function setDocumentLink($request) {
		$this->apiDocUrl = $request->getUri ()->getScheme () . '://' . $request->getHeaders ( 'host' )->getFieldValue () . "/apigility/documentation";
		return $this->apiDocUrl;
	}
	
	/**
	 * 
	 * @param Object $errorResponse
	 * @param Object $exception
	 */
	public function setErrorResponse($errorResponse, $exception) {
		$errorResponse->set ( "errorCode", $exception->status );
		$errorResponse->set ( 'link', $this->apiDocUrl );
		$errorResponse->set('module', $this->module);
		
		$ini = $this->parseErrorIniFile ( __DIR__ . '/../../../config/Error.ini' );
		
		if ($exception->status == 500) {
			// handle error, if exception found
			$errorResponse->set ( 'userMessage', $exception->status );
			$errorResponse->set ( 'debugMessage', $exception->detail->getMessage () );
			die ( json_encode ( $errorResponse ) );
		} else {
			// handle error, if ApiProblem set
			$errorResponse->set ( 'userMessage', $exception->detail );
			$errorResponse->set ( 'debugMessage', $ini [$this->module] [$exception->detail] );
			die ( json_encode ( $errorResponse ) );
		}
	}
	
	/**
	 * To parse the ini file
	 *
	 * @param File $inifile        	
	 */
	protected function parseErrorIniFile($inifile) {
		$errorDetail = array ();
		if (file_exists ( $inifile )) {
			$errorDetail = parse_ini_file ( $inifile, true );
		} else {
			return "INI File not found";
		}
		return $errorDetail;
	}
}
?>
