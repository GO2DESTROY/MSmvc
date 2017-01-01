<?php

namespace system;

//use system\pipelines\MS_pipeline;
use system\pipelines\MS_pipeline;

/**
 * Class MS_handler: this class will handle all the exception and error's thrown
 * @package MSmvc\system
 */
class MS_handler
{

    public static $errorSettings;

    public static function setConfig() {
        self::$errorSettings = MS_pipeline::includeFile("config/errors")[MS_start::getEnvironment()];
    }

    /**
	 * this will handle the exceptions
	 *
	 * @param $exception : exception to handle
	 */
	public function exceptionHandler(\Exception $exception) {
	    //get the settings
        var_dump(self::$errorSettings);
		if(self::$errorSettings['logs']['log_exceptions']['log'] === TRUE) {
			$this->addToLog(self::$errorSettings['logs']['log_exceptions']['location'], [date("Y-m-d H:i:s"), $exception->getFile(), $exception->getLine(), $exception->getCode(), $exception->getMessage()]);
		}
		$data = ['message' => $exception->getMessage(), 'date' => date("Y-m-d H:i:s"), 'code' => $exception->getCode(), 'location' => $exception->getFile(), 'line' => $exception->getLine(), 'backtrace' => debug_backtrace()];
		view('system/exceptionDump', $data);
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
			case E_COMPILE_ERROR:
				$type = 'Compile error';
				break;
			case E_COMPILE_WARNING:
				$type = 'Compile warning';
				break;
			case E_PARSE:
				$type = 'Parse error';
				break;
			case E_CORE_ERROR:
				$type = 'Core error';
				break;
			case E_CORE_WARNING:
				$type = 'Core warning';
				break;
			default:
				$type = 'Unkown';
				break;
		}
		if(self::$errorSettings['logs']['log_errors']['log'] === TRUE && self::$errorSettings['logs']['log_errors']['location'] !== FALSE) {
			$this->addToLog(self::$errorSettings['logs']['log_errors']['location'], [date("Y-m-d H:i:s"), $errfile, $errline, $type, $errstr]);
		}

		$data             = ['type' => $type, 'message' => $errstr, 'date' => date("Y-m-d H:i:s"), 'location' => $errfile, 'line' => $errline, 'variables' => $errcontext, 'backtrace' => debug_backtrace()];
		view('system/errorDump', $data);
	}

	/**
	 * this method will handle the fatal errors and sends them to the errorHandler
	 */
	public function fatal_handler() {
		$error = error_get_last();
		if(($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)) {
			ob_clean(); // we cancel all the normal output and only serve the fatal error
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
     * todo: add this to the pipeline
	 * @param $file : the file to write to
	 * @param $line : the line to add to the log
	 */
	private function addToLog($file, $line) {
		$fp = fopen($this->root . $file, 'a');
		if(is_array($line)) {
			foreach($line as $singleWord) {
				fwrite($fp, $singleWord . ' ');
			}
			fwrite($fp, PHP_EOL);
		}
		else {
			fwrite($fp, $line . PHP_EOL);
		}
		fclose($fp);
	}
	//this is the main core of the MVC framework this is the parent of all the classes it contains the autoloader nad the error handler
}