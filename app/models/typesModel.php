<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 13-Jan-17
 * Time: 11:46
 */

namespace App\models;


use App\system\models\MS_model;
use App\system\models\properties\integer;
use App\system\models\properties\varchar;

class typesModel extends MS_model {

    public function up() {
        $this->dataBaseConnection = 'development';

        $id = new integer();
        $id->name = "id";
        $id->setPrimaryKey(true);
        $id->setAutoIncrement(true);
        $this->addField($id);

        $name = new varchar();
        $name->name = 'Type name';
        $this->addField($name);
    }
}