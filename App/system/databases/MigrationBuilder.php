<?php

namespace App\system\databases;

use App\system\databases\migrations\test;
use App\system\models\Model;
use App\system\models\properties\Property;
use App\system\FileFilter;
use App\system\MS_filesystem;
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

    public function execute() {
        $oldMigrations = new MS_filesystem("App/system/databases/migrations");
        $oldMigrations->addFilter(new MigrationFilter($this->model->getShortModelName()));
        $oldMigrations->customCallback([$this, "applyOldMigrations"]);

        $this->buildChangeSet();
        //    var_dump($this->changeSet);
        //do stuff execute all the things
    }

    /**
     * @param \SplFileInfo $file
     */
    public function applyOldMigrations(\SplFileInfo $file) {
        /**
         * @type $migration Migration
         */
        $migrationString = "\\" . $file->getPathInfo()->getPathname() . DIRECTORY_SEPARATOR . $file->getBasename('.' . $file->getExtension());
        $migration = new $migrationString();
        $migration->up();
        var_dump($migration);
    }

    private function buildChangeSet() {
        foreach ($this->model->getFieldCollection() as $field) {
            //todo: mark the fields that are changed
            //todo: mark the fields that are default ---------done
            $this->changeSet[$field->name] = $this->checkFieldProperties($field);
        }
    }

    /**
     * todo: check this for the history
     *
     * @param \App\system\models\properties\Property $field
     *
     * @return array
     */
    private function checkFieldProperties(Property $field) {
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