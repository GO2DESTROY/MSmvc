<?php

namespace MSmvc\controllers;

use MSmvc\system\databases\MS_databaseResource;
use MSmvc\system\databases\MS_db;
use MSmvc\models\testModel;
use MSmvc\system\databases\MS_queryBuilder;

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
        $qu->insert($test);
  //      $qu->delete($test)->where("id");
    //    $qu->select()->from("test");
        //$qu->modelToTable();
        $result = $qu->execute([3]);
        var_dump($result);
	}
}