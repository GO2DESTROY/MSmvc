<?php

// Here we define the database settings
use App\system\databases\DatabaseResource;
DatabaseResource::setDefaultConnectionName('development');
DatabaseResource::create(['name' => 'development', 'settings' => ['host' => '127.0.0.1', 'driver' => 'mysql', 'port' => 3306, 'database' => 'test', 'username' => 'root', 'password' => '']]);