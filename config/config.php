<?php

return [

	//this defines the set of config settings to use so you can easy switch between a development and live version
	'environment' => 'development',

	'development' => ['error-logging' => 'MS_handler', // we can choice between the custom MS_handler, none
		'url' => 'http://localhost', // the default url you could leave this blank to let MS_mvc decide
		'timezone' => 'UTC',    // this will be used for the PHP date and date-time function we will set it for you
		'baseLocation'=> '/htdocs/MSmvc'
	],

	'live' => ['error-logging' => 'MS_handler', // we can choice between the custom MS_handler, none
		'url' => 'http://google', // the default url you could leave this blank to let MS_mvc decide
		'timezone' => 'UTC',    // this will be used for the PHP date and date-time function we will set it for you
		'baseLocation'=> '/htdocs/MSmvc'
	]];