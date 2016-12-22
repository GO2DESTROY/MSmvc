<?php

namespace MSmvc\models;

use MSmvc\system\models\MS_model;
use MSmvc\system\models\properties\integer;
use MSmvc\system\models\properties\varchar;

class testModel extends MS_model {
	function __construct() {
		$this->dataBaseConnection = 'development';
		$id = new integer();
		$id->name = "id";
		$id->setPrimaryKey(true);
		$id->setAutoIncrement(true);

		$this->addField($id);
		$name = new varchar();
		$name->name = 'name';

		$this->addField($name);

		$test = new varchar();
		$test->name = 'test';

		$this->addField($test);
	}
}