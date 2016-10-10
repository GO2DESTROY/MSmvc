<?php

namespace system;

use system\pipelines\MS_pipeline;

/**
 * Class MS_view
 * @package system
 */
class MS_view
{
	public static  $view;
	public static $viewHtml;
	public static  $layout;
	private static $bundleCollection;

	function __construct() {

	}

	/**
	 * the view will be loaded with the data if needed and returns the needed html for the layout
	 */
	public function loadView() {
		//todo: problem with extraction of variables
		$viewFile = MS_pipeline::returnViewFilePath(self::$view['view']);
		if(is_array(self::$view['data'])) {
			extract(self::$view['data'], EXTR_SKIP);
		}
        self::$viewHtml = MS_pipeline::executeAndReturnFileContent($viewFile, self::$view['data']);
		if(self::$layout !== NULL){
			 include MS_pipeline::returnViewFilePath(self::$layout);
		}
		else{
			echo self::$viewHtml;
		}
	}
	public static function loadPartial(string $viewName){
		$view = MS_pipeline::returnViewFilePath($viewName);
		include $view;
	}
//todo: improve the bundles and bundle getters / setters
	private function returnMasterBundles() {
		self::$bundleCollection['scripts']     = MS_bundle::returnScriptCollection(self::$layout['view']);
		self::$bundleCollection['stylesheets'] = MS_bundle::returnStyleCollection(self::$layout['view']);
	}

	public static function loadViewCollectionItem($name) {

	}
}