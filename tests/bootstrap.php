<?php

class bootstrap
{
	/**
	 * we load the MSmvc and call the MS_main
	 * we simulate a request so that way we can trigger all request types and debug the core as well.
	 */
	function __construct() {
		require '../system/MS_core.php';
		require_once 'PHPUnit.php';
	}
}