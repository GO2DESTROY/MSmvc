<?php

namespace MSmvc\system\models\properties;

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
	private $notNull = FALSE;
	public $externalResourceTable = NULL;
	private $autoIncrement;
	public $value;
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

}