<?php

namespace App\controllers;

use App\system\databases\MigrationHandler;
use App\system\Controller;

/**
 * Class generate: this is the base generate controller it will handle the generate requests
 * @package MSmvc\controllers
 */
class generate extends Controller {

    public function index() {

        foreach(glob(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR . '*.php') as $filename) {
           MigrationHandler::addMigrationModel($filename);
        }
        return view("system/generateForm");
    }
}