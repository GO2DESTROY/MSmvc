<?php

//todo: make a url helper and give accesss to the segments and routes these are only used in the class not outside

namespace system;
class MS_router
{
	public $currentRequestMethod;
	public $routes;
	public $uri;
	public $segments;
	public $variables;


	/**
	 * setRequestMethod checks if the request method is vaild and then sets it
	 */
	function __construct() {
		$this->setRequestMethod();
		if($this->currentRequestMethod != 'CLI') {
			$this->grabUrl();
			$this->setSegments();
		}
	}


	/**
	 * @return string sets the currentRequestMethod property with the http request method
	 * @throws \Exception
	 */
	private function setRequestMethod() {
		if(php_sapi_name() == 'cli') {
			$this->currentRequestMethod = 'CLI';
		}
		else {
			$method = $_SERVER['REQUEST_METHOD'];
			switch($method) {
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
					throw new \Exception('The supplied request method is not supported you have used ' . $method);
					break;
			}
		}
	}

	private function grabUrl() {
		$request_path = explode('?', $_SERVER['REQUEST_URI']);    //root of the URI
		$request_root = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');    //The url
		$uri          = utf8_decode(substr(urldecode($request_path[0]), strlen($request_root) + 1));
		if(empty($uri)) {
			$uri = '/';
		}
		$this->uri = $uri;
	}

	private function setSegments() {
		$this->segments = explode('/', $this->uri);
	}

	private function matchSegments($route) {
		$routeParts = explode('/', $route);
		if(count($this->segments) != count($routeParts)) {
			return FALSE;
		}
		for($i = 0; $i < count($this->segments); $i++) {
			if(empty($routeParts[$i])) {
				return FALSE;
			}
			elseif($routeParts[$i] == $this->segments[$i]) {
				continue;
			}
			elseif(strpos($routeParts[$i], '{') !== FALSE && strpos($routeParts[$i], '}') !== FALSE) {
				$this->variables[] = $this->segments[$i];
				continue;
			}
			else {
				return FALSE;
			}
		}
		return TRUE;
	}

	private function matchMethod($routeSet) {
		foreach($routeSet as $action) {
			if(is_array($action['methods'])) {
				foreach($action['methods'] as $method) {
					if($method == $this->currentRequestMethod) {
						return $action;
						break 2;
					}
				}
			}
			else {
				if($action['methods'] == $this->currentRequestMethod) {
					return $action;
					break 1;
				}
			}
		}
		throw new \Exception('The current method and route is not defined within the routes');
	}

	public function matchRoute() {
		foreach($this->routes as $route => $routeSet) {
			if($route == $this->uri || $this->matchSegments($route) == TRUE) {
				return $this->matchMethod($routeSet);
				break 1;
			}
		}
		throw new \Exception('The current URI is not defined within the routes');
	}

	public function matchCommand() {
		foreach($this->routes as $route => $routeSet) {
			if(getopt('m:')['m'] == $route) {
				$controller = $this->matchMethod($routeSet);
				$this->variables = getopt('m:'.$controller['action']['parameters']);
				return $controller;
				break 1;
			}
		}
		throw new \Exception('The current command is not defined within the routes');
	}
}
