<?php

namespace App\system\databases;

use App\system\models\fields\Field;

/**
 * Class MS_databaseMigrations
 * @package system\databases
 */
abstract class Migration {

    private $fields;   //array of fields

    /**
     * @return mixed
     */
    public function getFields() {
        return $this->fields;
    }

    protected function createField(Field $field) {
        $this->fields[$field->name] = ["field" => $field, "new" => TRUE];
    }

    protected function updateField(Field $field) {
        $this->fields[$field->name] = ["field" => $field, "new" => FALSE];;
    }

    protected function deleteField(string $name) {
            unset($this->fields[$name]);
    }

    abstract function up();

    function getDifference(Migration $migration){
        $self = get_object_vars($this);
        $other = get_object_vars($migration);
        $differences = [];
        $differences["changed"] = array_diff($other, $self);
        $differences["removed"] = array_diff($self, $other);
        foreach ($differences["changed"] as $key => $value){
            if (isset($differences["removed"][$key])){
                unset($differences["removed"][$key]);
            }
        }
        return $differences;
    }
}