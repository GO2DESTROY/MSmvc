<?php
namespace system\generators;

use system\MS_core;

class MS_generate extends MS_core
{
	function __construct() {
		parent::__construct();
	}

	public static function generateController($name) {
		$newGenerated = new MS_generateController($name);
	}

	public static function generateModel($name) {
		$newGenerated = new MS_generateModel($name);

	}

	private function generateApi($name) {
	}
}