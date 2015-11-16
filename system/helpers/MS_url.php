<?php

namespace system\helpers;

use system\MS_Route;

class MS_url
{
	private $segments;
	private $uriParts;
	private $uri;

	/**
	 * @param      $name       : the name of the controller
	 * @param null $properties : the properties for the controller to use
	 *
	 * @return mixed : we execute the controller
	 * @throws \Exception: in case it doesn't exist we throw an error
	 */
	public function callControllerByName($name, $properties = NULL) {
		if(!empty(MS_route::returnReferenceCollection()[$name])) {
			$controllerRequest = explode('@', MS_route::returnReferenceCollection()[$name]['action']['uses']);
			$controller        = new $controllerRequest[0];
			if($properties != NULL) {
				return call_user_func_array([$controller, $controllerRequest[1]], $properties);
			}
			else {
				return $controller->$controllerRequest[1]();
			}
		}
		else {
			throw new \Exception("The current route doesn't exist. Your request:" . $name);
		}
	}

	/**
	 * @param $uri : the uri to explode
	 */
	private function setSegments($uri) {
		$this->segments = explode('/', $uri);
	}

	/**
	 * set $this->uri
	 */
	private function setUri() {
		$this->uri = implode('/', $this->uriParts);
	}


	/**
	 * @param      $name       : the name of the url to use
	 * @param null $properties : the properties to replace the url variables with
	 *
	 * @throws \Exception: in case it doesn't exist we throw an error
	 */
	public function getUrlByName($name, $properties = NULL) {
		if(!empty(MS_route::returnReferenceCollection()[$name])) {
			$this->setSegments(MS_route::returnReferenceCollection()[$name]['uri']);
			//	$uri = $this->segments;
			foreach($this->segments as $segment) {
				if(strpos($segment, '{') !== FALSE && strpos($segment, '}') !== FALSE) {
					$this->uriParts[] = current($properties);
					next($properties);
				}
				else {
					$this->uriParts[] = $segment;
				}
			}
			$this->setUri();
			//	var_dump($controllerRequest);
		}
		else {
			throw new \Exception("The current route doesn't exist. Your request:" . $name);
		}
	}

	/**
	 * @param      $name       : the name of the url to use
	 * @param null $properties : the properties to replace the url variables with
	 *
	 * @return mixed: method
	 * @throws \Exception: in case it doesn't exist we throw an error
	 */
	public static function controller($name, $properties = NULL) {
		$call = new MS_url();
		return $call->callControllerByName($name, $properties);
	}

	public function __get($name) {
		return $this->$name;
	}

	/**
	 * @param      $name       : the name of the url to use
	 * @param null $properties : the properties to replace the url variables with
	 *
	 * @return mixed: the uri string to return
	 * @throws \Exception: in case it doesn't exist we throw an error
	 */
	public static function url($name, $properties = NULL) {
		$url = new MS_url();
		$url->getUrlByName($name, $properties);
		return $url->__get('uri');
	}
}