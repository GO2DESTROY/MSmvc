<?php

namespace system;
class MS_response
{
	private static $header;
	public $controller;

	public static function view($file, $data) {
		self::$header = 'Content-Type: text/html; charset=utf-8';
		$view         = new MS_view();
		$view->__set('data', $data);
		return $view->loadView($file);
	}

	public static function json($data) {
		self::$header = 'Content-Type: application/json';
		return json_encode($data);
	}
}