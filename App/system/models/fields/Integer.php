<?php
namespace App\system\models\fields;

/**
 * Class Integer
 * @package system\models\fields
 */
class Integer extends Field {

    public $type = 'int';
    public $length = 11;

    /**
     * @return bool
     * @throws \Exception
     */
    public function validateProperty() {
        if (is_int($this->value)) {
            return TRUE;
        } else {
            throw new \Exception('the property is invalid');
        }
    }

	/**
	 * @param bool $autoIncrement
	 *
     * @return \App\system\models\fields\Field
     */
	public function setAutoIncrement(bool $autoIncrement = true) {
		parent::setAutoIncrement($autoIncrement);
		return $this;
	}

    /**
     * @param string $refrenceModel
     * @param string $refrenceProperty
     *
     * @return $this
     */
    public function setForeignKey($refrenceModel, $refrenceProperty) {
        parent::setForeignKey($refrenceModel, $refrenceProperty);
        return $this;
    }
}