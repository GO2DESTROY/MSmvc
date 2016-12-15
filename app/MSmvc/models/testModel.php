<?php

namespace MSmvc\models;
use MSmvc\system\models\MS_model;
use MSmvc\system\models\properties\varchar;

class testModel extends MS_model {
	function __construct() {
	    $this->dataBaseConnection = 'development';
		$name = new varchar();
		$name->name = 'name';

		$this->addField($name);
	}
}