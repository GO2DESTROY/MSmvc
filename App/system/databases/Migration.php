<?php

namespace App\system\databases;

use App\system\DataStructureFile;
use App\system\models\fields\Field;
use App\system\models\Model;

/**
 * Class MS_databaseMigrations
 * @package system\databases
 */
abstract class Migration extends DataStructureFile {

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

    protected function replaceField(Field $field){
        //todo: make this an option for when we are not sure what to use for the basic Migration!
    }

    protected function deleteField(string $name) {
            unset($this->fields[$name]);
    }

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

    abstract function up();
}
