<?php

/*
 * todo: use Session PgSQL for session management and a custom fallback for windows
 * note to self PgSQL is dead and no longer supported but still installed on php for linux by default
 */
class MS_session extends \system\MS_core implements blueprints\MS_mainInterface
{
	public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return $this->$name;
	}
}