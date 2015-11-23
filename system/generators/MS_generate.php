<?php
namespace system\generators;

use system\MS_core;

class MS_generate
{
	function __construct() {
		parent::__construct();
	}

	public static function generateController($name) {
		new MS_generateController($name);
	}

	public static function generateModel($name) {
		new MS_generateModel($name);

	}

	private function generateApi($name) {
	}
}