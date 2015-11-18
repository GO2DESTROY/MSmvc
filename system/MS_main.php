<?php

// here we open a class main this is the core of the system this makes sure the MVC boots up
namespace system;

// this file contains a lot of dirty code we have to improve this in the near future

use system\pipelines\MS_pipeline;
use system\router\MS_Route;
use system\router\MS_router;

class MS_main extends MS_core
{
	public $currentRequestMethod = NULL;
	public $uri;
//setter
	public function setUpRouter() {
		if($this->currentRequestMethod !== 'CLI') {
			$this->uri = $this->grabUri();
		}
	}

	/**
	 * First we load the routes config
	 * Then we get the routes them self
	 * check the method of communication CLI vs HTTP
	 * Finally we call The Controller
	 *
	 * @return mixed: The controller
	 * @throws \Exception
	 */
	public function boot() {
		MS_pipeline::returnConfig('routes');
		$router         = new MS_router;
		$router->routes = MS_route::returnRouteCollection();
		//check for CI
		$router->setRequestMethod();
		if($router->currentRequestMethod !== 'CLI') {
			$router->uri =$this->grabUri();
		}
		$route = $router->matchRequest();

		$controllerRequest = explode('@', $route['action']['uses']);
		$controller        = new $controllerRequest[0];

		if($router->variables != NULL) {
			return call_user_func_array([$controller, $controllerRequest[1]], $router->variables);
		}
		else {
			return $controller->$controllerRequest[1]();
		}
	}

	/**
	 * @return string we return the current http URI
	 */
	private function grabUri() {
		$request_path = explode('?', $_SERVER['REQUEST_URI']);    //root of the URI
		$request_root = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');    //The url
		$uri          = utf8_decode(substr(urldecode($request_path[0]), strlen($request_root) + 1));
		if(empty($uri)) {
			$uri = '/';
		}
		return $uri;
	}

	public function index() {
		//we set the default cli and http configurations

	}
}