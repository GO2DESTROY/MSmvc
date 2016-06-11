<?php

namespace controllers;

use models\gebruikersModel;
use system\databases\MS_db;
use system\helpers\MS_utilization;
use system\models\MS_model;
use system\models\MS_modelProperty;

class example {
	/**
	 * @return mixed: the html page to return
	 */
	public function index() {
		$test = new gebruikersModel;
		dd($test);
		new MS_db();
	}

	/**
	 * @return bool: we return true because the framework booted successful if it could execute this.
	 */
	public function phpUnit() {
		return TRUE;
	}
}