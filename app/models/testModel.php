<?php

namespace App\models;

use App\system\models\MS_model;
use App\system\models\properties\integer;
use App\system\models\properties\varchar;

class testModel extends MS_model {

    public function up() {
		$this->dataBaseConnection = 'development';
		$id = new integer();
		$id->name = "id";
		$id->setPrimaryKey(true);
		$id->setAutoIncrement(true);
		$this->addField($id);


		$this->addField(string("name"));

		$test = new varchar();
		$test->name = 'test';
		$this->addField($test);

		$type = new integer();
		$type->name = "TypeRefrenceIdFromTest";
		$type->setForeignKey("some","someId");
		$this->addField($type);
	}
}