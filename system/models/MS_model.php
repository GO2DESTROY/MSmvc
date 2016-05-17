<?php

namespace system\models;
class MS_model{
	protected $dataBaseConnection;
	private $fieldCollection;

	/**
	 * @param string $name name of the property to add
	 * @param string $type type of the property
	 */
	protected function addField(string $name, string $type){
		$this->fieldCollection[] = new MS_modelProperty();
	}
}