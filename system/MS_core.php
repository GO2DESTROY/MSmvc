<?php
/*
 * this is the core of the system currently it contains the spl autoloader to make sure it works everywhere
 * same goes for the error and exception handler
*/
namespace system;

use system\pipelines\MS_pipeline;
use system\MS_view;
class MS_core
{
	protected $environment;
	private   $errorSettings;

	function __construct() {
		$paths = ['./system', './controllers', './models'];
		set_include_path(implode(PATH_SEPARATOR, $paths));
		spl_autoload_register('spl_autoload');
		$this->loadConfig();
	}

	/**
	 * we load the config files so we know how to handle error's and exceptions
	 */
	private function loadConfig() {
		$configFile        = MS_pipeline::returnConfig('config');
		$this->environment = $configFile['environment'];
		if($configFile[$this->environment]['error-logging'] == 'MS_handler') {
			$errorFile           = MS_pipeline::returnConfig('errors');
			$this->errorSettings = $errorFile[$this->environment];
			set_exception_handler([$this, 'exceptionHandler']);
			set_error_handler([$this, 'errorHandler']);
			register_shutdown_function([$this, 'fatal_handler']);
		}
	}

	/**
	 * this will handle the exceptions
	 * @param $exception: exception to handle
	 */
	public function exceptionHandler($exception) {
		if($this->errorSettings['logs']['log_exceptions']['log'] === TRUE) {
			$this->addToLog($this->errorSettings['logs']['log_exceptions']['location'], [date("Y-m-d H:i:s"), $exception->getFile(), $exception->getLine(), $exception->getCode(), $exception->getMessage()]);
		}
		$view = new MS_view;
		$data = ['message' => $exception->getMessage(), 'date' => date("Y-m-d H:i:s"), 'code' => $exception->getCode(), 'location' => $exception->getFile(), 'line' => $exception->getLine(), 'backtrace' => debug_backtrace()];
		$view->__set('data', $data);
		$view->loadView('system/exceptionDump');
	}

	/**
	 * this method will handle the errors
	 *
	 * @param      $errno      : error number
	 * @param      $errstr     : error message
	 * @param      $errfile    : error file
	 * @param      $errline    : error line
	 * @param null $errcontext : local variables
	 */
	public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext = NULL) {
		switch($errno) {
			case E_USER_ERROR:
				$type = 'Error';
				break;
			case E_USER_WARNING:
				$type = 'Warning';
				break;
			case E_USER_NOTICE:
				$type = 'Notice';
				break;
			default:
				$type = 'Unkown';
				break;
		}
		if($this->errorSettings['logs']['log_errors']['log'] === TRUE && $this->errorSettings['logs']['log_errors']['location'] !== FALSE) {
			$this->addToLog($this->errorSettings['logs']['log_errors']['location'], [date("Y-m-d H:i:s"), $errfile, $errline, $type, $errstr]);
		}

		$data = ['type' => $type, 'message' => $errstr, 'date' => date("Y-m-d H:i:s"), 'location' => $errfile, 'line' => $errline, 'variables' => $errcontext, 'backtrace' => debug_backtrace()];
		$view = new MS_view;
		$view->__set('data', $data);
		$view->loadView('system/errorDump');
	}

	/**
	 * this method will handle the fatal errors and sends them to the errorHandler
	 */
	public function fatal_handler() {
		$error = error_get_last();
		if(($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)) {
			ob_clean(); // we cancel all the normal output
			if($error !== NULL) {
				$errno   = $error["type"];
				$errstr  = $error["message"];
				$errfile = $error["file"];
				$errline = $error["line"];
			}
			else {
				$errfile = "unknown file";
				$errstr  = "shutdown";
				$errno   = E_CORE_ERROR;
				$errline = 0;
			}
			$this->errorHandler($errno, $errstr, $errfile, $errline);
			exit; // we got a fatal error since these shouldn't be ignored we exit
		}
	}

	/**
	 * @param $file : the file to write to
	 * @param $line : the line to add to the log
	 */
	private function addToLog($file, $line) {
		$fp = fopen(dirname($_SERVER["SCRIPT_FILENAME"]) . $file, 'a');
		if(is_array($line)) {
			foreach($line as $singleWord) {
				fwrite($fp, $singleWord . ' ');
			}
			fwrite($fp, PHP_EOL);
		}
		else {
			fwrite($fp, $line);
			fwrite($fp, PHP_EOL);
		}
		fclose($fp);
	}
	//this is the main core of the MVC framework this is the parent of all the classes it contains the autoloader nad the error handler
}