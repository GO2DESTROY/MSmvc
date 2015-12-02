<?php

namespace system;
class MS_response
{
	private static $responseCollection = [];
	private static $responseMaster     = [];
	private        $responseType       = 'view';//view|download|json

	private $header;

	//todo: make a style and script bundle for master view
	private function returnResponse() {
	}

	private function setMasterView() {

	}

	public function view() {
		$this->responseType = 'view';
	}

	public function download() {
		$this->responseType = 'download';
	}

	public function json() {
		$this->responseType = 'json';
	}

	public static function overwriteMasterView($view) {
		self::$responseMaster = $view;
	}

	public static function addViewToCollection($name, $view, $data = NULL) {
		if($name === NULL) {
			$name = 'default';
		}
		self::$responseCollection[$name] = ['view' => $view, 'data' => $data];
	}
}