<?php

namespace system\pipelines;

use system\MS_core;

class MS_pipeline extends MS_core
{
	public function loadRequest()
	{

	}
	public static function returnConfig($file)
	{
		return include dirname($_SERVER["SCRIPT_FILENAME"]).'/config/'.$file.'.php';
	}
// todo: make a pipeline sublayer to interacte with data providers
}