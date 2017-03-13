<?php

namespace App\controllers;

use App\system\MS_controller;
use App\system\MS_filesystem;

class example extends MS_controller {

    public function index() {

        $testDir = new MS_filesystem("app/resources");
//$testDir->setMaxDepth(3);
  //      $testDir->show();

        var_dump($testDir->getCurrentfilter());
        echo 123;
        //  echo $test->getShortModelName();
    }
}