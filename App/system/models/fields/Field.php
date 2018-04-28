<?php

namespace App\system\models\fields;

use App\system\models\Model;

/**
 * Class Field: this abstract class will be used as a blueprint for the database field
 * @package system\models\fields
 */
abstract class Field {
    /**
     * name of the field
     * @var string
     */
    public $name;

    /**
     * length of the field
     * @var int
     */
    public $length = 25;

    /**
     * SQL property
     * @var string
     */
    public $type = 'Varchar';

    /**
     * default value
     * @var null
     */
    public $default = NULL;

    /**
     * not used
     * @var
     */
    public $collation;

    /**
     * not used
     * @var
     */
    public $attributes;

    /**
     * not null value
     * @var string
     */
    private $notNull = NULL;

    /**
     * Autoincrement for int fields
     * @var string | null
     */
    private $autoIncrement;

    /**
     * the value that will be used for insert or update
     * @var string
     */
    public $value;

    /**
     * true or false if this field is marked for a primary key
     * @var bool
     */
    private $primaryKey = FALSE;

    /**
     * False or a instance of another property
     * @var string
     */
    private $referencePropertyString = FALSE;

    /**
     * @var string
     */
    private $referenceModelString = FALSE;


    /**
     * @return string
     */
    public function getNotNull() {
        return $this->notNull;
    }

    /**
     * @param boolean $notNull
     *
     * @return $this
     */
    public function setNotNull(bool $notNull = TRUE) {
        if ($notNull === TRUE) {
            $this->notNull = "NOT NULL ";
        } else {
            $this->notNull = NULL;
            $this->setPrimaryKey(FALSE);
        }
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrimaryKey(): bool {
        return $this->primaryKey;
    }

    /**
     * @param boolean $primaryKey
     *
     * @return $this
     */
    public function setPrimaryKey(bool $primaryKey = TRUE) {
        if ($primaryKey == TRUE) {
            $this->setNotNull(TRUE);
        }
        $this->primaryKey = $primaryKey;
        return $this;
    }

    /**
     * @return bool: true if the validation is correct
     * @throws \Exception: exception of the type invalidPropertyException
     */
    abstract public function validateProperty();

    /**
     * @return mixed
     */
    public final function getAutoIncrement() {
        return $this->autoIncrement;
    }

    /**
     * @param bool $autoIncrement
     */
    protected function setAutoIncrement(bool $autoIncrement = TRUE) {
        if ($autoIncrement === TRUE) {
            $this->autoIncrement = "AUTO_INCREMENT ";
        } else {
            $this->autoIncrement = NULL;
        }
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }


    /**
     * @param string | Model $refrenceModel
     * @param string         $refrenceProperty
     */
    protected function setForeignKey($refrenceModel, $refrenceProperty) {
        if ($refrenceModel instanceof Model) {
            $this->referenceModelString = $refrenceModel->getShortModelName();
        } else {
            $this->referenceModelString = $refrenceModel;
        }
        $this->referencePropertyString = $refrenceProperty;
    }

    /**
     * @return string
     */
    public function getReferenceProperty() {
        return $this->referencePropertyString;
    }


    /**
     * @return string
     */
    public function getReferenceModel() {
        return $this->referenceModelString;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public final function setName(string $name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $length
     *
     * @return $this
     */
    public final function setLength($length) {
        $this->length = $length;
        return $this;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name) {
        return $this->$name;
    }

    /**
     * @param \App\system\models\fields\Field $property
     *
     * @return bool
     */
    public function compare(Field $property) {
        return $this == $property ? TRUE : FALSE;
    }

    /**
     * @param \App\system\models\fields\Field $property
     *
     * @return array: the differences
     */
    public function getDifferences(Field $property) {
        $self = get_object_vars($this);
        $other = get_object_vars($property);
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