<?php
namespace system;

class MS_Route
{
	private static $routeSet;
	private static $referenceSet;

	public static function addRoute($methods, $uri, $action) {
		self::$routeSet[$uri][] = ['methods' => $methods, 'action' => $action];
		if(!empty($action['as'])) {
			self::$referenceSet[$action['as']] = ['methods' => $methods, 'uri' => $uri, 'action' => $action];
		}
	}

	public static function returnRouteCollection() {
		return self::$routeSet;
	}

	public static function returnReferenceCollection() {
		return self::$referenceSet;
	}

	public static function any($uri, $action) {
		$methods = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
		self::addRoute($methods, $uri, $action);
	}

	public static function post($uri, $action) {
		self::addRoute('POST', $uri, $action);
	}

	public static function get($uri, $action) {
		$methods = ['GET', 'HEAD'];
		self::addRoute($methods, $uri, $action);
	}

	public static function put($uri, $action) {
		self::addRoute('PUT', $uri, $action);
	}

	public static function patch($uri, $action) {
		self::addRoute('PATCH', $uri, $action);
	}

	public static function delete($uri, $action) {
		self::addRoute('DELETE', $uri, $action);
	}

	public static function options($uri, $action) {
		self::addRoute('OPTIONS', $uri, $action);
	}

	public static function cli($command, $action) {
		self::addRoute('CLI', $command, $action);
	}
}