<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 13-Jan-17
 * Time: 11:46
 */

namespace App\models;


use App\system\fields\Model;
use App\system\fields\properties\Integer;
use App\system\fields\properties\Varchar;

class typesModel extends Model {

    public function up() {
        $this->dataBaseConnection = 'development';

        $id = new Integer();
        $id->name = "id";
        $id->setPrimaryKey(true);
        $id->setAutoIncrement(true);
        $this->addField($id);

        $name = new Varchar();
        $name->name = 'Type name';
        $this->addField($name);
    }
}