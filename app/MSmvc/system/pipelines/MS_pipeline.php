<?php

namespace system\pipelines;

/**
 * Class MS_pipeline
 * @package system\pipelines
 */
class MS_pipeline {
	public static $dataSets;
	public static $dataSetsLocation;
	public $requestedDataSet;
	protected $requestTypeHandler;
	public static $configCollections;
	public static $fileCollections;
	public static $root;


	/**
	 * MS_pipeline constructor.
	 */
	function __construct(){
		if (empty(self::$dataSetsLocation)) {
			$this->requestedDataSet = 'datasets';
			self::$dataSetsLocation['datasets'] = $this->openPhpFile();
		}
	}

	/**
	 * @param      $file: the file u wish to get the content of
	 * @param bool $force: if you wish to force to reopen the file defaults to false
	 * @return mixed: the file content will be returned
	 */
	public static function getConfigFileContent($file, $force = false){
		if ($force === true || !isset(self::$configCollections[$file])) {
			$configData = new MS_pipeline();
			$configData->requestedDataSet = $file;
			self::$configCollections[$file] = $configData->getRequestedData();
		}
		return self::$configCollections[$file];
	}

	/**
	 * @param      $file
	 * @param bool $force
	 * @return mixed
	 */
	public static function includeFile($file, $force = false){
		if ($force === true || !isset(self::$fileCollections[$file])) {
			self::$fileCollections[$file] = include self::$root . $file . '.php';
		}
		return self::$fileCollections[$file];
	}

	/**
	 * @return int|mixed
	 */
	public function getRequestedData(){
		if (isset(self::$dataSetsLocation[$this->requestedDataSet])) {
			return self::$dataSetsLocation[$this->requestedDataSet];

		}
		else {
			$this->requestTypeHandler = self::$dataSetsLocation['datasets'][$this->requestedDataSet];
			return $this->connectToDataHandler();
		}
	}

	/**
	 * @param            $file
	 * @param array|NULL $data
	 * @return string
	 */
	public static function executeAndReturnFileContent($file, array $data = NULL){
		if (is_array($data)) {
			extract($data, EXTR_SKIP);
		}
		ob_start();
		include $file;
		return ob_get_clean();
	}

	/**
	 * @param $directory
	 * @return array
	 */
	public static function getClassesWithinDirectory($directory){
		$dir = new \DirectoryIterator($directory);
		$filesWithinDirectory = [];
		$classesWithinDirectory = [];
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot() && $fileinfo->getFilename() !== '.gitkeep') {
				$filesWithinDirectory[] = $directory . DIRECTORY_SEPARATOR . $fileinfo->getFilename();
			}
		}
		foreach ($filesWithinDirectory as $file) {
			$classesWithinDirectory[$file] = self::getClassesWithinFile($file);
		}
		return $classesWithinDirectory;
	}

	/**
	 * @param $file
	 * @return array
	 */
	public static function getClassesWithinFile($file){
		$php_code = file_get_contents($file);
		$classes = [];
		$tokens = token_get_all($php_code);
		$count = count($tokens);
		for ($i = 2; $i < $count; $i++) {
			if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {

				$class_name = $tokens[$i][1];
				$classes[] = $class_name;
			}
		}
		return $classes;
	}

	/**
	 * @return int|mixed
	 * @throws \Exception
	 */
	private function connectToDataHandler(){
		switch ($this->requestTypeHandler) {
			case 'php':
				return $this->openPhpFile();
				break;
			case 'json':
				return $this->openJsonFile();
				break;
			default:
				throw new \Exception("The datahandler isn't specified");
		}
	}

	/**
	 * @return mixed
	 */
	private function openPhpFile(){
		return include self::$root . '/config/' . $this->requestedDataSet . '.php';
	}

	/**
	 * @return mixed
	 */
	private function openJsonFile(){
		return json_decode(file_get_contents(self::$root . 'config' . DIRECTORY_SEPARATOR . $this->requestedDataSet . '.json'), true);
	}
}
// todo: make a pipeline sublayer to interacte with data providers
// todo: database config files support
/*
	//todo: improve the include location
	//todo: add folder inclusion
=======
// todo: add documentation
*/