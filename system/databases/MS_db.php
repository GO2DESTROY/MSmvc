<?php
namespace system\databases;

use system\pipelines\MS_pipeline;

class MS_db
{
	private        $collectionSetReference;//the data connection set that we reference to by unique names
	private        $collectionSet;
	private static $configSet;
	private static $pdoCollection;

	/**
	 * MS_db constructor.
	 *
	 * we will load the config settings and set the default settings
	 */
	function __construct(string $connectionName = NULL) {

			$connection = new MS_databaseConnection($connectionName);

		$this->loadConfig();
		$this->defaultSetter();
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
		$connection = new MS_databaseConnection();
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