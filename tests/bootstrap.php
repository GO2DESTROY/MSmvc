<?php

class bootstrap
{
	/**
	 * we load the MSmvc and call the MS_main
	 * we simulate a request so that way we can trigger all request types and debug the core as well.
	 */
	function __construct() {
		require './system/MS_core.php';
		require './system/MS_main.php';
		$MS_main          = new system\MS_main();
		$MS_main->phpUnit = TRUE;
		$MS_main->boot();
	}

	/**
	 * we use a protected method to set the values
	 *
	 * @param $key   : the key to set
	 * @param $value : the value to set
	 */
	protected function __set($key, $value) {

	}

}