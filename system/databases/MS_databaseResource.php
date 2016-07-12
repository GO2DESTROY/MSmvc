<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 25-5-2016
 * Time: 11:19
 */

namespace system\databases;

class MS_databaseResource {
	private static $dataBaseResourceSet = [];
	private static $defaultConnection;

	/**
	 * @return string: returns the name of the default connection
	 */
	public static function getDefaultConnection(){
		return self::$defaultConnection;
	}

	/**
	 * @param string $defaultConnection : this will set the defaultConnection to use
	 */
	public static function setDefaultConnection(string $defaultConnection){
		self::$defaultConnection = $defaultConnection;
	}

	/**
	 * @return array: return all the database resources indexed by the autoincrement.
	 */
	public static function getDataBaseResourceSet(){
		return self::$dataBaseResourceSet;
	}

	//this will be used for the database resource same like the route
	public static function create(array $settings){
		if (!empty($settings['name']) && !empty($settings['settings'])) {
			$dataBaseResourceSet[$settings['name']] = $settings['settings'];
		}
		else {
			throw new \Exception('there is no name for this connection');
		}
	}
}