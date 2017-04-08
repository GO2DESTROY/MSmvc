<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 13-Jan-17
 * Time: 12:53
 */

namespace App\system\databases;

use App\system\models\Model;
use App\system\models\properties\Property;


/**
 * Class MS_migrations
 * @package App\system\databases\migrations
 */
class MigrationHandler {

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
    public static function addMigrationModel(Model $model) {
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
        } else {
            throw new \Exception("No migrations pending");
        }
    }

    public function executeMigrations(){

    }

    /**
     * @param \App\system\models\Model $model
     */
    private function setMigrationReferences(Model $model) {
        /**
         * @var $field Property
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
     * @param \App\system\models\Model $migrationModel
     */
    private function getNewMigrationGenerateFieldLine(Model $migrationModel) {

        /** @var Property $field */
        foreach ($migrationModel->getFieldCollection() as $field) {
            //todo: add to string on the properties
        }
    }

    /**
     * this method will get the current state of the database and the tables within + the columns
     */
    public function getCurrentDatabaseState() {
        $tablesQuery = new QueryBuilder();
        $tables = $tablesQuery->showTables();
        $currentModels = [];
        foreach ($tables as $table) {
            $currentModel = new MS_modelTemplate();
            "SHOW FULL COLUMNS FROM test";
        }
    }
    //todo: use php reflection to check the default values of the Property values and based on the results create setters in the migration also use the reflection to get changes between instance and blueprint
}