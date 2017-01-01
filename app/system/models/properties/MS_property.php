<?php

namespace system\models\properties;

/**
 * Class MS_property: this abstract class will be used as a blueprint for the database field
 * @package system\models\properties
 */
abstract class MS_property {
    public $name;
    public $length = 25;
    public $type = 'varchar';
    public $default = NULL;
    public $collation;
    public $attributes;
    private $notNull = NULL;
    public $externalResourceTable = NULL;
    private $autoIncrement;
    protected $value;
    private $primaryKey = FALSE;


    /**
     * @param boolean $notNull
     *
     * @return $this
     */
    protected function setNotNull(bool $notNull = TRUE) {
        if ($notNull === TRUE) {
            $this->notNull = " NOT NULL";
        } else {
            $this->notNull = NULL;
        }
        return $this;
    }

    /**
     * @return null
     */
    public function getNotNull() {
        return $this->notNull;
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
            $this->setNotNull(TRUE);
        }
        $this->primaryKey = $primaryKey;
    }

    /**
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
            $this->autoIncrement = " AUTO_INCREMENT";
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

}