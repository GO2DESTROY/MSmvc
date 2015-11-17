<?php

// here we open a class main this is the core of the system this makes sure the MVC boots up
namespace system;

// this file contains a lot of dirty code we have to improve this in the near future
use system\pipelines\MS_pipeline;

class MS_main extends MS_core
{
	public $phpUnit = FALSE;

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
		$router            = new MS_router;
		$router->routes    = MS_route::returnRouteCollection();
		$route             = $router->matchRequest();
		$controllerRequest = explode('@', $route['action']['uses']);
		$controller        = new $controllerRequest[0];

		if($router->variables != NULL) {
			return call_user_func_array([$controller, $controllerRequest[1]], $router->variables);
		}
		else {
			return $controller->$controllerRequest[1]();
		}
	}
}