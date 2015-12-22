<?php

namespace system;
class MS_response
{
	private static $responseCollection = [];
	private static $responseMaster;
	private static $data;
	private static $responseType;//view|download|json
	private static $headers            = [];

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
	 * we will send a json encoded response
	 */
	public static function json() {
		self::$responseType = 'json';
	}

	/**
	 * we will overwrite the master view for our response
	 *
	 * @param      $view : the view to use for overwriting
	 * @param null $data : optional data for the master to use
	 */
	public static function overwriteMasterView($view, $data = NULL) {
		self::$responseMaster = ['view' => $view, 'data' => $data];
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
				$this->returnViewResponseBody();
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

	private function setHeader($defaultHeaders = []) {
		if(self::$headers == NULL) {
			foreach($defaultHeaders as $header) {
				header($header);
			}
		}
		else {
			foreach(self::$headers as $header) {
				header($header);
			}
		}
	}

	private function downloadResponse() {
		$this->setHeader(['Content-Type: application/octet-stream', 'Content-Disposition: attachment; filename="' . self::$data . '"']);
	}

	private function viewResponse() {
		$this->setHeader(['Content-Type: text/html; charset=utf-8']);
	}

	private function jsonResponse() {
		$this->setHeader(['Content-Type: application/json']);
	}

	private function returnViewResponseBody() {
		$reponse = new MS_view();
		if(self::$responseMaster !== NULL) {
			// call the master
			$reponse->masterFile     = self::$responseMaster;
			$reponse->viewCollection = self::$responseCollection;
		}
		else {
			dd('singleCall');
			//call single reponse
		}
	}
}