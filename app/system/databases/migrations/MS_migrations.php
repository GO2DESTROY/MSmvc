<?php
/**
 * Created by PhpStorm.
 * User: Maurice
 * Date: 13-Jan-17
 * Time: 12:53
 */

namespace App\system\databases\migrations;

use App\system\models\MS_model;


/**
 * Class MS_migrations
 * @package App\system\databases\migrations
 */
class MS_migrations {
    private static $migrationModels = [];
    private $migrations;
private $output;
    /**
     * @param $model
     */
    public static function addMigrationModel(MS_model $model) {
        self::$migrationModels[] = $model;
    }

    /**
     * MS_migrations constructor.
     */
    function __construct() {
        if (empty(self::$migrationModels)) {
            throw new \Exception("No migrations pending");
        }
        else{
            usort(self::$migrationModels,[$this,"orderMigrations"]);
            var_dump($this->output);
        }
    }

    /**
     * @param $a
     * @param $b
     *
     * @internal param array $migrationModels
     * @return int
     */
    private function orderMigrations($a,$b){
        $this->output[] = ["a"=>$a,"b"=>$b];
        return 0;
    }
}