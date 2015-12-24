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
if(!function_exists('masterView')) {
	/**
	 * @param $view : the view to set as the master-view
	 * @param $data : the data to use for the view
	 */
	function masterView($view, $data) {
		MS_response::overwriteMasterView($view, $data);
	}
}
if(!function_exists('view')) {
	/**
	 * @param      $view       : the view to send
	 * @param null $data       : the data to use for the view
	 * @param null $name       : name to use for this view to overwrite the section
	 * @param null $masterView : master to overwrite
	 * @param null $masterData : master data to overwrite
	 */
	function view($view, $data = NULL, $name = NULL, $masterView = NULL, $masterData = NULL) {
		MS_response::addViewToCollection($name, $view, $data);
		MS_response::view();
		if($masterView !== NULL) {
			MS_response::overwriteMasterView($masterView, $masterData);
		}
	}
}
if(!function_exists('dd')) {
	/**
	 * @param $data : the data to dump
	 */
	function dd($data) {
		array_map(function ($x) {
			var_dump($x);
		}, func_get_args());
		exit(1);
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