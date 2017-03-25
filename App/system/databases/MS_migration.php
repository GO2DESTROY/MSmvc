<?php
namespace App\system\databases;

use App\system\models\properties\MS_property;

/**
 * Class MS_databaseMigrations
 * @package system\databases
 */
abstract class MS_migration {

    private $fields;

    private function commitChanges() {
        //todo: make this function to commit all the changes that are pending
    }

    protected function createField(MS_property $field) {
        $this->fields[] = $field;
    }

    protected function updateField(MS_property $field) {
    //    $this->fields[$field->name] = $field;
    }

    protected function deleteField(string $name) {
    //    unset($this->fields[$name]);
    }

    abstract function up();
}