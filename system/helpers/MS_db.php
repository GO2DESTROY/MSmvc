<?php
namespace system\helpers;

use blueprints\MS_mainInterface;
use system\MS_core;

class MS_db extends MS_core implements MS_mainInterface
{
	private        $collectionSetReference;
	private        $collectionSet;
	private static $configSet;
	private static $pdoCollection;

	//public  $connectionSet;
	function __construct() {
		parent::__construct();
		$this->loadConfig();
		$this->defaultSetter();
	}

	/**
	 * we load the database config file
	 */
	private function loadConfig() {
		if(empty(self::$configSet)) {
			self::$configSet = include './config/database.php';    //we load this once
		}
	}

	/**
	 *    we set the default settings to use
	 */
	private function defaultSetter() {
		$this->__set('collectionSetReference', self::$configSet['defaultConnectionSet']);
	}

	/**
	 *    we set the collection depending on the reference
	 */
	private function collectionSetter() {
		$this->__set('collectionSet', self::$configSet[$this->__get('collectionSetReference')]);    //this line might give an error in some IDEs however it is not
	}

	/**
	 * we make a PDO connection we the given settings
	 */
	private function setUpConnection() {
		self::$pdoCollection[$this->__get('collectionSetReference')] = new PDO($this->__get('collectionSet')['driver'] . ":host=" . $this->__get('collectionSet')['host'] . ";port=" . $this->__get('collectionSet')['port'] . ";dbname=" . $this->__get('collectionSet')['database'] . ";user=" . $this->__get('collectionSet')['username'] . ";password=" . $this->__get('collectionSet')['password']);
	}

	/**
	 *    we check if we have already a connection if not we create it
	 */
	public function createConnection() {
		if(!isset(self::$pdoCollection[$this->__get('collectionSetReference')])) {
			//we have already ran once so the connection already exists
			//this collection set doesn't exists
			$this->collectionSetter();
			$this->setUpConnection();
		}

	}

	/**
	 * @param null $connectionSetReference : the connection reference to use for the connection otherwise we will use
	 *                                     the default set
	 *
	 * @return MS_db: we return the MS_db class
	 */
	public static function connection($connectionSetReference = NULL) {
		$connection = new MS_db();
		if(!is_null($connectionSetReference)) {
			$connection->__set('collectionSetReference', $connectionSetReference);
		}
		$connection->createConnection();
		return $connection;
	}

	/**
	 * @param      $query : the SQL query to be executed
	 * @param null $data  : the pdo data for prepare statement to be used
	 *
	 * @return mixed: we return the query results
	 */
	public function query($query, $data = NULL) {
		if(is_null($data)) {
			return self::$pdoCollection[$this->__get('collectionSetReference')]->query($query)->fetchAll();
		}
		else {
			$call = self::$pdoCollection[$this->__get('collectionSetReference')]->prepare($query);
			$call->execute($data);
			return $call->fetchAll(PDO::FETCH_OBJ);
		}
	}

	public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return $this->$name;
	}
}