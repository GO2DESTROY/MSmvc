<?php

namespace models;

use system\helpers\MS_db;

class generateModel
{
	public static function getPrimaryKeys($table) {
		return MS_db::connection($_REQUEST['databaseConnectionReference'])->query('SHOW KEYS FROM ' . $table . ' WHERE Key_name = "PRIMARY"');
	}

	public static function getTableColumns($table) {
		return MS_db::connection($_REQUEST['databaseConnectionReference'])->query('SHOW COLUMNS FROM '.$table);
	}
}