<?php

namespace system;
use system\pipelines\MS_pipeline;

class MS_view
{
	private $data;

	public function __get($name) {
		return $this->$name;
	}

	public function __set($name, $value) {
		$this->$name = $value;
	}

	/**
	 * @param $file : the view files to load
	 *
	 * @return mixed: the view files is returned and filled with provided data
	 */
	public function loadView($file) {
		$view = MS_pipeline::returnViewFilePath($file);
		if(is_array($this->__get('data'))) {
			extract($this->__get('data'), EXTR_SKIP);
		}
		include $view;
	}

	/**
	 * @param $array : the array or object to display
	 *todo: make this a function in the function helper class that way we don't have to use it from this class
	 * @return string: the array or object now in string
	 */
	public function dumpArray($array) {
		$string = '<ul>';
		if(is_array($array) || is_object($array)) {
			foreach($array as $key => $value) {
				if(is_array($value) || is_object($value) || is_resource($value)) {
					$value = $this->dumpArray($value);
					$string .= '<li><span class="highlight">' . $key . '</span> <small>' . gettype($value) . ' [' . count($value) . ']</small> ' . $value . ' </li>';
				}
				else {
					$string .= '<li><span class="highlight">' . $key . '</span> ' . $value . ' <small>' . gettype($value) . ' [' . strlen($value) . ']</small></li>';
				}
			}
		}
		else {
			$string .= '<li>' . $array . '</li>';
		}
		$string .= '</ul>';
		return $string;
	}

}