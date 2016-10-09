<?php

namespace system\models;

use system\models\properties\MS_property;

/**
 * Class MS_model: this is the model class to be extended of the model
 * @package system\models
 */
class MS_model {
	protected $dataBaseConnection = NULL;
	private $fieldCollection;

	/**
	 * @param string      $name name of the property to add
	 * @param MS_property $type type of the property
	 */
	protected function addField(string $name, MS_property $type) {
		$this->fieldCollection[] = ['name' => $name, new MS_modelProperty($type)];
	}

	/**
	 * @return array returns the structure of the model
	 */
	public function getModelStructure() {
		return ['database' => $this->dataBaseConnection, 'fields' => $this->fieldCollection];
	}
}