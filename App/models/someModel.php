<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 25-Jan-17
 * Time: 18:53
 */

namespace App\models;


use App\system\models\Model;

class someModel extends Model {
    public function up() {
        // TODO: Implement up() method.
        $this->addField(string("testSome"));
        $this->addField(int("someId"));
    }
}