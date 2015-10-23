<?php

// this is the system class that takes care of the model calls
// this class will take care of loading the model and calling it and returning the result
class MS_model extends \system\MS_core implements blueprints\MS_mainInterface
{
	public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return $this->$name;
	}
}