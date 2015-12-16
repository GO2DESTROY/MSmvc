<?php

namespace system;
class MS_response
{
	private static $responseCollection = [];
	private static $responseMaster;
	private static $data;
	private static $responseType;//view|download|json

	/**
	 * we will send a view so we set the response type to view
	 */
	public static function view() {
		self::$responseType = 'view';
	}

	/**
	 * we will send a file for the client to download so we set the response type to view
	 *
	 * @param $file : the file to download
	 */
	public static function download($file) {
		self::$responseType = 'download';
		self::$data         = $file;
	}

	/**
	 * we will send a json encoded
	 */
	public static function json() {
		self::$responseType = 'json';
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

	/**
	 * we return the specified response or we won't return anything
	 */
	public function returnResponse() {
		switch(self::$responseType) {
			case 'view':
				$this->viewResponse();
				break;
			case 'json':
				$this->jsonResponse();
				break;
			case 'download':
				$this->downloadResponse();
				break;
			default:
				break;
		}
	}

	private function viewResponse() {
		header('Content-Type: text/html; charset=utf-8');
	}

	private function jsonResponse() {
		header('Content-Type: application/json');
	}

	private function downloadResponse() {
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.self::$data.'"');
	}
}