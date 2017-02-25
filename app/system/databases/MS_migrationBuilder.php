<?php

namespace App\system\databases;

use App\system\databases\migrations\test;
use App\system\models\MS_model;
use App\system\models\properties\MS_property;
use App\system\pipelines\MS_pipeline;


/**
 * Class MS_migrationBuilder
 *
 * this class will build a migration based on a model
 */
class MS_migrationBuilder {

    /**
     * this is the current model that we will use to create new migrations
     * @var MS_model
     */
    private $model;

    /**
     * changes that need to be made this array contains all the changes to duplicate to model to a migration minus the
     * changes that occurred in previous migrations
     * @var array
     */
    private $changeSet;

    /**
     * MS_migrationBuilder constructor.
     *
     * @param \App\system\models\MS_model $model
     */
    function __construct(MS_model $model = NULL) {
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
    public function setModel(MS_model $model) {
        $this->model = $model;
        $this->model->up();
        return $this;
    }

    public function execute() {
        $this->buildChangeSet();
        var_dump($this->changeSet);
        //do stuff execute all the things
    }

    private function buildChangeSet() {
        var_dump(showWholeDirectory("app/system/databases/migrations", "/*_" . $this->model->getShortModelName(),".php",TRUE));

   //     var_dump(MS_pipeline::getClassesWithinDirectory("app/system/databases/migrations"));
        foreach ($this->model->getFieldCollection() as $field) {
            //todo: mark the fields that are changed
            //todo: mark the fields that are default ---------done
            $this->changeSet[$field->name] = $this->checkFieldProperties($field);

        }
    }

    /**
     * todo: check this for the history
     * @param \App\system\models\properties\MS_property $field
     *
     * @return array
     */
    private function checkFieldProperties(MS_property $field) {
        $reflection = new \ReflectionObject($field);
        $defaultValues = $reflection->getDefaultProperties();
        $changes = [];
        foreach ($reflection->getProperties() as $fieldValues) {
            $fieldValue = $field->__get($fieldValues->name);
            $data=[];
            $data["value"] = $fieldValue;
            if (in_array($fieldValue, $defaultValues)) {
                $data["default"] = TRUE;
            }else{
                $data["default"] = FALSE;
            }
            $changes[$fieldValues->name] = $data;
        }
        return $changes;
    }

    /**
     * work in progress
     */
    private function checkHistory() {
        $test = showWholeDirectory("app/system/databases/migrations", "/*_" . $this->model->getShortModelName());
        $classtest = MS_pipeline::getClassesWithinFile($test[0]);
        // new App\system\databases\migrations\test();
        $ct = new $classtest[0];
        //$th = new $test[0];
    }
}