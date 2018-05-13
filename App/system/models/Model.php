<?php

namespace App\system\models;

use App\system\DataStructureFile;
use App\system\models\fields\Field;

/**
 * Class Model: this is the model class to be extended of the model
 * @package system\models
 */
abstract class Model extends DataStructureFile {

    /**
     * MS_resource name to be used for the connection
     * if no name is given the default will be used
     * @var string
     */
    protected $dataBaseConnection = NULL;

    /**
     * array filled with Property objects
     * @var array
     */
    private $fieldCollection;

    /**
     * Model constructor.
     * @throws \ReflectionException
     */

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
     * @param \App\system\models\fields\Field $property
     *
     * @internal param \system\models\fields\Property $type type of the property
     * @throws \Exception
     */
    protected function addField(Field $property) {
        if(empty($this->fieldCollection[$property->name])) {
            $this->fieldCollection[$property->name] = $property;
        }
        else
        {
            throw new \Exception('Field already exists.');
        }
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
     * @param \App\system\models\fields\Field         $name
     * @param                                         $data
     *
     * @throws \Exception
     */
    private function fillProperty(Field $name, $data) {
        $name->setValue($data);
        $name->validateProperty();
    }

    /**
     * @return string
     */
    public function getShortModelName() {
        return str_replace("Model","",$this->getFileName());
    }

    /**
     * will return true if the object fields match
     *
     * @param \App\system\models\Model $otherModel
     *
     * @return bool
     */
    public final function compare(Model $otherModel){
      //  foreach ($otherModel)
        if($this == $otherModel){
            return TRUE;
        }
        else{
            return FALSE;
        }
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
