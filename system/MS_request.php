<?php

namespace system;
use system\pipelines\MS_pipeline;

class MS_request
{
	public $requestInterface     = 'HTTP';
	public $realRequestInterface = 'HTTP';
	public $requestMethod        = 'GET';

	private $response;

	function __construct() {
		MS_pipeline::includeFile('system'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'MS_functions');
		$this->response = new MS_response();
	}

	private function checkBlackList() {
		//todo: implement a blacklist-check/firewall
		return TRUE;
	}

	private function callController() {
	}

	public function response() {
		if($this->checkBlackList() === TRUE) {
			$this->callController();

		}
		else {
			//todo: send a 403 error
		}
	}
	//make a response overwrite to cancel the response
}