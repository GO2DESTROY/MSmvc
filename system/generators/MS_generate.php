<?php
namespace system\generators;

class MS_generate
{
	/**
	 * @param $name string: name for the controller to use as class name and file name
	 */
	public static function generateController($name) {
		$generateController       = new MS_generateController();
		$generateController->name = $name;
		$generateController->basicGenerate();
	}

	/**
	 * @param $name string: name for the model to use as class name and file name
	 */
	public static function generateModel($name) {
		$generateModel = new MS_generateModel();
		$generateModel->name = $name;
		$generateModel->basicGenerate();
	}

	/**
	 * we will generate a controller and it's content will depend on the table
	 *
	 * @param $name string: name for the controller to use as class name and file name
	 * @param $columns array: the columns for the table without the primary keys
	 * @param $keys array: primary keys
	 */
	public static function generateControllerWithDataSet($name, $columns, $keys) {
		$generateController          = new MS_generateController();
		$generateController->name    = $name;
		$generateController->columns = $columns;
		$generateController->keys    = $keys;
		$generateController->generateFromDataSet();
	}

	/**
	 * we will generate a model and it's content will depend on the table
	 *
	 * @param $name string: name for the model to use as class name and file name
	 * @param $databaseConnectionReference string: the database connection set to use
	 * @param $columns array: the columns for the table without the primary keys
	 * @param $keys array: primary keys
	 */
	public static function generateModelFromDatabase($name, $databaseConnectionReference, $columns, $keys) {
		$generateModel          = new MS_generateModel();
		$generateModel->name    = $name;
		$generateModel->columns = $columns;
		$generateModel->keys    = $keys;
		$generateModel->databaseConnectionReference = $databaseConnectionReference;
		$generateModel->generateFromDataSet();
	}
}