<?php
namespace App\system\databases;

use App\system\models\properties\Property;

/**
 * Class MS_databaseMigrations
 * @package system\databases
 */
abstract class Migration {

    private $fields;

    private function commitChanges() {
        //todo: make this function to commit all the changes that are pending
    }

    protected function createField(Property $field) {
        $this->fields[] = $field;
    }

    protected function updateField(Property $field) {
    //    $this->fields[$field->name] = $field;
    }

    protected function deleteField(string $name) {
    //    unset($this->fields[$name]);
    }

    abstract function up();
}