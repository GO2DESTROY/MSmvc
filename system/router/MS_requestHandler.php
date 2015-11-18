<?php
namespace system\router;

class MS_requestHandler
{
	public $currentRequestMethod = NULL;//if you wish to setup a custom request then set this value


	/**
	 * @return string sets the currentRequestMethod property with the http request method
	 * @throws \Exception
	 */
	public function setRequestMethod() {
		if($this->currentRequestMethod == NULL) {
			if(php_sapi_name() == 'cli') {
				$this->currentRequestMethod = 'CLI';
			}
			else {
				$method = $_SERVER['REQUEST_METHOD'];
				switch($method) {
					case 'PUT':
						$this->currentRequestMethod = 'PUT';
						break;
					case 'POST':
						$this->currentRequestMethod = 'POST';
						break;
					case 'GET':
						$this->currentRequestMethod = 'GET';
						break;
					case 'HEAD':
						$this->currentRequestMethod = 'HEAD';
						break;
					case 'DELETE':
						$this->currentRequestMethod = 'DELETE';
						break;
					case 'OPTIONS':
						$this->currentRequestMethod = 'OPTIONS';
						break;
					default:
						throw new \Exception('The supplied request method is not supported you have used ' . $method);
						break;
				}
			}
		}
	}
	/**
	 * @return string we return the current http URI
	 */
	private function grabUri() {
		$request_path = explode('?', $_SERVER['REQUEST_URI']);    //root of the URI
		$request_root = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');    //The url
		$uri          = utf8_decode(substr(urldecode($request_path[0]), strlen($request_root) + 1));
		if(empty($uri)) {
			$uri = '/';
		}
		return $uri;
	}
}