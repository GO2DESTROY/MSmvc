<?php
namespace system\databases;

use system\pipelines\MS_pipeline;

class MS_db
{
	private static $connectedDbReferenceCollection;
	private static $dbConnectionSets;

	private static $pdoCollection;

	/**
	 * MS_db constructor.
	 *
	 * we will load the config settings and set the default settings
	 */
	function __construct(string $connectionName = NULL) {

		if(isset(self::$connectedDbReferenceCollection[$connectionName]))
		{
			//our refrence does exists
		}
			else{
			//we don't have a living connection so create one
				if(isset(self::$dbConnectionSets[$connectionName])){
					//we have a valid connection
					$this->connect($connectionName);
				}
				else{
					throw new \Exception('the requested connection does not exist');
				}
		}
		$connection = new MS_databaseConnection($connectionName);

	}

	public static function create(array $databaseSettings){
		if (!empty($databaseSettings['name']) && !empty($databaseSettings['settings'])) {
			self::$dbConnectionSets[$databaseSettings['name']] = $databaseSettings['settings'];
		}
		else {
			throw new \Exception('there is no name for this connection');
		}
	}


	/**
	 * todo::create the real connection 
	 * @param null $connectionSetReference : the connection reference to use for the connection otherwise we will use
	 *                                     the default set
	 *
	 * @return MS_db: we return the MS_db class
	 */
	public function connect($connectionSetReference = NULL) {
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