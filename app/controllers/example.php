<?php

namespace App\controllers;

use App\system\databases\MS_databaseResource;
use App\system\databases\MS_db;
use App\models\testModel;
use App\system\databases\MS_queryBuilder;
use App\system\MS_controller;
use App\system\router\MS_route;

class example extends MS_controller {
    /**
     * @return mixed: the html page to return
     */
    public function index() {
        //	MS_databaseResource::create(['name' => 'development', 'settings' => ['host' => '127.0.0.1', 'driver' => 'mysql', 'port' => 3306, 'database' => 'test', 'username' => 'root', 'password' => '']]);
        $test = new testModel();
        $qb = new MS_queryBuilder();
        $results = $qb->update("test")->set(["test" =>"tetetetetete"])->where("id",":test")->execute([":test"=>250]);
        var_dump($qb);
        var_dump($results);
        //  $test->fillModel($qb->select()->where("id")->execute([249])[0]);
        //   var_dump($test);
        // $test->fillModel();

        //$qu = new MS_queryBuilder();E
        //   $qu->update($test);
        //return view("example");
        // $qu->insertBulk([["name"=>"test", "test" =>"adsfasdf"],["name" => "asdfadsfads", "test"=>"adsfffffffdsf"]])->into($test)->execute();

    }
}