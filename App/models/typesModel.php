<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 13-Jan-17
 * Time: 11:46
 */

namespace App\models;



use App\system\models\Model;
use App\system\models\fields\Integer;
use App\system\models\fields\Varchar;

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