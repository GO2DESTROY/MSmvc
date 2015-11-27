<?php

namespace controllers;

use models\generateModel;
use system\generators\MS_generate;
use system\helpers\MS_db;
use system\MS_controller;
use system\pipelines\MS_pipeline;
use system\pipelines\MS_pipeline_push;

class generate extends MS_controller
{
	public function controller($name) {
		MS_generate::generateController($name);
	}

	public function model($name) {
		MS_generate::generateModel($name);
	}

	public function getGenerateFormPage() {
		$dataBaseConnectionSets = MS_pipeline::returnConfig('database')['connectionSets'];
		return $this->view('system/generateForm', ['connectionSets' => $dataBaseConnectionSets]);
	}

	public function getGenerateTables($dataBaseConnectionName) {
		$database = MS_pipeline::returnConfig('database')['connectionSets'][$dataBaseConnectionName]['database'];
		$tables   = MS_db::connection($dataBaseConnectionName)->query("select table_name as 'tables' from information_schema.tables t where t.table_schema = ?", [$database]);

		return $this->json(['tables' => $tables]);
	}

	/**
	 * Query in this method are not safe to use but since this should only be used for development this shouldn't be a
	 * problem
	 */
	public function submitGenerateFormPage() {
		if(isset($_REQUEST['database'])) {
			foreach($_REQUEST['databaseTableCollection'] as $databaseTable) {
				$tableColumns = generateModel::getTableColumns($_REQUEST['databaseConnectionReference'], $databaseTable);
				$tableKeys    = generateModel::getPrimaryKeys($_REQUEST['databaseConnectionReference'], $databaseTable);
				$segments     = '';
				if(!empty($tableKeys) && !empty($tableColumns)) {
					foreach($tableKeys as $tableKey) {
						$segments .= '{' . $tableKey['Column_name'] . '}/';
						$keys[] = $tableKey['Column_name'];
					}
					foreach($tableColumns as $tableColumn) {
						$fields[] = $tableColumn['Field'];
					}
					$updateColumns = array_diff($fields, $keys);

					if(isset($_REQUEST['controller'])) {
						MS_generate::generateControllerWithDataSet($databaseTable, $updateColumns, $keys);
					}
					if(isset($_REQUEST['model'])) {
						MS_generate::generateModelFromDatabase($databaseTable, $_REQUEST['databaseConnectionReference'], $updateColumns, $keys);
					}
					unset($fields);
					unset($keys);
				}
				else {
					//return error there are no columns or primary keys within this table
				}
				if(isset($_REQUEST['routes'])) {
					$push = new MS_pipeline_push;
					$push->addToConfig('routes', PHP_EOL);
					$push->addToConfig('routes', "MS_route::get('/$databaseTable', ['uses' => '$databaseTable@index', 'as' => '" . $databaseTable . "Index']);" . PHP_EOL);
					$push->addToConfig('routes', "MS_route::get('/$databaseTable/create', ['uses' => '" . $databaseTable . "@create', 'as' => '" . $databaseTable . "Create']);" . PHP_EOL);
					$push->addToConfig('routes', "MS_route::post('/$databaseTable', ['uses' => '" . $databaseTable . "@store', 'as' => '" . $databaseTable . "Store']);" . PHP_EOL);
					$push->addToConfig('routes', "MS_route::get('/$databaseTable/" . rtrim($segments, '/') . "', ['uses' => '$databaseTable@show', 'as' => '" . $databaseTable . "Show']);" . PHP_EOL);
					$push->addToConfig('routes', "MS_route::get('/$databaseTable/" . rtrim($segments, '/') . "edit', ['uses' => '$databaseTable@edit', 'as' => '" . $databaseTable . "Edit']);" . PHP_EOL);
					$push->addToConfig('routes', "MS_route::put('/$databaseTable/" . rtrim($segments, '/') . "', ['uses' => '$databaseTable@update', 'as' => '" . $databaseTable . "Update']);" . PHP_EOL);
					$push->addToConfig('routes', "MS_route::delete('/$databaseTable/" . rtrim($segments, '/') . "', ['uses' => '$databaseTable@delete', 'as' => '" . $databaseTable . "Delete']);" . PHP_EOL);

					$push->closePushStream();
				}
			}
		}
		else {
			if(isset($_REQUEST['controller'])) {
				MS_generate::generateController($_REQUEST['name']);
			}
			if(isset($_REQUEST['model'])) {
				MS_generate::generateModel($_REQUEST['name']);
			}
		}
	}

}