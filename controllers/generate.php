<?php

use system\generators\MS_generate;

class generate extends MS_controller
{
	public function controller($name) {
		MS_generate::generateController($name);
	}
	public function model($name) {
		MS_generate::generateController($name);
	}

}