<?php

class MS_controller extends \system\MS_core implements blueprints\MS_mainInterface
{
	private $requestController;
	private $controllerName;

	public function __set($name, $value) {
		$this->$name = $value;
	}

	public function __get($name) {
		return $this->$name;
	}

	/**
	 * @param      $file : the view file to load
	 * @param null $data : the view data to use
	 *
	 * @return mixed    : the view page to return
	 */
	public function view($file, $data = NULL) {
		$view = new MS_view();
		$view->__set('data', $data);
		return $view->loadView($file);
	}
}
// this method will load the controller and execute it
// besides that it will make sure a helper can get called it will request it from the core which will call spl