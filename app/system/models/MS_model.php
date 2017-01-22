<?php

namespace App\system\models;

use App\system\models\properties\MS_property;

/**
 * Class MS_model: this is the model class to be extended of the model
 * @package system\models
 */
abstract class MS_model {

    /**
     * MS_resource name to be used for the connection
     * if no name is given the default will be used
     * @var null
     */
    protected $dataBaseConnection = NULL;

    /**
     * array filled with MS_property objects
     * @var array
     */
    private $fieldCollection;

    /**
     * name of the model
     * @var string
     */
    private $modelName;

    /**
     * MS_model constructor.
     */
    final function __construct() {
        $this->setModelName();
    }

    /**
     * @return null
     */
    public function getDataBaseConnection() {
        return $this->dataBaseConnection;
    }

    /**
     * @return mixed
     */
    public function getFieldCollection() {
        return $this->fieldCollection;
    }

    /**
     * @param \App\system\models\properties\MS_property $property
     *
     * @internal param \system\models\properties\MS_property $type type of the property
     */
    protected function addField(MS_property $property) {
        $this->fieldCollection[] = $property;
    }


    /**
     * we loop though the passed data and through the fields
     * will only fill the current model
     *
     * @param $data : fill the model with an associate array
     *
     * @throws \Exception
     */
    public function fillModel($data) {
        foreach ($data as $name => $item) {
            foreach ($this->fieldCollection as $field) {
                if ($field->name == $name) {
                    $this->fillProperty($field, $item);
                    break;
                }
            }
        }
    }

    /**
     * @param \App\system\models\properties\MS_property   $name
     * @param                                             $data
     */
    private function fillProperty(MS_property $name, $data) {
        $name->setValue($data);
        $name->validateProperty();
    }

    /**
     * this function will set the model name
     */
    private function setModelName() {
        $modelInformation = new \ReflectionClass($this);
        $this->modelName = $modelInformation->getShortName();
    }

    /**
     * @return string
     */
    public function getLongModelName() {
        return $this->modelName;
    }

    /**
     * @return string
     */
    public function getShortModelName() {
        return rtrim($this->modelName, "Model");
    }

    /**
     * within this method you may setup the model
     * @return mixed
     */
    abstract public function up();

    /**
     * within this method you may setup the model
     * @return mixed
     */
}