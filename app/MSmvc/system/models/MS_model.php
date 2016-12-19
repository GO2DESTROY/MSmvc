<?php

namespace MSmvc\system\models;

use MSmvc\system\models\properties\MS_property;

/**
 * Class MS_model: this is the model class to be extended of the model
 * @package system\models
 */
class MS_model {
	protected $dataBaseConnection = NULL;
	private $fieldCollection;

    /**
     * @param \MSmvc\system\models\properties\MS_property $property
     *
     * @internal param \system\models\properties\MS_property $type type of the property
     */
	protected function addField(MS_property $property) {
		$this->fieldCollection[] = $property;
	}

	/**
	 * @return array returns the structure of the model
	 */
	public function getModelStructure() {
		return ['database' => $this->dataBaseConnection, 'fields' => $this->fieldCollection];
	}
}