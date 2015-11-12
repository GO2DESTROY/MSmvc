<?php
/**
 * Setting the datasets is easy with the use of MS_route
 * IMPORTANT don't change the database to a databaseConnectionSet driver. This is output an error.
 *
 * possible values are: php or a database connection name.
 * todo: add json and xml support
 */
 return [
	 'routes' => 'php',
	 'config' => 'php',
	 'database' => 'php', // only php / json is supported
	 'errors' => 'php',
	 'routes' => 'php'	//currently only php is supported
 ];