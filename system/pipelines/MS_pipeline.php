<?php

namespace system\pipelines;

use blueprints\MS_mainInterface;
use system\MS_core;

class MS_pipeline
{
	public static $routes;
	public static $dataSetsLocation;

	function __construct() {
		$this->openDataSetsLocation();
	}

	public static function returnConfig($file) {
		return include dirname($_SERVER["SCRIPT_FILENAME"]) . '/config/' . $file . '.php';
	}

	private function openDataSetsLocation() {
		if(empty(self::$dataSetsLocation)) {
			self::$dataSetsLocation = $this->openPhpFile('/datasets.php');
		}
	}

	private function openPhpFile($file) {
		return include dirname($_SERVER["SCRIPT_FILENAME"]) . '/config/'.$file.'.php';
	}
// todo: make a pipeline sublayer to interacte with data providers
}