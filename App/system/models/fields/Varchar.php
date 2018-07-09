<?php
namespace App\system\models\fields;

/**
 * Class Varchar: default Varchar property
 * @package system\models\fields
 */
class Varchar extends Field{

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