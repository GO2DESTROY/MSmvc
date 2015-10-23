<?php

/*
 * todo: make an audio helper for audio streaming
 */
class MS_audio extends \system\MS_core implements blueprints\MS_mainInterface
{
	public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return $this->$name;
	}
}