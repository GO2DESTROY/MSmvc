<?php

namespace App\system\databases;

/**
 * Class MS_databaseConnection
 * @package MSmvc\system\databases
 */
class MS_databaseConnection {
    private static $databaseConnectionsCollections = [];
    private static $databaseConnectionsDetails = [];

    /**
     * MS_databaseConnection constructor.
     *
     * @param string $resource           : the resource name that we use from the resources
     * @param bool   $forceNewConnection : used to force a new connection
     *
     *                We load the database config file if we don't have the resource
     */
    public function __construct(string $resource, bool $forceNewConnection = FALSE) {
        if (!empty(self::$databaseConnectionsCollections[$resource]) && $forceNewConnection === FALSE) {
            self::$databaseConnectionsCollections[$resource];
        } else {
            self::$databaseConnectionsDetails = MS_databaseResource::getDataBaseResourceSet();
            self::$databaseConnectionsCollections[$resource] = $this->setUpConnection($resource);
        }

    }

    /**
     * @param $connectionName : the $connection name to return
     *
     * @return array
     */
    public static function getDatabaseConnectionsCollections($connectionName) {
        if (empty(self::$databaseConnectionsCollections[$connectionName])) {
            new MS_databaseConnection($connectionName);
        }
        return self::$databaseConnectionsCollections[$connectionName];
    }

    /**
     * we make a PDO connection we the given settings
     *
     * @param string $resource : this is the resource name that will be used to pick and name the resource for the
     *                         connection
     *
     * @return \PDO
     */
    private function setUpConnection(string $resource) {
        $connection = self::$databaseConnectionsDetails[$resource]['driver'] . ":dbname=" . self::$databaseConnectionsDetails[$resource]['database'] . ";host=" . self::$databaseConnectionsDetails[$resource]['host'] . ";port=" . self::$databaseConnectionsDetails[$resource]['port'];
        return new \PDO($connection, self::$databaseConnectionsDetails[$resource]['username'], self::$databaseConnectionsDetails[$resource]['password']);
    }
}