<?php

namespace system;
class MS_response
{
	private static $responseCollection = [];
	private static $responseMaster     = [];
	private        $responseType       = 'view';//view|download|json

	/**
	 * we will send a view so we set the response type to view
	 */
	public function view() {
		$this->responseType = 'view';
	}

	/**
	 * we will send a file for the client to download so we set the response type to view
	 */
	public function download() {
		$this->responseType = 'download';
	}

	/**
	 * we will send a json encoded
	 */
	public function json() {
		$this->responseType = 'json';
	}

	/**
	 * we will overwrite the master view for our response
	 *
	 * @param $view : the view to use for overwriting
	 */
	public static function overwriteMasterView($view) {
		self::$responseMaster = $view;
	}

	public static function addViewToCollection($name, $view, $data = NULL) {
		if($name === NULL) {
			$name = 'default';
		}
		self::$responseCollection[$name] = ['view' => $view, 'data' => $data];
	}

	public function returnResponse() {
		switch($this->responseType) {
			case 'view':
				$this->viewResponse();
				break;
			case 'json':
				$this->jsonResponse();
				break;
			case 'download':
				$this->downloadResponse();
				break;
		}
	}

	private function viewResponse() {
	}

	private function jsonResponse() {
		header('Content-Type: application/json');
	}

	private function downloadResponse() {
	}
}