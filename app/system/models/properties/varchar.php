<?php
namespace system\models\properties;

/**
 * Class varchar: default varchar property
 * @package system\models\properties
 */
class varchar extends MS_property {

    /**
     * @throws \Exception
     */

    public function validateProperty() {
        if (!is_string($this->value)) {
            throw new \Exception('The property is invalid');
        }
    }
}