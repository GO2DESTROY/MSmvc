<?php

namespace system;
class MS_response
{
	private $responseCollection = [];
	private $responseMaster     = [];
	private $responseType       = 'view';//view|download|json

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
}