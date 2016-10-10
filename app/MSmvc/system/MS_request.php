<?php

namespace MSmvc\system;

use system\pipelines\MS_pipeline;

/**
 * Class MS_request
 * @package MSmvc\system
 */
class MS_request
{
	public $requestInterface            = 'HTTP';
	public $requestInterfaceInformation = NULL;
	public $realRequestInterface        = 'HTTP';
	public $requestMethod               = 'GET';

	public $requestRoute;
	public $requestVariables = [];

	private $response;

	/**
	 * we open the MS_functions that way we always will be able to use the functions
	 * we will prepare the response when the request is started that way we can change it at any point
	 */
	function __construct() {
		MS_pipeline::includeFile('system' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'MS_functions');
		$this->response = new MS_response();
	}

	/**
	 * we check if the request passes our blacklist check simply said we check if his IP or his country is not
	 * blacklisted
	 *
	 * @return bool : this depends if our check passes
	 */
	private function checkBlackList() {
		//todo: implement a blacklist-check
		return TRUE;
	}

	/**
	 * This method will start the controller and execute it it's a void method so we don't expect any return values
	 *
	 * The controller should use functions provided by MS_functions to send data to the response object
	 */
	private function callController() {
		$controllerRequest = explode('@', $this->requestRoute['action']['uses']);
		$controllerString  = DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controllerRequest[0];
		$controller        = new $controllerString;

		call_user_func_array([$controller, $controllerRequest[1]], $this->requestVariables);
	}

	/**
	 * We will do a simple blacklist check and then execute the controller and return our response
	 */
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