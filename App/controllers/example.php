<?php

namespace App\controllers;

use App\models\testModel;
use App\system\databases\MigrationBuilder;
use App\system\Controller;

class example extends Controller {

    public function index() {
        $test = new MigrationBuilder(new testModel());

        $test->execute();
        //  echo $test->getShortModelName();
    }
}