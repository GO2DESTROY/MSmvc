<?php

namespace MSmvc\system\models;

use MSmvc\system\models\properties\MS_property;

/**
 * Class MS_model: this is the model class to be extended of the model
 * @package system\models
 */
class MS_model {
    protected $dataBaseConnection = NULL;
    private $fieldCollection;

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
     * @param \MSmvc\system\models\properties\MS_property $property
     *
     * @internal param \system\models\properties\MS_property $type type of the property
     */
    protected function addField(MS_property $property) {
        $this->fieldCollection[] = $property;
    }

    /**
     * we loop though the passed data and through the fields
     * @param array $data : fill the model with an associate array
     */
    public function fillModel(array $data) {
        foreach ($data as $name => $item) {
            foreach ($this->fieldCollection as $field) {
                if ($field->name == $name) {
                    $this->fillProperty($field, $item);
                    break;
                }
            }
        }
        //model is filled and validated
    }

    /**
     * @param \MSmvc\system\models\properties\MS_property $name
     * @param                                             $data
     */
    private function fillProperty(MS_property $name, $data) {
        $name->setValue($data);
        $name->validateProperty();
    }
}