<?php

namespace controllers;

use MSmvc\system\databases\MS_databaseResource;
use MSmvc\system\databases\MS_db;
use MSmvc\models\testModel;
use MSmvc\system\databases\MS_modelQueryBuilder;

class example {
	/**
	 * @return mixed: the html page to return
	 */
	public function index(){
	//	MS_databaseResource::create(['name' => 'development', 'settings' => ['host' => '127.0.0.1', 'driver' => 'mysql', 'port' => 3306, 'database' => 'test', 'username' => 'root', 'password' => '']]);

		$db = new MS_db('development');
        $test = new testModel();

        $test->fillModel(["name"=>"test","test"=>"noTEST"]);
        $qu = new MS_queryBuilder();
        $qu->insert(["name"=>"name2","test"=>"test2"])->into($test);
  //      $qu->delete($test)->where("id");
    //    $qu->select()->from("test");
        //$qu->modelToTable();
        $result = $qu->execute();

        $qu = new MS_modelQueryBuilder($test);
        $qu->modelToTable();


        var_dump($result);
	}
}