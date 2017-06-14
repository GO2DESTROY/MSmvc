<?php


namespace App\system\databases\migrations\D2017_02_23_163832;

use App\system\databases\Migration;

/**
 * Class test
 * @package App\system\databases\migrations
 */
class test extends Migration{

    function up() {
        $this->createField(string("Field")->setLength(47));
    }
}