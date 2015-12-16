<?php

namespace system;

use system\pipelines\MS_pipeline;

class MS_request
{
	public $requestInterface     = 'HTTP';
	public $realRequestInterface = 'HTTP';
	public $requestMethod        = 'GET';

	public $requestRoute;
	public $requestVariables;

	private $response;

	function __construct() {
		MS_pipeline::includeFile('system' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'MS_functions');
		$this->response = new MS_response();
	}

	private function checkBlackList() {
		//todo: implement a blacklist-check/firewall
		return TRUE;
	}

	private function callController() {
		$controllerRequest = explode('@', $this->requestRoute['action']['uses']);
		$controllerString  = DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controllerRequest[0];
		$controller        = new $controllerString;
		if($this->requestVariables != NULL) {
			call_user_func_array([$controller, $controllerRequest[1]], $this->requestVariables);
		}
		else {
			$controller->$controllerRequest[1]();
		}
	}

	public function request() {
		if($this->checkBlackList() === TRUE) {
			$this->callController();
			$this->response->returnResponse();
		}
		else {
			dd('blacklist error');
			//todo: send a 403 error
		}
	}
	//make a response overwrite to cancel the response
}