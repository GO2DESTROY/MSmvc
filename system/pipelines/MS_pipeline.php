<?php

namespace system\pipelines;

use blueprints\MS_mainInterface;
use system\MS_core;

class MS_pipeline
{
	public static $dataSets;
	public static $dataSetsLocation;
	public        $requestedDataSet;
	private       $requestTypeHandler;

	function __construct() {
		if(empty(self::$dataSetsLocation)) {
			$this->requestedDataSet             = 'datasets';
			self::$dataSetsLocation['datasets'] = $this->openPhpFile();
		}
	}

	public static function returnConfig($file) {
		$configData                   = new MS_pipeline();
		$configData->requestedDataSet = $file;
		return $configData->getRequestedData();
	}

	public function getRequestedData() {
		if(isset(self::$dataSetsLocation[$this->requestedDataSet])) {
			return self::$dataSetsLocation[$this->requestedDataSet];
		}
		else {
			$this->requestTypeHandler = self::$dataSetsLocation['datasets'][$this->requestedDataSet];
			return $this->connectToDataHandler();
		}
	}

	private function connectToDataHandler() {
		switch($this->requestTypeHandler) {
			case 'php':
				return $this->openPhpFile();
				break;
			case 'json':
				return $this->openJsonFile();
				break;
			default:
				return $this->openDataBaseFile();
		}
	}

	private function openPhpFile() {
		return include dirname($_SERVER["SCRIPT_FILENAME"]) . '/config/' . $this->requestedDataSet . '.php';
	}

	private function openJsonFile() {
		return json_decode(file_get_contents(dirname($_SERVER["SCRIPT_FILENAME"]) . '/config/' . $this->requestedDataSet . '.json'),true);
	}

	private function openDataBaseFile() {
		return 34; //no need to cache the dataConnecter since MS_database already does this
	}
// todo: make a pipeline sublayer to interacte with data providers
}