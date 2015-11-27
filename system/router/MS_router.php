<?php

namespace system\router;
class MS_router
{
	public $routes;
	public $segments;
	public $variables;
	public $currentRequestMethod = NULL;
	public $uri;

	/**
	 * @return string sets the currentRequestMethod property with the http request method
	 * @throws \Exception
	 */

	private function setSegments() {
		$this->segments = explode('/', $this->uri);
	}

	/**
	 * @param $route : The route to examine on segment matches
	 *
	 * @return bool: match true or false
	 */
	private function matchSegments($route) {
		$routeParts = explode('/', $route);

		if(count($this->segments) != count($routeParts)) {
			return FALSE;
		}
		for($i = 1; $i < count($this->segments); $i++) {
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

	/**
	 * @param $routeSet : the routes to loop through
	 *
	 * @return mixed: the controller with the method to call
	 * @throws \Exception
	 */
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

	/**
	 * @return mixed
	 * @throws \Exception: The current URI is not defined within the routes
	 */
	private function matchRoute() {
		foreach($this->routes as $route => $routeSet) {
			if($route == $this->uri || $this->matchSegments($route) == TRUE) {
				return $this->matchMethod($routeSet);
				break 1;
			}
		}
		throw new \Exception('The current URI is not defined within the routes your uri: '.$this->uri);
	}


	/**
	 * @return mixed: the controller
	 * @throws \Exception: The current command is not defined within the routes
	 */
	private function matchCommand() {
		foreach($this->routes as $route => $routeSet) {
			if(getopt('m:')['m'] == $route) {
				$controller      = $this->matchMethod($routeSet);
				$this->variables = getopt('m:' . $controller['action']['parameters']);
				unset($this->variables['m']);
				return $controller;
				break 1;
			}
		}
		throw new \Exception('The current command is not defined within the routes');
	}

	/**
	 * @return mixed
	 * @throws \Exception: the current command or URI id not defined within the routes settings
	 */
	public function matchRequest() {
		if($this->currentRequestMethod == 'CLI') {
			return $this->matchCommand();
		}
		else {
			$this->setSegments();
			return $this->matchRoute();
		}
	}
}
