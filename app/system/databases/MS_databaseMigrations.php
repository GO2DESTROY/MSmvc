<?php
namespace system\databases;
use system\pipelines\MS_pipeline;
use system\models\MS_model;

/**
 * Class MS_databaseMigrations
 * @package system\databases
 */
class MS_databaseMigrations {

	private $migrations;

	/**
	 *
	 */
	private function commitChanges() {
		//todo: make this function to commit all the changes that are pending
	}

	/**
	 * @param MS_model $model
	 */
	public function addMigration(MS_model $model) {
		$this->migrations[] = $model;
	}

	/**
	 *
	 */
	private function getCurrentState($modelName){
		//todo: get the current state of the database this will be done by a speciale table and a json log
		MS_pipeline::includeFile($modelName."-log.json");
	}
}