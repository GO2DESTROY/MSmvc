<?php
use App\system\Response;

if (!function_exists('view')) {
	/**
	 * @param      $view       : the view to send
	 * @param null $data       : the data to use for the view
	 * @param null $masterView : master to overwrite
	 */
	function view($view, $data = NULL, $masterView = NULL) {
		Response::addViewToCollection($view, $data);
		Response::view();
		if ($masterView !== NULL) {
			Response::overwriteMasterView($masterView);
		}
	}
}
if (!function_exists('dd')) {
	/**
	 * @param $data : the data to dump
	 */
	function dd($data) {
		array_map(function ($x) {
			//todo: check if we are in CLI or HTTP to dump the value
			//(new Dumper)->dump($x);
			var_dump($x);
		}, func_get_args());

		die(1);
	}
}
if (!function_exists('download')) {
	/**
	 * we will start the download of the file with the right headers
	 *
	 * @param $file : the file to download
	 */
	function download($file) {
		Response::download($file);
	}
}
if (!function_exists('json')) {
	/**
	 *  we will setup the json response the right header and then we encode the message
	 *
	 * @param $data : the json data to return
	 */
	function json($data) {
		Response::json($data);
	}
}
if (!function_exists('renderBody')) {
	/**
	 * This method will be used to render the body inside the layout
	 */
	function renderBody() {
		foreach (App\system\View::$viewHtml as $viewHtml){
			echo $viewHtml;
		}
	}
}
if (!function_exists('setLayout')) {
	/**
	 * This method is used to identify the correct layout
	 *
	 * @param string $layoutName : layout to use
	 */
	function setLayout(string $layoutName) {
		App\system\View::setLayout($layoutName);
	}
}
if (!function_exists('partial')) {
	function partial(string $viewName) {
		App\system\View::loadPartial('partials' . DIRECTORY_SEPARATOR . $viewName);
	}
}
if (!function_exists('isAssoc')) {
	/**
	 * @param array $arr
	 *
	 * @return bool
	 */
	function isAssoc($arr) {
		if ([] === $arr || !is_array($arr)) return FALSE;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}
if (!function_exists("string")) {
	/**
	 * @param string $name
	 *
	 * @return \App\system\models\properties\Varchar
	 */
	function string(string $name) {
		$string = new \App\system\models\properties\Varchar();
		$string->name = $name;
		return $string;
	}
}
if (!function_exists("int")) {
	/**
	 * @param string $name
	 *
	 * @return \App\system\models\properties\Integer
	 */
	function int(string $name) {
		$int = new \App\system\models\properties\Integer();
		$int->name = $name;
		return $int;
	}
}