<?php

namespace controllers;

use models\gebruikersModel;
use system\databases\MS_db;
use system\helpers\MS_utilization;
<<<<<<< HEAD
use system\models\MS_model;
use system\models\MS_modelProperty;
=======
use system\MS_controller;
use system\pipelines\MS_pipeline;
>>>>>>> origin/development

class example {
	/**
	 * @return mixed: the html page to return
	 */
	public function index() {
<<<<<<< HEAD
		$test = new gebruikersModel;
		dd($test);
		new MS_db();
=======
		dd(MS_pipeline::getClassesWithinDirectory('models'));
>>>>>>> origin/development
	}

	/**
	 * @return bool: we return true because the framework booted successful if it could execute this.
	 */
	public function phpUnit() {
		return TRUE;
	}
}