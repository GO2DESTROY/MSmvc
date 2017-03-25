<?php

namespace App\controllers;

use App\system\databases\MS_migrationHandler;
use App\system\MS_controller;

/**
 * Class generate: this is the base generate controller it will handle the generate requests
 * @package MSmvc\controllers
 */
class generate extends MS_controller {

    public function index() {

        foreach(glob(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR . '*.php') as $filename) {
           MS_migrationHandler::addMigrationModel($filename);
        }
        return view("system/generateForm");
    }
}