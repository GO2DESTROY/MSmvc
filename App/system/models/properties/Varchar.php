<?php
namespace App\system\models\properties;

/**
 * Class Varchar: default Varchar property
 * @package system\models\properties
 */
class Varchar extends Property{

	/**
	 * @return bool
	 * @throws \Exception
	 */

	function validateProperty(){
		if(is_string(strval($this->value))){
			return true;
		}
		else{
			throw new \Exception('the property is invalid');
		}
	}
}