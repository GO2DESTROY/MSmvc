<?php

namespace MSmvc\models;

use MSmvc\system\databases\MS_db;

class generateModel
{
	public static function getPrimaryKeys($databaseConnectionReference,$table) {
		return MS_db::connection($databaseConnectionReference)->query('SHOW KEYS FROM ' . $table . ' WHERE Key_name = "PRIMARY"');
	}


	public static function getTableColumns($databaseConnectionReference,$table) {
		return MS_db::connection($databaseConnectionReference)->query('SHOW COLUMNS FROM '.$table);
	}
}