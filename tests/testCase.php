<?php

use system\MS_Route;
use system\pipelines\MS_pipeline;

class testCase extends PHPUnit_Framework_TestCase
{

	/**
	 * a php unit test that boots the whole framework
	 */
	public function testBootingMSmvc() {
		$excpected = TRUE;
		$this->assertEquals($excpected, $this->bootFrameWork());
	}

	/**
	 * @return mixed: expect html output
	 * @throws Exception: possible exception note: this will not happen with the default configuration
	 */
	private function bootFrameWork() {
		$main = new \system\MS_main();
		MS_pipeline::returnConfig('routes');
		$router                       = new system\MS_router();
		$router->routes               = MS_route::returnRouteCollection();
		$router->currentRequestMethod = 'GET';
		$router->setRequestMethod();
		$router->uri       = '/phpunit';
		$route             = $router->matchRequest();
		$controllerRequest = explode('@', $route['action']['uses']);

		$controller = new $controllerRequest[0];

		if($router->variables != NULL) {
			return call_user_func_array([$controller, $controllerRequest[1]], $router->variables);
		}
		else {
			return $controller->$controllerRequest[1]();
		}
	}
}