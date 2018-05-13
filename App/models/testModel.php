<?php

namespace App\models;

use App\system\models\Model;
use App\system\models\fields\Integer;
use App\system\models\fields\Varchar;

class testModel extends Model {

    public function up() {
        $this->dataBaseConnection = 'development';
        $id = new Integer();


        $id->name = "id";
        $id->setLength(23);
        $id->setPrimaryKey(TRUE);
        $id->setAutoIncrement(TRUE);

        try {
            $this->addField($id);
            $this->addField(int("id2")->setAutoIncrement(TRUE));
            $this->addField(string("name"));
            $test = new Varchar();
            $test->name = 'test';
            $this->addField($test);
        } catch (\Exception $e) {

        }
    }
}
