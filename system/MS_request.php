<?php

namespace system;
class MS_request
{
	public $requestInterface     = 'HTTP';
	public $realRequestInterface = 'HTTP';
	public $requestMethod        = 'GET';

	private $response;

	function __construct() {
		$this->response = new MS_response();
	}

	private function checkBlackList() {
		//todo: implement a blacklist-check/firewall
		return TRUE;
	}

	private function setSessionSet() {
	}

	private function callController() {
	}

	public function response() {
		if($this->checkBlackList() === TRUE) {
			$this->callController();
		}
		else {

		}
	}
	//make a response overwrite to cancel the response
}