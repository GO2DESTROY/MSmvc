<?php

/*
 * todo: make a pdf file generator
 */
class MS_pdf extends \system\MS_core implements blueprints\MS_mainInterface
{
	public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return $this->$name;
	}
}