<?php
// here we open a class main this is the core of the system this makes sure the MVC boots up
namespace MSmvc;

// this file contains a lot of dirty code we have to improve this in the near future

use MSmvc\system\MS_handler;
use MSmvc\system\MS_request;
use MSmvc\system\pipelines\MS_pipeline;
use MSmvc\system\router\MS_Route;
use MSmvc\system\router\MS_router;

/**
 * Class MS_start: this class will start the framework
 * @package system: MSmvc main
 * @author  Maurice Schurink
 */
class MS_start {
	public $currentRequestMethod = NULL;
	public $uri = NULL;

	/**
	 * MS_start constructor.
	 */
	public function __construct() {
		MS_pipeline::$root= dirname(__FILE__) . DIRECTORY_SEPARATOR;
	//	set_exception_handler([new MS_handler, 'exceptionHandler']);
	//	set_error_handler([new MS_handler, 'errorHandler']);
//		register_shutdown_function([new MS_handler, 'fatal_handler']);
	}

	/**
	 * we let the router run to find the right route to use then we pass it to the request so the controller can be
	 * called followed by the response
	 */
	public function boot() {
		$this->setRequestMethod();
		if($this->currentRequestMethod !== 'CLI') {
			$this->setRequestUri();
		}
		$request = new MS_request();
		$request->requestInterface = $this->currentRequestMethod;

		foreach(glob("config/*.php") as $filename) {
			MS_pipeline::getConfigFileContent($filename);
		}
		//exit();
		MS_pipeline::getConfigFileContent('routes');

		$router = new MS_router();
		$router->routes = MS_Route::returnRouteCollection();
		$router->currentRequestMethod = $this->currentRequestMethod;

		if($this->currentRequestMethod !== 'CLI') {
			$router->uri = $this->uri;
		}
		$request->requestRoute = $router->matchRequest();
		if($router->variables !== NULL) {
			$request->requestVariables = $router->variables;
		}
		$request->request();
	}

	/**
	 * we set this->uri to the current http uri
	 */
	private function setRequestUri() {
		if($this->uri === NULL) {
			$request_path = explode('?', $_SERVER['REQUEST_URI']);    //root of the URI
			$request_root = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');    //The url
			$uri = '/' . utf8_decode(substr(urldecode($request_path[0]), strlen($request_root) + 1));
			$this->uri = $uri;
		}
	}

	/**
	 * @return string sets the currentRequestMethod property with the http request method
	 * @throws \Exception
	 */
	private function setRequestMethod() {
		if($this->currentRequestMethod === NULL) {
			if(php_sapi_name() == 'cli') {
				$this->currentRequestMethod = 'CLI';
			}
			else {
				switch($_SERVER['REQUEST_METHOD']) {
					case 'PUT':
						$this->currentRequestMethod = 'PUT';
						break;
					case 'POST':
						$this->currentRequestMethod = 'POST';
						break;
					case 'GET':
						$this->currentRequestMethod = 'GET';
						break;
					case 'HEAD':
						$this->currentRequestMethod = 'HEAD';
						break;
					case 'DELETE':
						$this->currentRequestMethod = 'DELETE';
						break;
					case 'OPTIONS':
						$this->currentRequestMethod = 'OPTIONS';
						break;
					default:
						throw new \Exception('The supplied request method is not supported you have used ' . $_SERVER['REQUEST_METHOD']);
						break;
				}
			}
		}
	}
}