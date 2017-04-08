<?php

namespace App\models;

use App\system\models\Model;
use App\system\models\properties\Integer;
use App\system\models\properties\Varchar;

class testModel extends Model {

	public function up() {
		$this->dataBaseConnection = 'development';
		$id = new Integer();
		$id->name = "id";
		$id->setLength(23);
		$id->setPrimaryKey(true);
		$id->setAutoIncrement(true);
		$this->addField($id);

		$this->addField(int("id")->setAutoIncrement(TRUE));
		$this->addField(string("name"));

		$test = new Varchar();
		$test->name = 'test';
		$this->addField($test);

		$type = new Integer();
		$type->name = "TypeRefrenceIdFromTest";
		$type->setForeignKey("some", "someId");
		$this->addField($type);
	}
}