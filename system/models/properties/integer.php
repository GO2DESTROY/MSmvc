<?php
namespace system\models\properties;

/**
 * Class integer
 * @package system\models\properties
 */
class integer extends MS_property {
	/**
	 * @return bool
	 * @throws \Exception
	 */
	function validateProperty(){
		if (is_int(intval($this->value))) {
			return true;
		}
		else {
			throw new \Exception('the property is invalid');
		}
	}
}