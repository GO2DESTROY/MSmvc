<?php

namespace App\controllers;

use App\models\someModel;
use App\models\typesModel;
use App\system\databases\migrations\MS_migrations;
use App\models\testModel;
use App\system\MS_controller;

class example extends MS_controller {
    /**
     * @return mixed: the html page to return
     */
    public function index() {
        //	MS_databaseResource::create(['name' => 'development', 'settings' => ['host' => '127.0.0.1', 'driver' => 'mysql', 'port' => 3306, 'database' => 'test', 'username' => 'root', 'password' => '']]);
        $test = new testModel();
        //$qb = new MS_queryBuilder();
        //todo: in de migratie de up uitvoeren
     //   echo $test->getShortModelName();


        MS_migrations::addMigrationModel($test);
        MS_migrations::addMigrationModel(new  typesModel());
        MS_migrations::addMigrationModel(new someModel());
        new MS_migrations();

        //$migration = new MS_migrations();
        //  $results = $qb->update("test")->set(["test" =>"tetetetetete"])->where("id",":test")->execute([":test"=>250]);
        //    var_dump($qb);
        //     var_dump($results);
        //  $test->fillModel($qb->select()->where("id")->execute([249])[0]);
        //   var_dump($test);
        // $test->fillModel();

        //$qu = new MS_queryBuilder();E
        //   $qu->update($test);
        //return view("example");
        // $qu->insertBulk([["name"=>"test", "test" =>"adsfasdf"],["name" => "asdfadsfads", "test"=>"adsfffffffdsf"]])->into($test)->execute();

    }
}