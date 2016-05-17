<?php

class varchar extends \system\models\properties\MS_property{

	/**
	 * @return bool
	 * @throws Exception
	 */
	function validateProperty(){
		if(is_string($this->value)){
			return true;
		}
		else{
			throw new Exception('the property is invalid');
		}
		// TODO: Implement validateProperty() method.
	}
}