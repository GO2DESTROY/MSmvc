<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 13-Jan-17
 * Time: 12:53
 */

namespace App\system\databases;

use App\system\models\MS_model;
use App\system\models\properties\MS_property;
use system\models\MS_modelTemplate;


/**
 * Class MS_migrations
 * @package App\system\databases\migrations
 */
class MS_migrationHandler {

    /**
     * @var array
     */
    private static $migrationModels = [];

    /**
     * @var array
     */
    private $migrations;

    /**
     * @var array
     */
    private $migrationReferences;

    /**
     * this method will call the up method and add the model
     *
     * @param $model
     */
    public static function addMigrationModel(MS_model $model) {
        $model->up();
        self::$migrationModels[$model->getShortModelName()] = $model;
    }

    /**
     * MS_migrations constructor.
     */
    function __construct() {
        if (!empty(self::$migrationModels)) {
            array_walk(self::$migrationModels, [$this, "setMigrationReferences"]);
            $this->migrations = self::$migrationModels;

            //todo: create migration
            //todo: compare migration with live version
            //todo: dynamic checkset based on query builder to change the database
        } else {
            throw new \Exception("No migrations pending");
        }
    }

    /**
     * @param \App\system\models\MS_model $model
     */
    private function setMigrationReferences(MS_model $model) {
        /**
         * @var $field MS_property
         */
        //todo: compare current model to existing migrations
		//todo: find changes between model and migration and write to new migration

        foreach ($model->getFieldCollection() as $field) {
            //short model name
            if (!empty($field->getReferenceModel())) {
                $this->migrationReferences[$field->getReferenceModel()][] = $model->getShortModelName();
            }
        }
    }

    /**
     * this method will get the current state of the database and the tables within + the columns
     */
    public function getCurrentDatabaseState(){
        $tablesQuery = new MS_queryBuilder();
        $tables = $tablesQuery->showTables();
        $currentModels = [];
        foreach ($tables as $table){
            $currentModel = new MS_modelTemplate();
            "SHOW FULL COLUMNS FROM test";
        }
    }
}