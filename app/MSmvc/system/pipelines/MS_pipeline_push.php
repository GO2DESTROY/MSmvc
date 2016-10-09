<?php

namespace system\pipelines;

class MS_pipeline_push extends MS_pipeline
{
	private $dataSet;

	public function addToConfig($requestFile, $content, $replace = FALSE) {
		$this->requestedDataSet = $requestFile;

		$this->requestTypeHandler = self::$dataSetsLocation['datasets'][$this->requestedDataSet];
		$this->connectToDataHandler();

		$this->pushToDataSet($content);
		self::$configCollections[$requestFile] = $this->getRequestedData();
	}
	public function closePushStream() {
		fclose($this->dataSet);
	}

	private function connectToDataHandler() {
		switch($this->requestTypeHandler) {
			case 'php':
				$this->dataSet = $this->openPhpFile();
				break;
			case 'json':
				$this->dataSet = $this->openJsonFile();
				break;
			default:
				$this->dataSet = $this->openDataBaseFile();
		}
	}

	private function pushToDataSet($input) {
		switch($this->requestTypeHandler) {
			case 'php':
				$this->pushToPhpFile($input);
				break;
			case 'json':
				break;
			default:
				break;
		}
	}

	private function pushToPhpFile($input) {
		fwrite($this->dataSet,$input);
	}

	private function openPhpFile() {
		return fopen(self::$root . '/config/' . $this->requestedDataSet . '.php', 'a');
	}

	private function openJsonFile() {
		return json_decode(fopen(self::$root . '/config/' . $this->requestedDataSet . '.json','r+'), TRUE);
	}

	private function openDataBaseFile() {
		return 42; //no need to cache the dataConnecter since MS_database already does this
	}
}