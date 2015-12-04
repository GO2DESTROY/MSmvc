<?php

namespace system;
use system\pipelines\MS_pipeline;

class MS_view
{
	public $viewCollection;
	public $viewDataCollection;
	private $data;
	private $bundleCollection;

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
		if(is_array($this->data)) {
			extract($this->data, EXTR_SKIP);
		}
		include $view;
	}
}