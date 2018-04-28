<?php

namespace App\system\databases;

use App\system\databases\migrations\test;
use App\system\models\Model;
use App\system\models\fields\Field;
use App\system\FileFilter;
use App\system\Filesystem;
use App\system\databases\MigrationFilter;


/**
 * Class MigrationBuilder
 *
 * this class will build a migration based on a model
 */
class MigrationBuilder {

    /**
     * this is the current model that we will use to create new migrations
     * @var Model
     */
    private $model;

    /**
     * contains the name of the property and if it's a default value or not
     * changes that need to be made this array contains all the changes to duplicate to model to a migration minus the
     * changes that occurred in previous migrations
     * @var array
     */
    private $changeSet;

    private $oldMigrationsBase;

    /**
     * MigrationBuilder constructor.
     *
     * @param \App\system\models\Model $model
     */
    function __construct(Model $model = NULL) {
        if (!is_null($model)) {
            $this->setModel($model);
        }
    }

    /**
     * @return mixed
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * @param mixed $model
     *
     * @return $this
     */
    public function setModel(Model $model) {
        $this->model = $model;
        $this->model->up();
        return $this;
    }

    /**
     * add create the migration based on olf models
     */
    public function execute() {
        $oldMigrations = new Filesystem("App/system/databases/migrations");
        $oldMigrations->addFilter(new MigrationFilter($this->model->getShortModelName()));
        //todo: write custom iterator
        $oldMigrations->customCallback([$this, "applyOldMigrations"]);

        $this->buildChangeSet();
       //     var_dump($this->changeSet);
        //do stuff execute all the things
    }

    /**
     * this will start the old version
     * @param \SplFileInfo $file
     */
    public function applyOldMigrations(\SplFileInfo $file) {
        /**
         * @type $migration Migration
         */
        $migrationString = "\\" . $file->getPathInfo()->getPathname() . DIRECTORY_SEPARATOR . $file->getBasename('.' . $file->getExtension());
        $migration = new $migrationString();
        $migration->up();
        $this->oldMigrationsBase;
       // var_dump($migration);
    }

    private function applyNewMigration(Migration $migration){

    }

    private function buildChangeSet() {
        foreach ($this->model->getFieldCollection() as $field) {
            $this->changeSet[$field->name] = $this->checkFieldProperties($field);
        }
    }

    /**
     * todo: check this for the history
     * this method will set the default flag for a property value
     *
     * @param \App\system\models\fields\Field $field
     *
     * @return array
     */
    private function checkFieldProperties(Field $field) {
        $reflection = new \ReflectionObject($field);
        $defaultValues = $reflection->getDefaultProperties();
        $changes = [];
        foreach ($reflection->getProperties() as $fieldValues) {
            $fieldValue = $field->__get($fieldValues->name);
            $data = [];
            $data["value"] = $fieldValue;
            if (in_array($fieldValue, $defaultValues)) {
                $data["default"] = TRUE;
            } else {
                $data["default"] = FALSE;
            }
            $changes[$fieldValues->name] = $data;
        }
        return $changes;
    }

}