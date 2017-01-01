<?php
use system\MS_response;

if(!function_exists('dumpArray')) {
	/**
	 * we transform an array to an ul with li elements
	 *
	 * @param $array : the array to dump
	 *
	 * @return string: the html string
	 */
	function dumpArray($array) {
		$string = '<ul>';
		if(is_array($array) || is_object($array)) {
			foreach($array as $key => $value) {
				if(is_array($value) || is_object($value) || is_resource($value)) {
					$value = dumpArray($value);
					$string .= '<li><span class="highlight">' . $key . '</span> <small>' . gettype($value) . ' [' . count($value) . ']</small> ' . $value . ' </li>';
				}
				else {
					$string .= '<li><span class="highlight">' . $key . '</span> ' . $value . ' <small>' . gettype($value) . ' [' . strlen($value) . ']</small></li>';
				}
			}
		}
		else {
			$string .= '<li>' . $array . '</li>';
		}
		$string .= '</ul>';
		return $string;
	}
}
if(!function_exists('view')) {
	/**
	 * @param      $view       : the view to send
	 * @param null $data       : the data to use for the view
	 * @param null $masterView : master to overwrite
	 */
	function view($view, $data = NULL,  $masterView = NULL) {
		MS_response::addViewToCollection($view, $data);
		MS_response::view();
		if($masterView !== NULL) {
			MS_response::overwriteMasterView($masterView);
		}
	}
}
if(!function_exists('dd')) {
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
if(!function_exists('download')) {
	/**
	 * we will start the download of the file with the right headers
	 *
	 * @param $file: the file to download
	 */
	function download($file) {
		MS_response::download($file);
	}
}
if(!function_exists('json')) {
	/**
	 *  we will setup the json response the right header and then we encode the message
	 *
	 * @param $data : the json data to return
	 */
	function json($data) {
		MS_response::json($data);
	}
}
if(!function_exists('renderBody')) {
	/**
	 * This method will be used to render the body inside the layout
	 */
	function renderBody(){
		echo MSmvc\system\MS_view::$viewHtml;
	}
}
if(!function_exists('layout')) {
	/**
	 * This method is used to identify the correct layout
	 * @param string $layoutName: layout to use
	 */
	function layout(string $layoutName){
		MSmvc\system\MS_view::$layout = $layoutName;
	}
}
if(!function_exists('partial')){
	function partial(string $viewName){
		MSmvc\system\MS_view::loadPartial('partials'.DIRECTORY_SEPARATOR.$viewName);
	}
}