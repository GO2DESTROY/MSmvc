<?php

use system\MS_controller;

class example extends MS_controller
{
	public function index() {
		return $this->view('example');
	}
}