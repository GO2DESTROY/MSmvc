<?php

namespace App\controllers;

use App\system\databases\migrations\MS_staticMigrationContainer;
use App\system\MS_controller;

/**
 * Class generate: this is the base generate controller it will handle the generate requests
 * @package MSmvc\controllers
 */
class generate extends MS_controller {

    public function index() {

        foreach(glob(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR."models".DIRECTORY_SEPARATOR . '*.php') as $filename) {
           MS_staticMigrationContainer::addMigrationModelFiles($filename);
        }
        return view("system/generateForm");
    }
}