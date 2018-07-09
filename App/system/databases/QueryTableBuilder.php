<?php

namespace App\system\databases;

use App\system\models\fields\Field;
use App\system\models\Model;


/**
 * This class will be used to convert a model to a table
 *
 * Class QueryTableBuilder
 * @package system\databases
 */
class QueryTableBuilder extends QueryBuilder {
    use SqlStatements;

    /**
     * QueryTableBuilder constructor.
     *
     * @param \App\system\fields\Model $model
     */
    function __construct(Model $model) {
        parent::__construct($model);
    }

    /**
     * this function will set all the fields for a single field
     *
     * @param \App\system\fields\properties\Field $property
     *
     * @return string
     */
    private function propertyToField(Field $property) {
        return rtrim("$property->name $property->type ($property->length) " . $property->getAutoIncrement() . $property->getNotNull(), " ") . ", ";
    }

    /**
     * @return $this
     */
    public function modelToTable() {
        $this->type = 'CREATE TABLE';
        return $this;
    }

    /**
     * @return mixed
     */
    private function getPrimaryKeysString() {
        return "PRIMARY KEY(" . implode(",", $this->primaryKeys) . ")";
    }

    /**
     * @param Field $property
     *
     * @internal param mixed $primaryKeys
     */
    public function setPrimaryKeys(Field $property) {
        if ($property->isPrimaryKey() == TRUE) {
            $this->primaryKeys[] = $property->name;
        }
    }

    protected function buildQuery() {
        switch ($this->type) {
            case 'CREATE TABLE':
                $query = "$this->sql_create_table $this->table (";
                foreach ($this->model->getFieldCollection() as $field) {
                    //todo: remove if statement from the loop
                    $query .= $this->propertyToField($field);
                    $this->setPrimaryKeys($field);
                }
                $query .= $this->getPrimaryKeysString();
                $this->addStatementToQuery($query . ")");
                break;
            case 'ALTER TABLE':
                break;
            default:
                break;
        }
    }
}
