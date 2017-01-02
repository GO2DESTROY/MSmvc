<?php

namespace MSmvc\system\models\properties;

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
     * not used yet will be used for relations
     * @var null
     */
	public $externalResourceTable = NULL;

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
	private $primaryKey = false;

	/**
	 * @return boolean
	 */
	public function isNotNull(): bool {
		return $this->notNull;
	}

	/**
	 * @param boolean $notNull
	 */
	public function setNotNull(bool $notNull = true) {
		if ($notNull == false) {
			$this->primaryKey = false;
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
	public function setPrimaryKey(bool $primaryKey = true) {
		if ($primaryKey == true) {
			$this->notNull = true;
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
	 * @return $this
	 */
	protected function setAutoIncrement(bool $autoIncrement = true) {
		if ($autoIncrement === true) {
			$this->autoIncrement = "AUTO_INCREMENT";
		} else {
			$this->autoIncrement = null;
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