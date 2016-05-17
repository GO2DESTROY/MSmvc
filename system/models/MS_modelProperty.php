<?php

namespace system\models;
class MS_modelProperty{
	public $name;
	private $property;
	/**
	 * @param $property: this will set the property if the property is valid
	 */
	public function __construct($property){
		$this->property = $property;
		$this->validateProperty();
	}

	/**
	 *
	 */
	public function validateProperty(){
		/**todo: We will match all properties against a list of known properties and a list of custom properties custom meaning a relation to another table
		verder lijst maken van alle pr
		 */
	}
}
