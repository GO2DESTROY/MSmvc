<?php

namespace App\system\databases;

use App\system\databases\migrations\test;
use App\system\models\Model;
use App\system\models\fields\Field;
use App\system\Filesystem;


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
     * @var array: this variable hold all the migrationFields
     */
    private $MigrationFieldCollection = [];

    /**
     * @var array: this indexed array will hold the lines of code for the new migration
     */
    private $newMigrationLines = [];

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
    public function getModel(): Model {
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
        //todo: get the current model and get the diffrences between that and the migration
        //todo: write a new migration.
        //
        // $this->buildChangeSet();
        //do stuff execute all the things
        $this->createNewMigration();

    }

    /**
     * this method will compare the current model with it's migration list and create a new migration based on the
     * current model
     */
    private function createNewMigration() {
        // $this->MigrationFieldCollection[$this->getModel()->getShortModelName()];
        $changes = []; //these fields are added or updated if the fieldnames is not in this array delete it.
        foreach ($this->getModel()->getFieldCollection() as $fieldName => $field) {
            //var_dump($this->checkFieldProperties($field)); --mark if the fieldsproperties are default or not
            $this->checkFieldProperties($field);
            var_dump($field);

            if (!empty($this->MigrationFieldCollection[$this->getModel()->getShortModelName()][$fieldName])) {
                $reflection = new \ReflectionObject($field);
                $changes = [];
                $this->checkFieldProperties($field);

                foreach ($reflection->getProperties() as $fieldValues) {

                }
                //we want to update this field
            } else {

                //copy this field since we create
            }
        }
        foreach ($this->MigrationFieldCollection[$this->getModel()->getShortModelName()] as $migrationFieldName => $migrationField) {
            if (!in_array($migrationFieldName, $changes)) {
                $this->newMigrationLines[] = $this->modelFieldToMigrationString($migrationField['dataStructure'], 'delete');
            }
        }
    }

    /**
     * @param \App\system\models\fields\Field $field
     * @param string                          $type : 'add|update|delete'
     *
     * @return string
     */
    private function modelFieldToMigrationString(Field $field, $type = 'add'): string {
        // return '';
        switch ($type) {
            case 'add':
                return NULL;
                break;
            case 'update':
                return NULL;
                break;
            case 'delete':
                return '$this->deleteField("'.$field->name.'"")';
                break;
            default:
                return NULL;
                break;
        }
    }

    /**
     * this will start the old version
     *
     * @param \SplFileInfo $file
     *
     * @throws \Exception
     */
    public function applyOldMigrations(\SplFileInfo $file) {
        /**
         * @type $migration Migration
         */
        $migrationString = "\\" . $file->getPathInfo()->getPathname() . DIRECTORY_SEPARATOR . $file->getBasename('.' . $file->getExtension());
        $migration = new $migrationString();
        $this->applyNewMigration($migration);
    }

    /**
     * this method will build a single datastructure based on all the migrations of the current model.
     *
     * @param \App\system\databases\Migration $migration
     *
     * @throws \Exception
     */
    private function applyNewMigration(Migration $migration) {
        $migration->up();
        foreach ($migration->getFields() as $migrationField) {
            if ($migrationField["new"] == TRUE) {
                //new field
                $this->MigrationFieldCollection[$migration->getFileName()][$migrationField["field"]->name]["dataStructure"] = $migrationField["field"];
                $this->MigrationFieldCollection[$migration->getFileName()][$migrationField["field"]->name]["defaults"] = $this->checkFieldProperties($migrationField["field"]);
                //  $this->mi$this->$migrationField["field"];
            } else {
                if (!empty($this->MigrationFieldCollection[$migration->getFileName()][$migrationField["field"]->name])) {
                    $checkFieldProperties = $this->checkFieldProperties($migrationField["field"]);
                    //We will remove the default value if they are also the default value of the first migration otherwise we will keep it
                    foreach ($checkFieldProperties as $key => $checkFieldProperty) {
                        //todo: update this check the name value seems to go wrong!
                        if (($checkFieldProperty === TRUE && $this->MigrationFieldCollection[$migration->getFileName()][$migrationField["field"]->name]["defaults"][$key] === TRUE) || $key == "name") {
                            unset($checkFieldProperties[$key]);
                        }
                    }
                    //overwrite these fields note: we will not check if values are similar we will just overwrite the field another change check should be done on the last migration that exists
                    foreach (array_keys($checkFieldProperties) as $propertyName) {
                        $this->MigrationFieldCollection[$migration->getFileName()][$migrationField["field"]->name]["dataStructure"]->$propertyName = $migrationField["field"]->$propertyName;
                    }
                } else {
                    throw new \Exception("trying to update a field that doesn't exist.");
                }
            }
        }
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
     * @param bool                            $includeValue
     *
     * @return array
     */
    private function checkFieldProperties(Field $field, $includeValue = FALSE) {
        $reflection = new \ReflectionObject($field);
        $defaultValues = $reflection->getDefaultProperties();
        $changes = [];
        foreach ($reflection->getProperties() as $fieldValues) {
            $fieldValue = $field->__get($fieldValues->name);
            $data = [];
            if ($includeValue) {
                $data["value"] = $fieldValue;
                if (in_array($fieldValue, $defaultValues)) {
                    $data["default"] = TRUE;
                } else {
                    $data["default"] = FALSE;
                }
            } else {
                if (in_array($fieldValue, $defaultValues)) {
                    $data = TRUE;
                } else {
                    $data = FALSE;
                }
            }
            $changes[$fieldValues->name] = $data;
        }
        return $changes;
    }

}
