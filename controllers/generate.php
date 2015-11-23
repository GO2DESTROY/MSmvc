<?php

namespace controllers;

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

	public function submitGenerateFormPage() {
		if(isset($_REQUEST['database'])) {
			//todo: we generate if from a database
			var_dump($_REQUEST['databaseTableCollection']);
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