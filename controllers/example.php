<?php

namespace controllers;

use system\helpers\MS_utilization;
use system\MS_controller;
use system\pipelines\MS_pipeline;

class example {
	/**
	 * @return mixed: the html page to return
	 */
	public function index() {
		dd(MS_pipeline::getClassesWithinDirectory('models'));
	}

	/**
	 * @return bool: we return true because the framework booted successful if it could execute this.
	 */
	public function phpUnit() {
		return TRUE;
	}
}