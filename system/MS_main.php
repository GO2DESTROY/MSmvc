<?php

// here we open a class main this is the core of the system this makes sure the MVC boots up
namespace system;
// this file contains a lot of dirty code we have to improve this in the near future
use system\pipelines\MS_pipeline;

class MS_main extends MS_core
{
	public    $currentUrl;
	protected $configSet;

	function __construct() {
		parent::__construct();
		$this->configSet = new \stdClass();
		$this->configLoader();
	}

	/**
	 * we load the config files and set the config collection sets
	 */
	protected function configLoader() {
		MS_pipeline::returnConfig('routes');
		$this->configSet->routes     = MS_route::returnRouteCollection();
		$this->configSet->references = MS_Route::returnReferenceCollection();
	}

	private function boot() {
		$router = new MS_router;

		$router->routes = $this->configSet->routes;

		if($router->currentRequestMethod == 'CLI') {
			$route = $router->matchCommand();
			unset($router->variables['m']);	// re remove the method request from the variables
		}
		else {
			$route = $router->matchRoute();
		}
		$controllerRequest = explode('@', $route['action']['uses']);
		$controller        = new $controllerRequest[0];

		if($router->variables != NULL) {
			return call_user_func_array([$controller, $controllerRequest[1]], $router->variables);
		}
		else {
			return $controller->$controllerRequest[1]();
		}

	}

	public function index() {
		$this->boot();
	}
}