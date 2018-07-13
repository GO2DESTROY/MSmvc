<?php
namespace App\system\databases;

/**
 * Class Db
 * @package system\databases
 */
class Db {

	private $connection;

	/**
	 * Db constructor.
	 * @param string|NULL $connectionName: connection to be used otherwise default will be used
	 * @throws \Exception: The default connection is not specified.
	 */
	function __construct(string $connectionName = NULL) {
		if(!empty($connectionName)) {
			$this->connection = DatabaseConnection::getDatabaseConnectionsCollections($connectionName);
		}
		else if(!empty(DatabaseResource::getDefaultConnectionName())) {
			$this->connection = DatabaseConnection::getDatabaseConnectionsCollections(DatabaseResource::getDefaultConnectionName());
		}
		else {
			throw new \Exception('The connection is not specified');
		}
	}

	/**
	 * @param string $query : the SQL query to be executed
	 * @param null   $data  : the pdo data for prepare statement to be used
	 *
	 * @return mixed : we return the query results
	 */
	public function query(string $query, $data = NULL) {
			$statement = $this->connection->prepare($query);
			$statement->execute($data);
			return $statement->fetchAll(\PDO::FETCH_OBJ);
	}
}
