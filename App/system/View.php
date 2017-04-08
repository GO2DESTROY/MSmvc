<?php

namespace App\system;

/**
 * Class View
 * @package system
 */
class View {
	/**
	 * view: name of the view file
	 * data: array with the variables
	 * @var array
	 */
	private static $view;

	/**
	 * html content of the view file in case of a layout
	 * @var mixed
	 */
	public static $viewHtml;

	/**
	 * the name of the layout
	 * @var string
	 */
	private static $layout;

	/**
	 * @return string
	 */
	public static function getLayout(): string {
		return self::$layout;
	}

	/**
	 * @param string $layout
	 */
	public static function setLayout(string $layout = null) {
		if ($layout !== NULL) {
			self::$layout = str_replace(".php", "", $layout) . ".php";
		}
	}

	/**
	 * @return array
	 */
	public static function getView(): array {
		return self::$view;
	}

	/**
	 * @param array $view
	 */
	public static function setView(array $view) {
		$view["view"] = str_replace(".php", "", $view["view"]) . ".php";
		self::$view = $view;
	}

	public function loadView() {
		$viewFile = new Filesystem(self::getView()['view'], Filesystem::USE_VIEW_PATH);
		$viewFile->setLocalData(self::getView()['data']);
		self::$viewHtml = $viewFile->executeAndReturn();
		if (self::$layout !== NULL) {
			$viewFile = new Filesystem(self::getLayout(), Filesystem::USE_LAYOUT_PATH);
			$viewFile->include();
		} else {
			echo self::$viewHtml;
		}
	}

	/**
	 * @param string $viewName
	 */
	public static function loadPartial(string $viewName) {
		$view = Filesystem::returnViewFilePath($viewName);
		include $view;
	}
}