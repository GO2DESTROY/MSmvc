<?php

namespace system\models\properties;
abstract class MS_property {
	public $name;
	protected $value = 25;
	public $default = NULL;
	public $null = FALSE;
	public $externalResourceTable = NULL;
	public $autoIncrement;

	/**
	 * @return bool: true if the validation is correct
	 * @throws \Exception: exception of the type invalidPropertyException
	 */
	abstract function validateProperty();

}