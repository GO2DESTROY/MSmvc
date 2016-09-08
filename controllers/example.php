<?php

namespace controllers;

use system\databases\MS_databaseResource;
use system\databases\MS_db;

class example {
	/**
	 * @return mixed: the html page to return
	 */
	public function index(){
		MS_databaseResource::create(['name' => 'test', 'settings' => ['host' => '127.0.0.1', 'driver' => 'mysql', 'port' => 3306, 'database' => 'test', 'username' => 'root', 'password' => '']]);
		//var_dump(MS_databaseResource::getDataBaseResourceSet());
		echo('it works');
	}
}