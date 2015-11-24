<?php
namespace system\generators;

class MS_generate
{
	public static function generateController($name) {
		$generateController       = new MS_generateController();
		$generateController->name = $name;
		$generateController->basicGenerate();
	}

	public static function generateModel($name) {
		$generateModel = new MS_generateModel();
		$generateModel->generate($name);
	}

	public static function generateControllerWithDataSet($name, $columns, $keys) {
		$generateController          = new MS_generateController();
		$generateController->name    = $name;
		$generateController->columns = $columns;
		$generateController->keys    = $keys;
		$generateController->generateFromDataSet();
	}

	public function generateModelFromDatabase() {
	}
}