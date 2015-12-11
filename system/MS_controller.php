<?php

namespace system;
class MS_controller
{
	public $controller;
	/**
	 * @param      $file : the view file to load
	 * @param null $data : the view data to use
	 *
	 * @return mixed    : the view page to return
	 */
	protected function view($file, $data = NULL) {
		$view = new MS_view();
		$view->__set('data', $data);
		return $view->loadView($file);
	}

	/**
	 * @param $data : the data to be converted to a json string
	 *
	 * @return string : a json string
	 */
	protected function json($data) {
		header('Content-Type: application/json');
		return json_encode($data);
	}

	/**
	 * @param $requestController: the controller class to use
	 * @param $requestMethod: the controller method to call from this class
	 */
	protected function controller($requestController, $requestMethod)
	{
		$this->controller = new $requestController;
		$this->controller->$requestMethod();
	}
}