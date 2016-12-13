<?php

namespace system\models\properties;

/**
 * Class MS_property: this abstract class will be used as a blueprint for the database field
 * @package system\models\properties
 */
abstract class MS_property {
	public $Name;
	public $Value = 25;
	public $Default = NULL;
	public $NotNull = FALSE;
	public $ExternalResourceTable = NULL;
	public $AutoIncrement;

	/**
	 * @return bool: true if the validation is correct
	 * @throws \Exception: exception of the type invalidPropertyException
	 */
	abstract function validateProperty();

}