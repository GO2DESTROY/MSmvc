<?php

// Here we define the database settings
return [

	'defaultConnectionSet' => 'development', // this is the dataSet to be used as default

	'development' => ['host' => '127.0.0.1', // the host of the DB
		'driver' => 'mysql', // set the database driver
		'port' => 3306, // set the database port
		'database' => 'test', // the database you wish to use
		'username' => 'root', // username to use for this database connection
		'password' => ''], // the password for the user

	'live' => ['host' => '127.0.0.1', // the host of the DB
		'driver' => 'mysql', // set the database driver
		'port' => 3306, // set the database port
		'database' => 'test', // the database you wish to use
		'username' => 'root', // username to use for this database connection
		'password' => '']]; // the password for the user
