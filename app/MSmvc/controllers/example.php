<?php

namespace MSmvc\controllers;

use MSmvc\system\databases\MS_databaseResource;
use MSmvc\system\databases\MS_db;
use MSmvc\models\testModel;
use MSmvc\system\databases\MS_queryBuilder;
use MSmvc\system\MS_controller;
use MSmvc\system\router\MS_route;

class example extends MS_controller{
	/**
	 * @return mixed: the html page to return
	 */
	public function index(){
	//	MS_databaseResource::create(['name' => 'development', 'settings' => ['host' => '127.0.0.1', 'driver' => 'mysql', 'port' => 3306, 'database' => 'test', 'username' => 'root', 'password' => '']]);

		$db = new MS_db('development');
        $test = new testModel();

        $qu = new MS_queryBuilder();
return view("example");
       // $qu->insertBulk([["name"=>"test", "test" =>"adsfasdf"],["name" => "asdfadsfads", "test"=>"adsfffffffdsf"]])->into($test)->execute();

	}
}