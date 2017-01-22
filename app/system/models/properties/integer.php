<?php
namespace App\system\models\properties;

/**
 * Class integer
 * @package system\models\properties
 */
class integer extends MS_property {

    public $type = 'int';
    public $length = 11;

    /**
     * @return bool
     * @throws \Exception
     */
    public function validateProperty() {
        if (is_int(intval($this->value))) {
            return TRUE;
        } else {
            throw new \Exception('the property is invalid');
        }
    }

	/**
	 * @param bool $autoIncrement
	 *
     * @return \App\system\models\properties\MS_property
     */
	public function setAutoIncrement(bool $autoIncrement = true) {
		parent::setAutoIncrement($autoIncrement);
		return $this;
	}
}