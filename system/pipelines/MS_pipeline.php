<?php

namespace system\pipelines;

use blueprints\MS_mainInterface;
use system\MS_core;

class MS_pipeline extends MS_core implements MS_mainInterface
{
	public static $routes;
	public function loadRequest()
	{

	}
	public static function returnConfig($file)
	{
		return include dirname($_SERVER["SCRIPT_FILENAME"]).'/config/'.$file.'.php';
	}
	public function loadRoutes()
	{
		var_dump($this);
	}
// todo: make a pipeline sublayer to interacte with data providers
	/**
	 * @param $name  : the key to use for the magic method
	 * @param $value : the value to use for the magic method
	 *
	 * @return mixed: the interface
	 */
	public function __set($name, $value) {
		$this->$name = $value;
	}

	/**
	 * @param $name : the key to use for the magic method
	 *
	 * @return mixed: the interface
	 */
	public function __get($name) {
		return $this->$name;
	}
}