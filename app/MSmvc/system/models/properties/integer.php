<?php
namespace MSmvc\system\models\properties;

/**
 * Class integer
 * @package system\models\properties
 */
class integer extends MS_property {

    public $autoIncrement;
    public $type = 'int';
    public $length = 11;

    /**
     * @return bool
     * @throws \Exception
     */
    function validateProperty() {
        if (is_int(intval($this->value))) {
            return TRUE;
        } else {
            throw new \Exception('the property is invalid');
        }
    }
}