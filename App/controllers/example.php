<?php

namespace App\controllers;

use App\models\testModel;
use App\system\databases\MigrationBuilder;
use App\system\Controller;

class example extends Controller {

    public function index() {
        $test = new MigrationBuilder(new testModel());

        $test->execute();
        echo 234;

        return view("example",["test"=>"tete"]);
        //  echo $test->getShortModelName();
    }
}
