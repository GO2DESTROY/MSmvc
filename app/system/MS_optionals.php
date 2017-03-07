<?php

namespace App\system;

/**
 * Class MS_optionals
 * @package App\system
 */
class MS_optionals {

	/**
	 * @var array|int
	 */
	private $values;

	/**
	 * @var bool
	 */
	private $basic;

	/**
	 * MS_optionals constructor.
	 *
	 * @param array $values
	 * @param null  $minus
	 * @param bool  $basic
	 */
	function __construct(array $values, $minus = null, bool $basic = false) {
		var_dump($values);
		$this->setBasic($basic);
		if (!is_null($minus)) {
			if (is_array($minus)) {
				foreach ($minus as $removeValue) {
					if (($key = array_search($removeValue, $values)) !== false) {
						unset($values[$key]);
					}
				}
			} else {
				if (($key = array_search($minus, $values)) !== false) {
					unset($values[$key]);
				}
			}
		}
		if ($this->isBasic()) {
			$this->values = decbin(array_sum($values));
		} else {
			$this->values = $values;
		}
		var_dump($this->values);
	}

	/**
	 * @param $value
	 *
	 * @return bool
	 */
	public function checkExists($value) {
		if ($this->isBasic()) {
			return $this->values & $value;
		} else {
			return in_array($value, $this->values);
		}
	}


	/**
	 * @return bool
	 */
	public function isBasic() {
		return $this->basic;
	}

	/**
	 * @param bool $basic
	 */
	private function setBasic(bool $basic) {
		$this->basic = $basic;
	}
}