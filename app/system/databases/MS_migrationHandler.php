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
            //todo: run the migrations that are created
        } else {
            throw new \Exception("No migrations pending");
        }
    }

    public function executeMigrations(){

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
     *
     */
    public function generateNewMigrations() {
        //todo: loop though migraitons and create template for them
        var_dump($this->migrations);

        foreach ($this->migrations as $migration) {
            $this->getNewMigrationGenerateFieldLine($migration);
            //todo: compare with existing migrations -> create new migration with the changes

        }
    }

    /**
     * @param \App\system\models\MS_model $migrationModel
     */
    private function getNewMigrationGenerateFieldLine(MS_model $migrationModel) {

        /** @var MS_property $field */
        foreach ($migrationModel->getFieldCollection() as $field) {
            //todo: add to string on the properties
        }
    }

    /**
     * this method will get the current state of the database and the tables within + the columns
     */
    public function getCurrentDatabaseState() {
        $tablesQuery = new MS_queryBuilder();
        $tables = $tablesQuery->showTables();
        $currentModels = [];
        foreach ($tables as $table) {
            $currentModel = new MS_modelTemplate();
            "SHOW FULL COLUMNS FROM test";
        }
    }
    //todo: use php reflection to check the default values of the MS_property values and based on the results create setters in the migration also use the reflection to get changes between instance and blueprint
}