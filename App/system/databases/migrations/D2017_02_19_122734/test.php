<?php

namespace App\system\databases\migrations\D2017_02_19_122734;

use App\system\databases\MS_migration;

/**
 * Class test
 * @package App\system\databases\migrations
 */
class test extends MS_migration{

    function up() {
        $this->createField(string("test"));
        // TODO: Implement up() method.
    }
}