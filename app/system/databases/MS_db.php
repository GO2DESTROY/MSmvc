<?php
namespace App\system\databases;

/**
 * Class MS_db
 * @package system\databases
 */
class MS_db {

	private $connection;

	/**
	 * MS_db constructor.
	 * @param string|NULL $connectionName: connection to be used otherwise default will be used
	 * @throws \Exception: The default connection is not specified.
	 */
	function __construct(string $connectionName = NULL) {
		if(!empty($connectionName)) {
			$this->connection = MS_databaseConnection::getDatabaseConnectionsCollections($connectionName);
		}
		else if(!empty(MS_databaseResource::getDefaultConnectionName())) {
			$this->connection = MS_databaseConnection::getDatabaseConnectionsCollections(MS_databaseResource::getDefaultConnectionName());
		}
		else {
			throw new \Exception('The connection is not specified');
		}
	}

	/**
	 * @param      $query : the SQL query to be executed
	 * @param null $data  : the pdo data for prepare statement to be used
	 *
	 * @return mixed : we return the query results
	 */
	public function query($query, $data = NULL) {
			$statement = $this->connection->prepare($query);
			$statement->execute($data);
			return $statement->fetchAll(\PDO::FETCH_OBJ);
	}
}