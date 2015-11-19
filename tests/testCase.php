<?php

use system\MS_main;

require_once '../system/MS_core.php';
require_once '../system/MS_main.php';

class testCase extends PHPUnit_Framework_TestCase
{

	/**
	 * a php unit test that boots the whole framework
	 */
	public function testBootingMSmvc() {
		$main                       = new MS_main();
		$main->uri                  = '/phpunit';
		$main->currentRequestMethod = 'GET';
		$result                     = $main->boot();
		$this->assertTrue($result);
	}
}