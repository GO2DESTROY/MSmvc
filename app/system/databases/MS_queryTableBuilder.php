<?php

namespace App\system\databases;

use App\system\models\MS_model;
use App\system\models\properties\MS_property;

/**
 * This class will be used to convert a model to a table
 *
 * Class MS_queryTableBuilder
 * @package system\databases
 */
class MS_queryTableBuilder extends MS_queryBuilder {
    use MS_sqlStatements;

    /**
     * MS_queryTableBuilder constructor.
     *
     * @param \App\system\models\MS_model $model
     */
    function __construct(MS_model $model) {
        parent::__construct($model);
    }

    /**
     * this function will set all the properties for a single field
     *
     * @param \App\system\models\properties\MS_property $property
     *
     * @return string
     */
    private function propertyToField(MS_property $property) {
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
     * @param MS_property $property
     *
     * @internal param mixed $primaryKeys
     */
    public function setPrimaryKeys(MS_property $property) {
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
        }
    }
}