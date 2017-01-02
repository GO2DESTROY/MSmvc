<?php

namespace MSmvc\system\models\properties;

use MSmvc\system\models\MS_model;

/**
 * Class MS_property: this abstract class will be used as a blueprint for the database field
 * @package system\models\properties
 */
abstract class MS_property {
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
    public $type = 'varchar';

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
     * @var bool
     */
    private $notNull = FALSE;

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
     * @var MS_property
     */
    private $refrenceProperty = FALSE;

    /**
     * @var MS_model
     */
    private $refrenceModel;

    /**
     * @return boolean
     */
    public function isNotNull(): bool {
        return $this->notNull;
    }

    /**
     * @param boolean $notNull
     */
    public function setNotNull(bool $notNull = TRUE) {
        if ($notNull == FALSE) {
            $this->primaryKey = FALSE;
        }
        $this->notNull = $notNull;
    }

    /**
     * @return boolean
     */
    public function isPrimaryKey(): bool {
        return $this->primaryKey;
    }

    /**
     * @param boolean $primaryKey
     */
    public function setPrimaryKey(bool $primaryKey = TRUE) {
        if ($primaryKey == TRUE) {
            $this->notNull = TRUE;
        }
        $this->primaryKey = $primaryKey;
    }

    /**
     * @return bool: true if the validation is correct
     * @throws \Exception: exception of the type invalidPropertyException
     */
    abstract public function validateProperty();

    /**
     * @return mixed
     */
    public function getAutoIncrement() {
        return $this->autoIncrement;
    }

    /**
     * @param bool $autoIncrement
     *
     * @return $this
     */
    protected function setAutoIncrement(bool $autoIncrement = TRUE) {
        if ($autoIncrement === TRUE) {
            $this->autoIncrement = "AUTO_INCREMENT";
        } else {
            $this->autoIncrement = NULL;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * @param \MSmvc\system\models\MS_model               $refrenceModel
     * @param \MSmvc\system\models\properties\MS_property $refrenceProperty
     */
    public function setForeignKey(MS_model $refrenceModel, MS_property $refrenceProperty) {
        $this->refrenceModel = $refrenceModel;
        $this->refrenceProperty = $refrenceProperty;
    }

    /**
     * @return \MSmvc\system\models\properties\MS_property
     */
    public function getRefrenceProperty(){
        return $this->refrenceProperty;
    }

    /**
     * @return \MSmvc\system\models\MS_model
     */
    public function getRefrenceModel(){
        return $this->refrenceModel;
    }

}