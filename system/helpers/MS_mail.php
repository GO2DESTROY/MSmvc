<?php

/*
 * todo: add a sub layer between swiftmail phpmailer or create your own
 */
class MS_mail extends \system\MS_core implements blueprints\MS_mainInterface
{
	public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return $this->$name;
	}
}