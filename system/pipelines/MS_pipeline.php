<?php

namespace system\pipelines;

class MS_pipeline
{
	public static $dataSets;
	public static $dataSetsLocation;
	public        $requestedDataSet;
	protected     $requestTypeHandler;
	public static $configCollections;
	public static $fileCollections;
	public static $root;


	function __construct() {
		if(empty(self::$dataSetsLocation)) {
			$this->requestedDataSet             = 'datasets';
			self::$dataSetsLocation['datasets'] = $this->openPhpFile();
		}
	}

	public static function returnConfig($file, $force = FALSE) {
		if($force === TRUE || !isset(self::$configCollections[$file])) {
			$configData                     = new MS_pipeline();
			$configData->requestedDataSet   = $file;
			self::$configCollections[$file] = $configData->getRequestedData();
		}
		return self::$configCollections[$file];
	}

	public static function includeFile($file, $force = FALSE) {
		if($force === TRUE || !isset(self::$fileCollections[$file])) {
			self::$fileCollections[$file] = include self::$root.$file.'.php';
		}
		return self::$fileCollections[$file];
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
	public static function executeAndReturnFileContent($file, array $data = null){
		if(is_array($data)) {
			extract($data, EXTR_SKIP);
		}
		ob_start();
		include $file;
		return ob_get_clean();
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

	public static function returnViewFilePath($file) {
		return self::$root . 'resources/views/' . $file . '.php';
	}

	private function openPhpFile() {
		return include self::$root . '/config/' . $this->requestedDataSet . '.php';
	}

	private function openJsonFile() {
		return json_decode(file_get_contents(self::$root . 'config' . DIRECTORY_SEPARATOR . $this->requestedDataSet . '.json'), TRUE);
	}

	private function openDataBaseFile() {
		return 42; //no need to cache the dataConnecter since MS_database already does this
	}
	public static function returnPhpFileContent($location){
		return file_get_contents(self::$root.$location.'.php');
	}
	public static function returnFilesAndDirectories($location){
		return array_diff(scandir(self::$root.$location), array('..', '.'));
	}
// todo: make a pipeline sublayer to interacte with data providers
// todo: database config files support
	//todo: improve the include location
	//todo: add folder inclusion
}