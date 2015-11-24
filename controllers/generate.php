<?php

namespace controllers;

use models\generateModel;
use system\generators\MS_generate;
use system\helpers\MS_db;
use system\MS_controller;
use system\pipelines\MS_pipeline;

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
		return $this->view('generateForm', ['connectionSets' => $dataBaseConnectionSets]);
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
			//make an index based on the table name for the data saving
			//todo: we generate if from a database
			foreach($_REQUEST['databaseTableCollection'] as $databaseTable) {
				$tableColumns = generateModel::getTableColumns($databaseTable);
				$tableKeys    = generateModel::getPrimaryKeys($databaseTable);
				if(!empty($tableKeys) && !empty($tableColumns)) {
					foreach($tableKeys as $tableKey) {
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

					}
					unset($fields);
					unset($keys);
				}
				else
				{
					//return error there are no columns or primary keys within this table
				}
			}
		}
		else {
			if(isset($_REQUEST['controller']) && isset($_REQUEST['model'])) {
				//todo: generate Both model and controller to implement each other
			}
			elseif(isset($_REQUEST['controller'])) {
				MS_generate::generateController($_REQUEST['name']);
			}
			elseif(isset($_REQUEST['model'])) {
				MS_generate::generateModel($_REQUEST['name']);
			}
		}
	}

}