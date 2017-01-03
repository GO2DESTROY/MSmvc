<?php
//this is the session set
return [
	/*
	 * options are file, memcached / redis, cookies, database
	 * currently only file is supported
	 */

	'driver' => 'file',

	/*
	 * Use the database connection set you wish to use for the sessions
	 * table: the database table you wish to use
	 */

	'connection' => false,
	'table' =>'sessions',

	/*
	 * file-location: the location to safe the session data
	 */

	'file-location' => 'storage/sessions',

	/*
	 * lifetime the amount of time in seconds it takes till the session expires
	 */

	'lifetime' => 12000];