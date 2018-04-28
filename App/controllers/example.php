<?php

namespace App\controllers;

use App\models\testModel;
use App\system\databases\MigrationBuilder;
use App\system\Controller;

/**
 * Class example
 * @package App\controllers
 */
class example extends Controller {

    public function index() {
        try {
            $test = new MigrationBuilder(new testModel());
        } catch (\ReflectionException $e) {
        }

        $test->execute();
    }
}
