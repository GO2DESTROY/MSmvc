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
        $this->fields[] = $field;
    }

    protected function updateField(Field $field) {
    //    $this->fields[$field->name] = $field;
    }

    protected function deleteField(string $name) {
    //    unset($this->fields[$name]);
    }

    abstract function up();
}