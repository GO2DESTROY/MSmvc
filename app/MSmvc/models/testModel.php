<?php
use system\models\properties\varchar;

class testModel extends \system\models\MS_model {
	function __construct() {
		$name = new varchar();
		$name->name = 'name';
		$name->value = '';


		$this->addField('naam', new varchar());
	}
}