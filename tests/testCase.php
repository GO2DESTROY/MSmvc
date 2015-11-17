<?php

class testCase extends bootstrap
{
	public function createApplication() {
		require '../system/MS_core.php';
		require '../system/MS_main.php';
		$MS_main = new system\MS_main();
	}
}