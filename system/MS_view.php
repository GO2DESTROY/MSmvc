<?php

namespace system;

use system\pipelines\MS_pipeline;

class MS_view
{
	public static  $viewCollection;
	public static  $masterFile;
	private static $bundleCollection;


	function __construct() {

	}

	/**
	 * @return mixed: the view files is returned and filled with provided data
	 */
	public function loadMasterView() {
		if(self::$masterFile !== NULL) {
			MS_pipeline::returnConfig('bundle');
			$this->returnMasterBundles();
			$view = MS_pipeline::returnViewFilePath(self::$masterFile['view']);
			if(is_array(self::$masterFile['data'])) {
				extract(self::$masterFile['data'], EXTR_SKIP);
			}
			include $view;
		}
		else {
			foreach(self::$viewCollection as $viewCollectionItem) {
				$view = MS_pipeline::returnViewFilePath($viewCollectionItem['view']);
				if(is_array($viewCollectionItem['data'])) {
					extract($viewCollectionItem['data'], EXTR_SKIP);
				}
				include $view;
			}
		}
	}
//todo: improve the bundles and bundle getters / setters
	private function returnMasterBundles() {
		self::$bundleCollection['scripts']     = MS_bundle::returnScriptCollection(self::$masterFile['view']);
		self::$bundleCollection['stylesheets'] = MS_bundle::returnStyleCollection(self::$masterFile['view']);
	}

	public static function loadViewCollectionItem($name) {

	}
}