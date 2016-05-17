<?php

namespace system\models\properties;
abstract class MS_property{
	public $name;
	public $value;

	/**
	 * @return bool: true if the validation is correct
	 * @throws \Exception: exception of the type invalidPropertyException
	 */
	abstract function validateProperty();
	
}