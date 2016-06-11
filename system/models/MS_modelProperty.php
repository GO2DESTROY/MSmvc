<?php

namespace system\models;

class MS_modelProperty {
	public $name;
	public $property;

	/**
	 * @param $property : this will set the property if the property is valid
	 */
	public function __construct($property){
		$this->property = $property;
		$this->validateProperty();
	}

	public function validateProperty(){
		if (!is_subclass_of($this->property, 'system\models\properties\MS_property')) {

			throw new \Exception("The property doesn't extend the MS_property class");
		}
	}
}