<?php
namespace App\system\databases;
/**
 * Class DatabaseConnectionResource
 * @package MSmvc\system\databases
 */
class  DatabaseConnectionResource {
    private $name;
    private $host;
    private $database;
    private $driver;
    private $port;
    private $username;
    private $password;

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost(string $host) {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getDatabase() {
        return $this->database;
    }

    /**
     * @param mixed $database
     */
    public function setDatabase(string $database) {
        $this->database = $database;
    }

    /**
     * @return mixed
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port) {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getDriver() {
        return $this->driver;
    }

    /**
     * @param string $driver
     */
    public function setDriver(string $driver) {
        $this->driver = $driver;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username) {
        $this->username = $username;
    }

}