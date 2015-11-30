<?php
session_start();

class MS_session
{
	private static $session;
	public static  $driver = ['driver' => 'file', 'location' => '/storage/sessions'];

	public function driverInteraction() {
		switch(self::$driver['driver']) {
			case 'file':
				session_save_path(self::$driver['location']);
				break;
		}
	}

	public static function add($key, $value) {

	}
}