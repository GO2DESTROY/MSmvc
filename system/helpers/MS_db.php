<?php
namespace system\helpers;

use blueprints\MS_mainInterface;
use system\pipelines\MS_pipeline;

class MS_db
{
	private        $collectionSetReference;//the data connection set that we reference to by unique names
	private        $collectionSet;
	private static $configSet;
	private static $pdoCollection;

	//public  $connectionSet;
	function __construct() {
		$this->loadConfig();
		$this->defaultSetter();
	}

	/**
	 * we load the database config file
	 */
	private function loadConfig() {
		if(empty(self::$configSet)) {
			self::$configSet = MS_pipeline::returnConfig('database');    //we load this once
		}
	}

	/**
	 *    we set the default settings to use
	 */
	private function defaultSetter() {
		$this->collectionSetReference = self::$configSet['defaultConnectionSet'];
	}

	/**
	 *    we set the collection depending on the reference
	 */
	private function collectionSetter() {
		$this->collectionSet = self::$configSet['connectionSets'][$this->collectionSetReference];    //this line might give an error in some IDEs however it is not
	}

	/**
	 * we make a PDO connection we the given settings
	 */
	private function setUpConnection() {
		$connection                                         = $this->collectionSet['driver'] . ":dbname=" . $this->collectionSet['database'] . ";host=" . $this->collectionSet['host'] . ";port=" . $this->collectionSet['port'];
		self::$pdoCollection[$this->collectionSetReference] = new \PDO($connection, $this->collectionSet['username'], $this->collectionSet['password']);
	}

	/**
	 *    we check if we have already a connection if not we create it
	 */
	public function createConnection() {
		if(!isset(self::$pdoCollection[$this->collectionSetReference])) {
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
			$connection->collectionSetReference = $connectionSetReference;
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
			return self::$pdoCollection[$this->collectionSetReference]->query($query)->fetchAll();
		}
		else {
			$call = self::$pdoCollection[$this->collectionSetReference]->prepare($query);
			$call->execute($data);
			return $call->fetchAll(\PDO::FETCH_OBJ);
		}
	}
}