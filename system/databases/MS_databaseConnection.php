<?php

namespace system\databases;

use system\pipelines\MS_pipeline;

class MS_databaseConnection {
	private static $databaseConnectionsCollection = [];
	private static $databaseConnectionsDetails = [];

	/**
	 * MS_databaseConnection constructor.
	 * @param string $resource: the resource name to in
	 * @param bool   $forceNewConnection: used to force a new connection
	 */
	public function __construct(string $resource, bool $forceNewConnection = false){
		if (!empty(self::$databaseConnectionsCollection[$resource]) && $forceNewConnection === false) {
			return self::$databaseConnectionsCollection[$resource];
		}
		else {
			if(empty(self::$databaseConnectionsDetails)) {
				MS_pipeline::returnConfig('database');
				self::$databaseConnectionsDetails = MS_databaseResource::getDataBaseResourceSet();
			}
			//pick the right resource 
			self::$databaseConnectionsCollection[$resource] = $this->setUpConnection();
		}

	}
	/**
	 * we make a PDO connection we the given settings
	 */
	private function setUpConnection() {
		$connection                                         = $this->collectionSet['driver'] . ":dbname=" . $this->collectionSet['database'] . ";host=" . $this->collectionSet['host'] . ";port=" . $this->collectionSet['port'];
		self::$pdoCollection[$this->collectionSetReference] = new \PDO($connection, $this->collectionSet['username'], $this->collectionSet['password']);
	}
}