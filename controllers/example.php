<?php

namespace controllers;

use system\databases\MS_databaseResource;
use system\databases\MS_db;

class example {
	/**
	 * @return mixed: the html page to return
	 */
	public function index(){
	dd(array_sum([7,7,8,11,15]));
	}
}