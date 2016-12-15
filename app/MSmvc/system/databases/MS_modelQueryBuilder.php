<?php
namespace MSmvc\system\databases;
use MSmvc\system\models\MS_model;

/**
 * Class MS_modelQueryBuilder: this class will build the queries based on the models
 * @package MSmvc\system\databases
 */
class  MS_modelQueryBuilder {
    private $model;
    private $query;

    function __construct(MS_model $model) {

    }

    public function select(string $select = '*') {
        if (is_string($select)) {
            $select = explode(',', $select);
            foreach ($select as $item){
                $this->selectFields[] = $item;
            }
        }
        else{
            //get fields and add them
        }
    }
}