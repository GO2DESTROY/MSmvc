<?php

namespace App\models;

use App\system\databases\Db;

class generateModel
{
	public static function getPrimaryKeys($databaseConnectionReference,$table) {
		return Db::connection($databaseConnectionReference)->query('SHOW KEYS FROM ' . $table . ' WHERE Key_name = "PRIMARY"');
	}


	public static function getTableColumns($databaseConnectionReference,$table) {
		return Db::connection($databaseConnectionReference)->query('SHOW COLUMNS FROM '.$table);
	}
}